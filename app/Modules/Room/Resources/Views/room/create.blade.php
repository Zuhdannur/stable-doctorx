@extends('backend.layouts.app')

@section('title', __('room::labels.room.management') . ' | ' . __('room::labels.room.create'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('room::labels.room.management') <small class="text-muted">@lang('room::labels.room.create')</small></h3>
        
    </div>
    <div class="block-content">
        {{ html()->form('POST', route('admin.room.store'))->class('form-horizontal')->attribute(' autocomplete="off" data-parsley-validate')->open() }}
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label(__('room::validation.room.name'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-10">
                            {{ html()->text('name')
                                ->class('form-control')
                                ->placeholder(__('room::validation.room.name'))
                                ->attribute('maxlength', 191)
                                ->required()->attribute(' data-parsley-required="true"')
                                ->autofocus() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('room::validation.room.group'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-10">
                            <select class="form-control" id="room_group_id" name="room_group_id" data-parsley-required="true">
                                <option value="">Pilih</option>

                                @foreach ($roomgroup as $item)
                                    <option value="{{ $item->id }}" {{ ( $item->id == old('room_group_id')) ? 'selected' : '' }}> {{ $item->name }} - {{ $item->floor['name'] }}</option>
                                @endforeach    
                           </select>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('admin.room.index'), __('buttons.general.cancel')) }}
                </div>

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.create')) }}
                </div>
            </div>
        {{ html()->form()->close() }}
    </div>
</div>


@endsection
