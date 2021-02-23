<?php

namespace App\Modules\Room\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Room\Models\Traits\Attribute\RoomAttribute;

class Room extends Model
{
    use RoomAttribute;

	protected $fillable = ['name', 'room_group_id','id_klinik'];

    public function group()
    {
        return $this->hasOne('App\Modules\Room\Models\RoomGroup', 'id', 'room_group_id');
    }
}
