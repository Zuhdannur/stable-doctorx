@extends('backend.layouts.app')

@section('title', __('patient::labels.appointment.management') . ' | ' . __('patient::labels.appointment.edit'))

@section('content')

<div class="col-lg-6 offset-lg-3">
    <div class="block block-rounded block-fx-shadow" id="my-block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Edit Data Konsultasi</h3>
        </div>
        <div class="block-content">
            {{ html()->modelForm($appointment, 'PATCH', route('admin.patient.appointment.update', $appointment))->class('form-horizontal js-validation-bootstrap')->open() }}
                <input type="hidden" name="pid" id="pid" value="{{ $patient->patient_unique_id }}">

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Pasien : </label>
                    <span class='text-black text-primary font-w600'>{{$patient->patient_name}}&nbsp;({{ $patient->patient_unique_id }})</span></label>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Tanggal</label>
                        <input type="text" class="form-control datepicker" id="appointment_date" name="appointment_date" value="{{ old('appointment_date') ? old('appointment_date') : $appointment->date->format('d/m/Y') }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Jam</label>
                        <input type="text" class="js-masked-time form-control" id="appointment_time" name="appointment_time" value="{{ old('appointment_time') ? old('appointment_time') : $appointment->date->format('H:i:s')}}">
                    </div>
                    <div class="form-group col-md-12">
                        <label>Dokter</label>
                        <select class="form-control" id="staff_id" name="staff_id" style="width: 100%" data-placeholder="Pilih">
                            <option></option>
                            @foreach ($staff as $person)
                            <option value="{{ $person->id }}" {{ ( $person->id == old('staff_id')) || $person->id  == $appointment->staff_id ? 'selected' : '' }}> {{ $person->user->full_name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Ruangan</label>
                        <select class="form-control" id="room_id" name="room_id" style="width: 100%" data-placeholder="Pilih">
                            <option></option>
                            @foreach ($room as $item)
                            <option value="{{ $item->id }}" {{ ( $item->id == old('room_id')) || $item->id == $appointment->room_id ? 'selected' : '' }}> {{ $item->name }} - {{ $item->group->floor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Catatan</label>
                        <input type="text" class="form-control" id="notes" name="notes" value="{{ old('notes') ? old('notes') : $appointment->notes }}">
                    </div>
                    <div class="form-group col-md-12">
                        <label>Flag Info</label>
                        <input type='text' class="form-control" id="patient_flag_id" name="patient_flag_id"/>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col">
                        {{ form_cancel(route('admin.patient.appointment.index'), __('buttons.general.cancel')) }}
                    </div>
    
                    <div class="col text-right">
                        {{ form_submit(__('buttons.general.crud.update')) }}
                    </div>
                </div>
            {{ html()->closeModelForm() }}
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ URL::asset('js/plugins/spectrum/spectrum.css') }}">
<script src="{{ URL::asset('js/plugins/spectrum/spectrum.js') }}"></script>

<script type="text/javascript">
jQuery(function(){ 
    Codebase.helpers(['masked-inputs']); 
});

$('.datepicker').datepicker({
    todayHighlight: true,
    format: "{{ setting()->get('date_format_js') }}",
    weekStart: 1,
    language: "{!! str_replace('_', '-', app()->getLocale()) !!}",
    daysOfWeekHighlighted: "0,6",
    autoclose: true
});

$('#staff_id, #room_id').select2({
    placeholder: "Pilih",
    allowClear: true
});

$(document).on("focusout", ".datepicker", function() {
    $(this).prop('readonly', false);
});

$(document).on("focusin", ".datepicker", function() {
    $(this).prop('readonly', true);
});

var BeFormValidation = function() {
    var initValidationBootstrap = function(){
        jQuery('.js-validation-bootstrap').validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e) {
                jQuery(e).parents('.form-group').append(error);
            },
            highlight: function(e) {
                jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            success: function(e) {
                jQuery(e).closest('.form-group').removeClass('is-invalid');
                jQuery(e).remove();
            },
            rules: {
                'pid': {
                    required: true
                },
                'staff_id': {
                    required: true
                },
                /*'notes': {
                    required: true
                },*/
                'patient_flag_id': {
                    required: true
                },
                'appointment_date': {
                    required: true,
                    dateITA: true
                },
                'appointment_time': {
                    required: true,
                    time: true
                },
            },
        });
    };

    return {
        init: function () {
            // Init Bootstrap Forms Validation
            initValidationBootstrap();

            // Init Validation on Select2 change
            jQuery('.js-select2').on('change', function(){
                jQuery(this).valid();
            });
        }
    };
}();

// Initialize when page loads
jQuery(function(){ BeFormValidation.init(); });

$("#patient_flag_id").spectrum({
    showPaletteOnly: true,
    showPalette:true,
    hideAfterPaletteSelect: true,
    preferredFormat: "name",
    color: 'green',
    palette: [
        {!! str_replace('"', "'", json_encode(array_map('strtolower', array_column($flags->toArray(), 'name')), JSON_HEX_QUOT)) !!}
    ]
});

jQuery(function(){ 
var url = '{{ route("patient.getflag", ["id" => $patient->patient_unique_id]) }}';
    $.ajax({
        url: url,
        beforeSend: function(xhr) {
            Codebase.blocks('#my-block', 'state_loading');
        },
        error: function() {
            alert('An error has occurred');
            Codebase.blocks('#my-block', 'state_normal');
        },
        success: function(result) {
            if(result.status){
                $('#patient_flag_id').val(result.data.patient_flag_id);
                $("#patient_flag_id").spectrum("set", result.data.name.toLowerCase());
            }else{
                alert('data empty!');
            }
            
            Codebase.blocks('#my-block', 'state_normal');
        },
        type: 'GET'
    });
})

</script>
@endsection
