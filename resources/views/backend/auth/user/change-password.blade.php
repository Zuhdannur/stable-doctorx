@extends('backend.layouts.app')

@section('title', __('labels.backend.access.users.management') . ' | ' . __('labels.backend.access.users.change_password'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('labels.backend.access.users.management') <small class="text-muted">@lang('labels.backend.access.users.change_password') | @lang('labels.backend.access.users.change_password_for', ['user' => $user->name])</small></h3>
        <div class="block-options">
            @include('backend.auth.user.includes.breadcrumb-links')
        </div>
    </div>
    <div class="block-content">
        {{ html()->form('PATCH', route('admin.auth.user.change-password.post', $user))->class('form-horizontal')->open() }}
            <div class="row mt-4 mb-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label(__('validation.attributes.backend.access.users.password'))->class('col-md-2 form-control-label')->for('password') }}

                        <div class="col-md-10">
                            {{ html()->password('password')
                                ->class('form-control')
                                ->placeholder( __('validation.attributes.backend.access.users.password'))
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('validation.attributes.backend.access.users.password_confirmation'))->class('col-md-2 form-control-label')->for('password_confirmation') }}

                        <div class="col-md-10">
                            {{ html()->password('password_confirmation')
                                ->class('form-control')
                                ->placeholder( __('validation.attributes.backend.access.users.password_confirmation'))
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->
                </div><!--col-->
            </div><!--row-->

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('admin.auth.user.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
            </div>
        {{ html()->form()->close() }}
    </div>

</div>
@endsection
