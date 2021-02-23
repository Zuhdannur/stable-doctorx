@extends('backend.layouts.app')

@section('title', __('patient::labels.diagnoseitem.management') . ' | ' . __('patient::labels.diagnoseitem.edit'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('patient::labels.diagnoseitem.management') <small class="text-muted">@lang('patient::labels.diagnoseitem.edit')</small></h3>
        
    </div>
    <div class="block-content">
        {{ html()->modelForm($diagnoseitem, 'PATCH', route('admin.masterdata.diagnoseitem.update', $diagnoseitem))->class('form-horizontal')->open() }}
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label('Kode')
                            ->class('col-md-2 form-control-label')
                            ->for('code') }}

                        <div class="col-md-10">
                            {{ html()->text('code')
                                ->class('form-control')
                                ->placeholder('Kode')
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        {{ html()->label('Nama')
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-10">
                            {{ html()->text('name')
                                ->class('form-control')
                                ->placeholder('Nama')
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        {{ html()->label('Deskripsi')
                            ->class('col-md-2 form-control-label')
                            ->for('description') }}

                        <div class="col-md-10">
                            {{ html()->text('description')
                                ->class('form-control')
                                ->placeholder('Deskripsi')
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('admin.masterdata.diagnoseitem.index'), __('buttons.general.cancel')) }}
                </div>

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div>
            </div>
        {{ html()->closeModelForm() }}
    </div>
</div>
@endsection
