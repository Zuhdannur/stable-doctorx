<?php

namespace App\Modules\Room\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Room\Models\Traits\Attribute\FloorAttribute;

class Floor extends Model
{
	use FloorAttribute;

	protected $fillable = ['name','id_klinik'];


}
