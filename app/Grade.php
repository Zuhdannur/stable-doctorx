<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = "t_grade";
    protected $guarded = [];

    public $timestamps = false;
    public $primaryKey = "id_grade";
}
