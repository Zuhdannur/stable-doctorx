@extends('backend.layouts.app')

@section('title', __('room::labels.group.management') . ' | ' . __('room::labels.group.edit'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('room::labels.group.management') <small class="text-muted">@lang('room::labels.group.edit')</small></h3>
        
    </div>
    <div class="block-content">
        {{ html()->modelForm($roomgroup, 'PATCH', route('admin.room.group.update', $roomgroup))->class('form-horizontal')->open() }}
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label(__('room::validation.group.name'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-10">
                            {{ html()->text('name')
                                ->class('form-control')
                                ->placeholder(__('room::validation.group.name'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('room::validation.group.description'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-10">
                            {{ html()->text('description')
                                ->class('form-control')
                                ->placeholder(__('room::validation.group.description'))
                                ->attribute('maxlength', 191)
                                ->required()->attribute(' data-parsley-required="true"')
                                ->autofocus() }}
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        {{ html()->label(__('room::validation.group.floor'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-10">
                            <select class="form-control" id="floor_id" name="floor_id" data-parsley-required="true">
                                <option value="">Pilih</option>

                                @foreach ($floor as $item)
                                    <option value="{{ $item->id }}" {{ ( $item->id == (old('floor_id') ? old('floor_id') : $roomgroup->floor_id)) ? 'selected' : '' }}> {{ $item->name }} </option>
                                @endforeach    
                           </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('admin.room.group.index'), __('buttons.general.cancel')) }}
                </div>

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div>
            </div>
        {{ html()->closeModelForm() }}
    </div>
</div>
@endsection
