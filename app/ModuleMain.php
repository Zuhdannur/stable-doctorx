<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleMain extends Model
{
    protected $table = "modules_main";

    protected $primaryKey = "id_modules";

    public $guarded = [''];

    public $timestamps = false;

    public function sub() {
        return $this->hasMany('\App\ModuleSub','id_modules','id_modules');
    }

    public function modul() {
        return $this->hasOne('\App\Modul','id_modul','id_modul');
    }
}
