<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelAccess extends Model
{
    protected $table = "modules_access";

    public $primaryKey = "id_access";

    public $guarded = [];

    public $timestamps = false;

    public function modul() {
        return $this->hasOne('\App\Modul','id_modul','id_modul');
    }
}
