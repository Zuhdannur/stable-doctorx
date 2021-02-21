<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Klinik extends Model
{
    protected $table = "t_master_klinik";
    public $primaryKey = "id_klinik";

    public $timestamps = false;
    protected $guarded = [];
}
