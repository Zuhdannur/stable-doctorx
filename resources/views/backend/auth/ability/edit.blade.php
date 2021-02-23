@extends('backend.layouts.app')

@section('title', __('labels.backend.access.abilities.management') . ' | ' . __('labels.backend.access.abilities.edit'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('labels.backend.access.abilities.management') <small class="text-muted">@lang('labels.backend.access.abilities.edit')</small></h3>
        
    </div>
    <div class="block-content">
        {{ html()->modelForm($ability, 'PATCH', route('admin.auth.ability.update', $ability))->class('form-horizontal')->open() }}
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label(__('validation.attributes.backend.access.abilities.name'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-10">
                            {{ html()->text('name')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.abilities.name'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->
                </div><!--col-->
            </div><!--row-->

            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label(__('validation.attributes.backend.access.abilities.group'))
                            ->class('col-md-2 form-control-label')
                            ->for('group') }}

                        <div class="col-md-10">
                            {{ html()->text('group')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.abilities.group'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->
                </div><!--col-->
            </div><!--row-->

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('admin.auth.ability.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
            </div>
        {{ html()->closeModelForm() }}
    </div>
</div>
@endsection
