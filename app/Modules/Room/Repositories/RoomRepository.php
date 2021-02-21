<?php

namespace App\Modules\Room\Repositories;

use App\Modules\Room\Models\Room;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;

class RoomRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Room::class;
    }

    public function create(array $data) : Room
    {
        // Make sure it doesn't already exist
        if ($this->nameExists($data['name'], $data['room_group_id'])) {
            throw new GeneralException(trans('room::exceptions.room.already_exists') . ' :: '.$data['name']);
        }

        return DB::transaction(function () use ($data) {
            $save = parent::create([
                'name' => $data['name'],
                'room_group_id' => $data['room_group_id'],
                'id_klinik' => Auth()->user()->klinik->id_klinik
            ]);

            if ($save) {
                return $save;
            }

            throw new GeneralException(trans('room::exceptions.room.create_error'));
        });
    }

    public function update(Room $roomGroup, array $data)
    {
        // If the name is changing make sure it doesn't already exist
        if (strtolower($roomGroup->name) !== strtolower($data['name'])) {
            if ($this->nameExists($data['name'], $data['room_group_id'])) {
                throw new GeneralException(trans('room::exceptions.room.already_exists') . ' :: '.$data['name']);
            }
        }

        return DB::transaction(function () use ($roomGroup, $data) {
            if ($roomGroup->update([
                'name' => $data['name'],
                'room_group_id' => $data['room_group_id']
            ])) {

                return $roomGroup;
            }

            throw new GeneralException(trans('room::exceptions.room.update_error'));
        });
    }

    protected function nameExists($name, $room_group_id) : bool
    {
        return $this->model
                ->where('name', strtolower($name))
                ->where('room_group_id', strtolower($room_group_id))
                ->count() > 0;
    }

    public function getByGroupId($groupId){
        return $this->model->where('room_group_id', $groupId)->get();
    }
}
