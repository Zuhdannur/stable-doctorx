<?php

namespace App\Events\Backend\Auth\Role;

use Illuminate\Queue\SerializesModels;

/**
 * Class AbilityUpdated.
 */
class AbilityUpdated
{
    use SerializesModels;

    /**
     * @var
     */
    public $ability;

    /**
     * @param $ability
     */
    public function __construct($ability)
    {
        $this->ability = $ability;
    }
}
