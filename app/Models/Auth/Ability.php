<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Auth\Traits\Method\AbilityMethod;
use App\Models\Auth\Traits\Attribute\AbilityAttribute;

/**
 * Class Ability.
 */
class Ability extends \Silber\Bouncer\Database\Ability
{
    use AbilityAttribute,
        AbilityMethod, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'title', 'group'];
}
