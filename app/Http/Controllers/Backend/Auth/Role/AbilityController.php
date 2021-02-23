<?php

namespace App\Http\Controllers\Backend\Auth\Role;

use App\Models\Auth\Ability;
use App\Http\Controllers\Controller;
use App\Events\Backend\Auth\Role\AbilityDeleted;
use App\Repositories\Backend\Auth\RoleRepository;
use App\Repositories\Backend\Auth\AbilityRepository ;
use App\Http\Requests\Backend\Auth\Role\StoreAbilityRequest;
use App\Http\Requests\Backend\Auth\Role\ManageAbilityRequest;
use App\Http\Requests\Backend\Auth\Role\UpdateAbilityRequest;

/**
 * Class RoleController.
 */
class AbilityController extends Controller
{
    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * @var AbilityRepository 
     */
    protected $abilityRepository ;

    /**
     * @param RoleRepository       $roleRepository
     * @param AbilityRepository  $abilityRepository 
     */
    public function __construct(RoleRepository $roleRepository, AbilityRepository  $abilityRepository )
    {
        $this->roleRepository = $roleRepository;
        $this->abilityRepository  = $abilityRepository ;
    }

    /**
     * @param ManageAbilityRequest $request
     *
     * @return mixed
     */
    public function index(ManageAbilityRequest $request)
    {
        return view('backend.auth.ability.index')
            ->withAbilities($this->abilityRepository
                ->orderBy('id', 'asc')
                ->paginate(25));
    }

    /**
     * @param ManageAbilityRequest $request
     *
     * @return mixed
     */
    public function create(ManageAbilityRequest $request)
    {
        return view('backend.auth.ability.create');
    }

    /**
     * @param StoreAbilityRequest $request
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function store(StoreAbilityRequest $request)
    {
        $this->abilityRepository->create($request->only('name', 'title', 'group'));

        return redirect()->route('admin.auth.ability.index')->withFlashSuccess(__('alerts.backend.abilities.created'));
    }

    /**
     * @param ManageAbilityRequest $request
     * @param Ability              $ability
     *
     * @return mixed
     */
    public function edit(ManageAbilityRequest $request, Ability $ability)
    {
        if ($ability->isAdmin()) {
            return redirect()->route('admin.auth.ability.index')->withFlashDanger('You can not edit the administrator ability.');
        }

        return view('backend.auth.ability.edit')
            ->withAbility($ability);
    }

    /**
     * @param UpdateAbilityRequest $request
     * @param Ability              $role
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function update(UpdateAbilityRequest $request, Ability $ability)
    {
        $this->abilityRepository->update($ability, $request->only('name', 'group'));

        return redirect()->route('admin.auth.ability.index')->withFlashSuccess(__('alerts.backend.abilities.updated'));
    }

    /**
     * @param ManageAbilityRequest $request
     * @param Ability              $ability
     *
     * @return mixed
     * @throws \Exception
     */
    public function destroy(ManageAbilityRequest $request, Ability $ability)
    {
        if ($ability->isAdmin()) {
            return redirect()->route('admin.auth.ability.index')->withFlashDanger(__('exceptions.backend.access.abilities.cant_delete_admin'));
        }

        $this->abilityRepository->deleteById($ability->id);

        event(new AbilityDeleted($ability));

        return redirect()->route('admin.auth.ability.index')->withFlashSuccess(__('alerts.backend.abilities.deleted'));
    }
}
