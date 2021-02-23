<?php

namespace App\Modules\Room\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Room\Models\Traits\Attribute\RoomGroupAttribute;

class RoomGroup extends Model
{
	use RoomGroupAttribute;

	protected $fillable = ['name', 'description', 'floor_id', 'type','id_klinik'];

    public function floor()
    {
        return $this->hasOne('App\Modules\Room\Models\Floor', 'id', 'floor_id');
    }

    public function room()
    {
        return $this->hasOne('App\Modules\Room\Models\Room', 'id', 'room_group_id');
    }
}
