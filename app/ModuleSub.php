<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleSub extends Model
{
    protected $table = "modules_sub";

    protected $primaryKey = "id_sub_modules";

    public $guarded = [''];

    public $timestamps = false;

    public function modul() {
        return $this->hasOne('\App\Modul','id_modul','id_modul');
    }

    public function sub() {
        return $this->hasMany('\App\ModuleSubSub','id_sub_modules','id_sub_modules');
    }
}
