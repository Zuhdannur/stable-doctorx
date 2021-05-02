<?php

namespace App\Models\Auth;

use App\Models\Auth\Traits\Method\RoleMethod;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Auth\Traits\Attribute\RoleAttribute;

/**
 * Class Role.
 */
class Role extends \Silber\Bouncer\Database\Role
{
    use RoleAttribute,
        RoleMethod, SoftDeletes;

    public function modul() {
        return $this->hasMany('\App\ModelAccess','id_user','id');
    }
}
