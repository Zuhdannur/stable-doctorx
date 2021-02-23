@extends('backend.layouts.app')

@section('title', __('labels.backend.access.roles.management') . ' | ' . __('labels.backend.access.roles.edit'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('labels.backend.access.roles.management') <small class="text-muted">@lang('labels.backend.access.roles.edit')</small></h3>

    </div>
    <div class="block-content">
        {{ html()->modelForm($role, 'PATCH', route('admin.auth.role.update', $role))->class('form-horizontal')->open() }}
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label(__('validation.attributes.backend.access.roles.name'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-10">
                            {{ html()->text('name')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.roles.name'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('validation.attributes.backend.access.roles.associated_abilities'))
                            ->class('col-md-2 form-control-label')
                            ->for('abilities') }}

                        <div class="col-md-10">
                            @if($abilities->count())
                                <div class="row">
                                    @foreach($abilities->groupBy('group') as $key => $ability)
                                    <div class="col-md-4 col-xl-3">
                                        <label>{{ ucwords($key) }}</label>
                                        <div>
                                            @foreach($ability as $items)
                                            <div class="custom-control custom-checkbox mb-5">
                                                {{ html()->checkbox('abilities[]', in_array($items->name, $roleAbilities), $items->name)->class('custom-control-input')->id('ability-'.$items->id) }}
                                                {{ html()->label(ucwords($items->name))->class('custom-control-label')->for('ability-'.$items->id) }}
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div><!--col-->
                    </div><!--form-group-->
                </div><!--col-->
            </div><!--row-->

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('admin.auth.role.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
            </div>
        {{ html()->closeModelForm() }}
    </div>
</div>
@endsection
