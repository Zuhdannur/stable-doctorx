<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecordMapping extends Model
{
    protected $table = "t_record_mapping";
    protected $guarded = [];

    public $primaryKey = "id_mapping";

    public $timestamps = false;
}
