@extends('backend.layouts.app')

@section('title', __('labels.backend.access.users.management') . ' | ' . __('labels.backend.access.users.edit'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('labels.backend.access.users.management') <small class="text-muted">@lang('labels.backend.access.users.edit')</small></h3>
        <div class="block-options">
            @include('backend.auth.user.includes.breadcrumb-links')
        </div>
    </div>
    <div class="block-content">
        {{ html()->modelForm($user, 'PATCH', route('admin.auth.user.update', $user->id))->class('form-horizontal')->open() }}
            <div class="row mt-4 mb-4">
                <div class="col">
                    <div class="form-group row">
                            {{ html()->label('Nama Lengkap')->class('col-md-2 form-control-label')->for('full_name') }}

                            <div class="col-md-10">
                                {{ html()->text('full_name')
                                    ->class('form-control')
                                    ->placeholder('Nama Lengkap')
                                    ->attribute('maxlength', 191)
                                    ->required()
                                    ->autofocus()->attribute('autocomplete', 'off') }}
                            </div><!--col-->
                        </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('validation.attributes.backend.access.users.email'))->class('col-md-2 form-control-label')->for('email') }}

                        <div class="col-md-10">
                            {{ html()->email('email')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.users.email'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->
                    <div class="form-group row">
                        {{ html()->label("Klinik")->class('col-md-2 form-control-label')->for('password_confirmation') }}

                        <div class="col-md-8">
                            <select name="id_klinik" id="klinik" class="form-control select">
                                @foreach(\App\Klinik::all() as $item)
                                    <option value="{{ @$item->id_klinik }}" {{ @$user->klinik->id_klinik == $item->id_klinik ? 'selected' : '' }}>{{ @$item->nama_klinik.' - '.@$item->status }}</option>
                                @endforeach
                            </select>
                        </div><!--col-->
                    </div><!--form-group-->

                        <div class="form-group row">
                            {{ html()->label(__('labels.backend.access.users.table.roles'))->class('col-md-2 form-control-label') }}

                            <div class="col-md-10">
                                @if($roles->count())
                                    <!-- {{ json_encode($roles->groupBy('group')) }} -->
                                    <div class="row no-gutters items-push">
                                    @foreach($roles as $role)
                                    @php
                                        $dataPermisson = array();
                                        //die(json_encode($role->abilities));
                                        if($role->name != 'superadmin'){
                                            if($role->abilities->count()){
                                                foreach($role->abilities as $permission){
                                                    $dataPermisson[] = ucwords($permission->name);
                                                }
                                            }else{
                                                $dataPermisson[] = __('labels.general.none');
                                            }
                                        }else{
                                            $dataPermisson[] = __('labels.backend.access.users.all_permissions');
                                        }

                                        $listPermisson = implode (", ", $dataPermisson);
                                    @endphp

                                    <div class="col-12">
                                        <div class="custom-control custom-checkbox custom-control-inline mb-5">
                                            {{ html()->checkbox('roles[]', in_array($role->name, $userRoles), $role->name)->class('custom-control-input')->id('role-'.$role->id) }}

                                            {{ html()->label('<a data-toggle="collapse" href="#permission-role-'.$role->id.'" role="button" aria-expanded="false" aria-controls="#permission-role-'.$role->id.'">'.ucwords($role->name).'</a>')->class('custom-control-label')->for('role-'.$role->id) }}
                                        </div>

                                        <div class="collapse" id="permission-role-{{ $role->id }}">
                                            <div class="card card-body">
                                                @if($role->name != 'superadmin')
                                                    @if($role->abilities->count())
                                                    <div class="block mb-0 bg-body-dark">
                                                        <div class="block-content">
                                                            <div class="row">
                                                                @foreach($role->abilities->groupBy('group') as $key => $permission)
                                                                <div class="col-md-4 col-xl-3">
                                                                    <label>{{ ucwords($key) }}</label>
                                                                    <ol class="fa-ul">
                                                                        @foreach($permission as $items)
                                                                        <li><i class="fa fa-check fa-li"></i><span class="badge badge-success">{{ ucwords($items->name) }}</span></li>
                                                                        @endforeach
                                                                    </ol>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @else
                                                        <span class="badge badge-pill badge-warning">
                                                            <i class="fa fa-exclamation-circle mr-5"></i>@lang('labels.general.none')
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-pill badge-info">
                                                        <i class="fa fa-check mr-5"></i>@lang('labels.backend.access.users.all_permissions')
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    </div>
                                @endif
                            </div>
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ html()->label(__('labels.backend.access.users.table.other_permissions'))->class('col-md-2 form-control-label') }}

                            <div class="col-md-10">
                                @if($abilities->count())
                                    <div class="row">
                                        @foreach($abilities->groupBy('group') as $key => $permission)
                                        <div class="col-md-4 col-xl-3">
                                            <label>{{ ucwords($key) }}</label>
                                            <div>
                                                @foreach($permission as $items)
                                                <div class="custom-control custom-checkbox mb-5">
                                                    {{ html()->checkbox('abilities[]', in_array($items->name, $userAbilities), $items->name)->class('custom-control-input')->id('permission-'.$items->id) }}
                                                    {{ html()->label(ucwords($items->name))->class('custom-control-label')->for('permission-'.$items->id) }}
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                        </div><!--form-group-->
                </div><!--col-->
            </div><!--row-->

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('admin.auth.user.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.save')) }}
                </div><!--col-->
            </div>
    {{ html()->form()->close() }}
    </div>
</div>

@endsection
