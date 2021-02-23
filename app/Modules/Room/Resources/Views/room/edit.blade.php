@extends('backend.layouts.app')

@section('title', __('room::labels.room.management') . ' | ' . __('room::labels.room.edit'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('room::labels.room.management') <small class="text-muted">@lang('room::labels.room.edit')</small></h3>
        
    </div>
    <div class="block-content">
        {{ html()->modelForm($room, 'PATCH', route('admin.room.update', $room))->class('form-horizontal')->open() }}
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
                                ->required() }}
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
                                    <option value="{{ $item->id }}" {{ ( $item->id == (old('room_group_id') ? old('room_group_id') : $room->room_group_id)) ? 'selected' : '' }}> {{ $item->name }} </option>
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
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div>
            </div>
        {{ html()->closeModelForm() }}
    </div>
</div>
@endsection
