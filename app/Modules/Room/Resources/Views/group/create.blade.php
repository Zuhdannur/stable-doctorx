@extends('backend.layouts.app')

@section('title', __('room::labels.group.management') . ' | ' . __('room::labels.group.create'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('room::labels.group.management') <small class="text-muted">@lang('room::labels.group.create')</small></h3>
        
    </div>
    <div class="block-content">
        {{ html()->form('POST', route('admin.room.group.store'))->class('form-horizontal')->attribute(' autocomplete="off" data-parsley-validate')->open() }}
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
                                ->required()->attribute(' data-parsley-required="true"')
                                ->autofocus() }}
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
                                    <option value="{{ $item->id }}" {{ ( $item->id == old('floor_id')) ? 'selected' : '' }}> {{ $item->name }} </option>
                                @endforeach    
                           </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label('Type')
                            ->class('col-md-2 form-control-label')
                            ->for('room_type') }}

                        <div class="col-md-10">
                            <select class="form-control" id="type" name="type" data-parsley-required="true">
                                <option value="">Pilih</option>

                                <option value="APPOINTMENT"> APPOINTMENT </option>
                                <option value="TREATMENT"> TREATMENT </option>
                                <option value="BILLING"> BILLING </option>
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
                    {{ form_submit(__('buttons.general.crud.create')) }}
                </div>
            </div>
        {{ html()->form()->close() }}
    </div>
</div>


@endsection
