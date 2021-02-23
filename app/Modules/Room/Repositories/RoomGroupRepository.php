<?php

namespace App\Modules\Room\Repositories;

use App\Helpers\Auth\Auth;
use App\Modules\Room\Models\RoomGroup;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;

class RoomGroupRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return RoomGroup::class;
    }

    public function create(array $data) : RoomGroup
    {
        // Make sure it doesn't already exist
        if ($this->nameExists($data['name'], $data['floor_id'])) {
            throw new GeneralException(trans('room::exceptions.group.already_exists') . ' :: '.$data['name']);
        }

        return DB::transaction(function () use ($data) {
            $save = parent::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'floor_id' => $data['floor_id'],
                'type' => $data['type'],
                'id_klinik' => Auth()->user()->klinik->id_klinik
            ]);

            if ($save) {
                return $save;
            }

            throw new GeneralException(trans('room::exceptions.group.create_error'));
        });
    }

    public function update(RoomGroup $roomGroup, array $data)
    {
        // If the name is changing make sure it doesn't already exist
        if (strtolower($roomGroup->name) !== strtolower($data['name'])) {
            if ($this->nameExists($data['name'], $data['floor_id'])) {
                throw new GeneralException(trans('room::exceptions.group.already_exists') . ' :: '.$data['name']);
            }
        }

        return DB::transaction(function () use ($roomGroup, $data) {
            if ($roomGroup->update([
                'name' => $data['name'],
                'description' => $data['description'],
                'floor_id' => $data['floor_id']
            ])) {

                return $roomGroup;
            }

            throw new GeneralException(trans('room::exceptions.group.update_error'));
        });
    }

    protected function nameExists($name, $floor_id ) : bool
    {
        return $this->model
                ->where('name', strtolower($name))
                ->where('floor_id', strtolower($floor_id))
                ->where('id_klinik',Auth()->user()->klinik->id_klinik)
                ->count() > 0;
    }
}
