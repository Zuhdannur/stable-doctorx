@extends('base.app')

@section('title', app_name() . ' | User Management Access')

@section('content')
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('labels.backend.access.users.management') <small class="text-muted">@lang('labels.backend.access.users.edit')</small></h3>
            <div class="block-options">
                @include('backend.auth.user.includes.breadcrumb-links')
            </div>
        </div>
        <div class="block-content">
            {{ html()->modelForm($user, 'PATCH', route('role-module.update', $user->id))->class('form-horizontal')->open() }}

            <div class="row mt-4 mb-4">
                <div class="col">

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.access.users.table.other_permissions'))->class('col-md-2 form-control-label') }}

                        <div class="col-md-10">
                            <div class="row">
                                @foreach(\App\Modul::all() as $row)
                                    <div class="col-md-4 col-xl-3">
                                        <div>

                                            <div class="custom-control custom-checkbox mb-5">
                                                {{ html()->checkbox('modul[]', in_array($row->id_modul, $modul), $row->id_modul)->class('custom-control-input')->id('permission-'.$row->id_modul) }}
                                                {{ html()->label(ucwords($row->nama_modul))->class('custom-control-label')->for('permission-'.$row->id_modul) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
