@extends('backend.layouts.app')

@section('title', __('patient::labels.appointment.management') . ' | ' . __('patient::labels.appointment.create'))

@section('content')
<link rel="stylesheet" href="{{ URL::asset('js/plugins/spectrum/spectrum.css') }}">
<script src="{{ URL::asset('js/plugins/spectrum/spectrum.js') }}"></script>
<div class="col-lg-8 offset-lg-2">
    <div class="block block-rounded block-fx-shadow" id="my-block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Input Data Treatment</h3>
        </div>
        <div class="block-content">
            <form class="formTreatment form-vertical" method="post" action="{{ route('admin.patient.treatment.store') }}" id="formGroup" autocomplete="off">
                @csrf
                @method('POST')
                <input type="hidden" name="qId" id="qId" value="{{ $qid }}">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Pasien</label>
                        <select class="form-control required" id="pid" name="pid" data-parsley-required="true" data-placeholder="Pilih" style="width: 100%">
                            <option></option>
                            @foreach ($patientlist as $patients)
                            <option value="{{ $patients->patient_unique_id }}"> {{ $patients->patient_unique_id }} - {{ $patients->patient_name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Tanggal</label>
                        <input type="text" class="form-control datepicker" id="appointment_date" name="appointment_date">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Jam Mulai</label>
                        <input type="text" class="js-masked-time form-control" id="appointment_time" name="appointment_time">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Jam Selesai</label>
                        <input type="text" class="js-masked-time form-control" id="end_time" name="end_time">
                    </div>
                    <div class="form-group col-md-12">
                        <label>Dokter</label>
                        <select class="form-control" id="dokter_staff_id" name="dokter_staff_id" style="width: 100%" data-placeholder="Pilih" required>
                            <option>Pilih</option>
                            @foreach ($dokter as $person)
                            <option value="{{ $person->id }}" {{ ( $person->id == old('staff_id')) ? 'selected' : '' }}> {{ $person->user->full_name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Terapis</label>
                        <select class="form-control" id="staff_terapis_id" name="staff_terapis_id" style="width: 100%" data-placeholder="Pilih">
                            <option>Pilih</option>
                            @foreach ($terapis as $person)
                                <option value="{{ $person->id }}" {{ ( $person->id == old('staff_terapis_id')) ? 'selected' : '' }}> {{ $person->user->full_name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Ruangan</label>
                        <select class="form-control" id="room_id" name="room_id" style="width: 100%" data-placeholder="Pilih">
                            <option></option>
                            @foreach ($room as $item)
                            <option value="{{ $item->id }}" {{ ( $item->id == old('room_id')) ? 'selected' : '' }}> {{ $item->name }} - {{ $item->group->floor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Catatan</label>
                        <input type="text" class="form-control" id="notes" name="notes">
                    </div>
                    <div class="form-group col-md-12">
                        <label>Flag Info</label>
                        <input type='text' class="form-control" id="patient_flag_id" name="patient_flag_id"/>
                    </div>
                    @if(!$prescription)
                    <div class="form-group col-md-12">
                        <label>Service</label>
                        <span id="error"></span>
                        <table class="table table-bordered table-hover table-striped table-vcenter" id="tab_logic3">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;"> </th>
                                    <th style="width: 50px;"> # </th>
                                    <th> Tindakan </th>
                                    <th> Flag </th>
                                    <th> Keterangan </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><button type="button" class="btn btn-sm btn-circle btn-outline-success add3" title="Tambah Tindakan">
                                        <i class="fa fa-plus"></i>
                                    </button></td>
                                    <td class="nomor3">
                                        1
                                    </td>
                                    <td>
                                        <select class="form-control service" id="service1" name="service[1]" data-placeholder="Pilih" style="width: 100%">
                                            {!! $service !!}
                                        </select>
                                    </td>
                                    <td class="flags">
                                        <label for="" class=".flag">Dokter</label>
                                    </td>
                                    <td width="300px">
                                        <input type="text" name='servicenotes[1]' class="form-control servicenotes" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
                @if($prescription)
                    @include('patient::prescription.partials.hasil-diagnosa')
                @endif
                <!-- Form Submission -->
                <div class="row items-push">
                    <div class="col-lg-7 offset-lg-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-alt-success">
                                <i class="fa fa-plus mr-5"></i> Submit
                            </button>
                        </div>
                    </div>
                </div>
                <!-- END Form Submission -->
            </form>
        </div>
    </div>


</div>

<script type="text/javascript">
jQuery(function(){
    Codebase.helpers(['masked-inputs']);

    $('#pid').select2({
        placeholder: "Pilih",
        minimumInputLength: 3,
        allowClear: true
    }).on("select2:select", function (e) {
        var selected = e.params.data;

        if (typeof selected !== "undefined") {
            var url = '{{ route("patient.getflag", ":id") }}';
            url = url.replace(':id', selected.id);

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
        }
    });
});

$('.datepicker').datepicker({
    todayHighlight: true,
    format: "{{ setting()->get('date_format_js') }}",
    weekStart: 1,
    language: "{!! str_replace('_', '-', app()->getLocale()) !!}",
    daysOfWeekHighlighted: "0,6",
    autoclose: true
});

var lastDate = new Date();
lastDate.setDate(lastDate.getDate());//any date you want
$(".datepicker").datepicker('setDate', lastDate);
$('#appointment_time').val((lastDate.getHours()<10?'0':'') + lastDate.getHours() + ":" + (lastDate.getMinutes()<10?'0':'') + lastDate.getMinutes());
$('#end_time').val((lastDate.getHours()<10?'0':'') + lastDate.getHours() + ":" + (lastDate.getMinutes()<10?'0':'') + lastDate.getMinutes());

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

$('.dropify').dropify({
    messages: {
        'default': 'Drag and drop a file here or click',
        'replace': 'Drag and drop or click to replace',
        'remove': 'Hapus',
        'error': 'Ooops, something wrong happended.'
    },
    tpl: {
        wrap: '<div class="dropify-wrapper"></div>',
        loader: '<div class="dropify-loader"></div>',
        message: '<div class="dropify-message"><span class="file-icon" /> <p>@{{ default }}</p></div>',
        preview: '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message">@{{ replace }}</p></div></div></div>',
        filename: '<p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p>',
        clearButton: '<button type="button" class="dropify-clear btn-xs" style="margin-top: -9px;">@{{ remove }}</button>',
        errorLine: '<p class="dropify-error">@{ error }}</p>',
        errorsContainer: '<div class="dropify-errors-container"><ul></ul></div>'
    }
});


        var validator = jQuery('.formTreatment').validate({
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
                // 'notes': {
                //     required: true
                // },
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
                'end_time': {
                    required: true,
                    time: true
                },
            }
        });

$('#pid').val('{{ ($pid ? $pid : '') }}').focus();
setTimeout(function(){
    $('#pid').focusout();
}, 500);

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

$("#patient_flag_id").spectrum("set", '{{ $patientflag }}');


jQuery(function () {
    $('#service1').select2({
        placeholder: "Pilih"
    });

    var k = 1;
    function updateIds3() {
        $('#tab_logic3 tbody tr').each(function(k, element) {
            var html3 = $(this).html();
            if (html3 != '') {
                $(this).find('.nomor3').text((k+1));
                $(this).find('.service').attr('name', 'service['+(k+1)+']');
                $(this).find('.servicenotes').attr('name', 'servicenotes['+(k+1)+']');
            }
        });

        validator.resetForm();
    }
    updateIds3();

    $(document).ready(function() {
        $(document).on('click', '.add3', function() {
            var html3 = '';
            html3 += '<tr>';
            html3 += '<td><button type="button" name="remove3" class="btn btn-sm btn-circle btn-outline-danger remove3" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
            html3 += '<td class="nomor3">'+(k+1)+'</td>';
            html3 += '<td><select class="form-control service required" id="service'+(k+1)+'" name="service['+(k+1)+']" data-placeholder="Pilih" style="width: 100%">{!! $service !!}</select></td>';
            html3 += '<td class="flags"><label for="" class=".flag">Dokter</label></td>';
            html3 += '<td><input type="text" name="servicenotes['+(k+1)+']" class="form-control servicenotes" /></td>';

            html3 += '</tr>';

            $('#tab_logic3 > tbody').append(html3);

            $('#service'+(k+1)+'').select2({
                placeholder: "Pilih"
            });

            k++;
            updateIds3();
        });

        $(document).on('click', '.remove3', function() {
            $(this).closest('tr').remove();
            updateIds3();
        });

        $('body').on('change','.service', function () {
            var value = $(this).val()

            // var item = $("td.flags")[0];
            var parent = $(this).parent().parent();
            var label = parent.find('td.flags').find('label')

            $.ajax({
                url: '{{ url('api-master/data/getFlagService') }}',
                data: {
                  'id' : value
                },
                beforeSend: function(xhr) {
                    Codebase.blocks('#my-block', 'state_loading');
                },
                error: function() {
                    alert('An error has occurred');
                    Codebase.blocks('#my-block', 'state_normal');
                },
                success: function(result) {
                    label.empty();
                    label.append(result.data === 1 ? 'Dokter' : 'Terapis')

                    Codebase.blocks('#my-block', 'state_normal');
                },
                type: 'POST'
            });





            // console.log(item)
            // alert(label);
        })
    });

});
</script>
@endsection
