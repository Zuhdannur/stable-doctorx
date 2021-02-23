<?php

namespace App\Repositories\Backend\Auth;

use App\Models\Auth\Ability;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use App\Events\Backend\Auth\Role\AbilityCreated;
use App\Events\Backend\Auth\Role\AbilityUpdated;

/**
 * Class AbilityRepository.
 */
class AbilityRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Ability::class;
    }

    /**
     * @param array $data
     *
     * @return Ability
     * @throws GeneralException
     */
    public function create(array $data) : Ability
    {
        // Make sure it doesn't already exist
        if ($this->abilityExists($data['name'])) {
            throw new GeneralException('A ability already exists with the name '.$data['name']);
        }

        return DB::transaction(function () use ($data) {

            $ability = parent::create([
            	'name' => strtolower($data['name']), 
            	'title' => ucwords(strtolower($data['name'])), 
            	'group' => ucwords(strtolower($data['group']))
            ]);

            if ($ability) {

                event(new AbilityCreated($ability));

                return $ability;
            }

            throw new GeneralException(trans('exceptions.backend.access.abilities.create_error'));
        });
    }

    /**
     * @param Ability  $ability
     * @param array $data
     *
     * @return mixed
     * @throws GeneralException
     */
    public function update(Ability $ability, array $data)
    {
        if ($ability->isAdmin()) {
            throw new GeneralException('You can not edit the administrator ability.');
        }

        // If the name is changing make sure it doesn't already exist
        if ($ability->name !== strtolower($data['name'])) {
            if ($this->abilityExists($data['name'])) {
                throw new GeneralException('A ability already exists with the name '.$data['name']);
            }
        }

        return DB::transaction(function () use ($ability, $data) {
            if ($ability->update([
                'name' => strtolower($data['name']),
                'title' => ucwords(strtolower($data['name'])),
                'group' => ucwords(strtolower($data['group'])),
            ])) {
                event(new AbilityUpdated($ability));

                return $ability;
            }

            throw new GeneralException(trans('exceptions.backend.access.abilities.update_error'));
        });
    }

    /**
     * @param $name
     *
     * @return bool
     */
    protected function abilityExists($name) : bool
    {
        return $this->model
                ->where('name', strtolower($name))
                ->count() > 0;
    }
}
