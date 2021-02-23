<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleSubSubSub extends Model
{
    protected $table = "modules_sub_sub_sub";

    protected $primaryKey = "id_sub_sub_sub_modules";

    public $guarded = [''];

    public $timestamps = false;

    public function modul() {
        return $this->hasOne('\App\Modul','id_modul','id_modul');
    }
}
