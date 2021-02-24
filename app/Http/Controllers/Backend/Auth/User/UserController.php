<?php

namespace App\Http\Controllers\Backend\Auth\User;

use App\Helpers\Auth\Auth;
use App\Models\Auth\User;
use App\Http\Controllers\Controller;
use App\Events\Backend\Auth\User\UserDeleted;
use App\Repositories\Backend\Auth\RoleRepository;
use App\Repositories\Backend\Auth\UserRepository;
use App\Repositories\Backend\Auth\AbilityRepository;
use App\Http\Requests\Backend\Auth\User\StoreUserRequest;
use App\Http\Requests\Backend\Auth\User\ManageUserRequest;
use App\Http\Requests\Backend\Auth\User\UpdateUserRequest;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ManageUserRequest $request)
    {
        $id_cabang = "all";
        if(!empty($request->id_cabang) && @$request->id_cabang != "all") {
            $id_cabang = $request->id_cabang;
            $users = $this->userRepository->getActivePaginated(25, 'id', 'asc',$request->id_cabang);
        } else {
            $users = $this->userRepository->getActivePaginated(25, 'id', 'asc');
        }

        if(Auth()->user()->klinik->status == "cabang") {
            $users = $this->userRepository->getActivePaginatedSuper(25, 'id', 'asc');
        }

        return view('backend.auth.user.index')
            ->withCabang($id_cabang)
            ->withUsers($users);
    }

    /**
     * @param ManageUserRequest    $request
     * @param RoleRepository       $roleRepository
     * @param AbilityRepository    $abilityRepository
     *
     * @return mixed
     */
    public function create(ManageUserRequest $request, RoleRepository $roleRepository, AbilityRepository $abilityRepository)
    {
        return view('backend.auth.user.create')
            ->withRoles($roleRepository->get(['id', 'name', 'title']))
            ->withAbilities($abilityRepository->get(['id', 'name', 'group']));
    }

    /**
     * @param StoreUserRequest $request
     *
     * @return mixed
     * @throws \Throwable
     */
    public function store(StoreUserRequest $request)
    {
        $this->userRepository->create($request->only(
            'full_name',
            'username',
            'email',
            'password',
            'active',
            'confirmed',
            'confirmation_email',
            'roles',
            'abilities',
            'id_klinik'
        ));

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.created'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $user
     *
     * @return mixed
     */
    public function show(ManageUserRequest $request, User $user)
    {
        return view('backend.auth.user.show')
            ->withUser($user);
    }

    /**
     * @param ManageUserRequest    $request
     * @param RoleRepository       $roleRepository
     * @param AbilityRepository    $abilityRepository
     * @param User                 $user
     *
     * @return mixed
     */
    public function edit(ManageUserRequest $request, RoleRepository $roleRepository, AbilityRepository $abilityRepository, User $user)
    {
        return view('backend.auth.user.edit')
            ->withUser($user)
            ->withRoles($roleRepository->get())
            ->withUserRoles($user->roles->pluck('name')->all())
            ->withAbilities($abilityRepository->get(['id', 'name', 'group']))
            ->withUserAbilities($user->abilities->pluck('name')->all());
    }

    /**
     * @param UpdateUserRequest $request
     * @param User              $user
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userRepository->update($user, $request->only(
            'full_name',
            'email',
            'roles',
            'abilities',
            'id_klinik'
        ));

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.updated'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $user
     *
     * @return mixed
     * @throws \Exception
     */
    public function destroy(ManageUserRequest $request, User $user)
    {
        $this->userRepository->deleteById($user->id);

        event(new UserDeleted($user));

        return redirect()->route('admin.auth.user.deleted')->withFlashSuccess(__('alerts.backend.users.deleted'));
    }
}
