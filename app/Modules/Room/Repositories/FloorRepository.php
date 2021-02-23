<?php

namespace App\Modules\Room\Repositories;

use App\Helpers\Auth\Auth;
use App\Modules\Room\Models\Floor;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;

class FloorRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Floor::class;
    }

    public function create(array $data) : Floor
    {
        // Make sure it doesn't already exist
        if ($this->nameExists($data['name'])) {
            throw new GeneralException(trans('exceptions.backend.room.floor.already_exists') . ' :: '.$data['name']);
        }

        return DB::transaction(function () use ($data) {
            $feeGroup = parent::create([
                'name' => $data['name'],
                'id_klinik' => Auth()->user()->klinik->id_klinik
            ]);

            if ($feeGroup) {
                return $feeGroup;
            }

            throw new GeneralException(trans('room::exceptions.floor.create_error'));
        });
    }

    public function update(Floor $floor, array $data)
    {
        // If the name is changing make sure it doesn't already exist
        if (strtolower($floor->name) !== strtolower($data['name'])) {
            if ($this->nameExists($data['name'])) {
                throw new GeneralException(trans('exceptions.backend.room.floor.already_exists') . ' :: '.$data['name']);
            }
        }

        return DB::transaction(function () use ($floor, $data) {
            if ($floor->update([
                'name' => $data['name']
            ])) {

                return $floor;
            }

            throw new GeneralException(trans('exceptions.backend.room.floor.update_error'));
        });
    }

    protected function nameExists($name) : bool
    {
        return count($this->model
                ->where('name', strtolower($name))
                ->where('id_klinik',Auth()->user()->klinik->id_klinik)
                ->get()) > 0;
    }
}
