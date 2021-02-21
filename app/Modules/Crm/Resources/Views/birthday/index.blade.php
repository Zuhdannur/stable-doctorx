@extends('backend.layouts.app')

@section('title', app_name().' | '.__('crm::menus.birthday'))

@section('content')
<div class="block" id="my-block2">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('crm::menus.birthday')</h3>
        <div class="block-options">
            <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
            </div>
        </div>
    </div>
    <div class="block-content block-content-full">
    	<div class="row">
                <div class="col-lg-4 offset-lg-4">
                    <div class="form-group row">
                        <div class="col-12 text-center">
                            <label for="startdate">Tanggal</label>
                            <div class="input-daterange input-group" data-date-format="{{ setting()->get('date_format_js') }}" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                <input type="text" class="form-control" id="start_date" name="start_date" placeholder="Dari" data-week-start="1" data-autoclose="true" data-today-highlight="true" autocomplete="off" value="{{ isset($input['start_date']) ? $input['start_date'] : null }}">
                                <div class="input-group-prepend input-group-append">
                                    <span class="input-group-text font-w600">s/d</span>
                                </div>
                                <input type="text" class="form-control" id="end_date" name="end_date" placeholder="Hingga" data-week-start="1" data-autoclose="true" data-today-highlight="true" autocomplete="off" value="{{ isset($input['end_date']) ? $input['end_date'] : null }}">&nbsp;
                                <button type="button" class="btn btn-alt-success" onclick="getData();" id="showData">
	                                <i class="fa fa-plus mr-5"></i> Submit
	                            </button>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <table id="table" class="table table-sm table-hover table-striped table-vcenter" width="100%">
            <thead class="thead-light">
                <tr>
                    <th>@lang('patient::labels.appointment.table.unique_id')</th>
                    <th>@lang('patient::labels.appointment.table.name')</th>
                    <th>@lang('patient::labels.appointment.table.date')</th>
                    <th>No Telpon</th>
                    <th>No Whatsapp</th>
                    <th>Status</th>
                    <th>@lang('crm::labels.membership.table.membership')</th>
                    <th class="text-center" style="width: 100px;">@lang('labels.general.actions')</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
            <tbody></tbody>
        </table>
    </div>
</div>
<script src="{{ URL::asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script>

    jQuery(function(){ 
        var formatDate = "{{ setting()->get('date_format_js') }}";
        // Codebase.helpers(['datepicker']);
        $('.input-daterange').datepicker({
            todayHighlight: true,
            format: "{{ setting()->get('date_format_js') }}",
            weekStart: 1,
            language: "{!! str_replace('_', '-', app()->getLocale()) !!}",
            daysOfWeekHighlighted: "0,6",
            autoclose: true,
        });

        var startdate = moment(new Date()).format(formatDate.toUpperCase());
        var enddate = moment(new Date()).format(formatDate.toUpperCase());
        
        var dt = $('#table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            pageLength: 25,
            ajax: {
                url: '{!! route('admin.crm.birthday.index') !!}',
                data: function ( data ) {
                    data.startDate =  startdate;
                    data.endDate =  enddate;
                },
                type: "GET"
            },
            language: {
                url: "{!! URL::asset('js/plugins/datatables/i18n/'.str_replace('_', '-', app()->getLocale()).'.json') !!}"
            },
            columns: [
                {
                    data: 'patient.patient_unique_id',
                    // name: 'patient.patient_unique_id'
                },
                {
                    data: 'patient.patient_name',
                    name: 'patient.patient_name',
                    className: 'font-w600'
                },
                {
                    data: 'patient.dob',
                    // className: 'text-right'
                },
                {
                    data: 'patient.phone_number',
                },
                {
                    data: 'patient.wa_number',
                },
                {
                    data: 'patient.last_wa',
                },
                {
                    data: 'ms_membership.name',
                },
                {
                    data: 'action',
                    name: 'action',
                    width: "8%",
                    className: 'text-right',
                    orderable: false, 
                    searchable: false
                }
            ]
        }).on('processing.dt', function (e, settings, processing) {
            if (processing) {
                Codebase.blocks('#my-block2', 'state_loading');
            } else {
                Codebase.blocks('#my-block2', 'state_normal');
            }
        }).on('draw.dt', function () {
            $('[data-toggle="tooltip"]').tooltip();
            addDeleteForms();
        } );


        getData = function(){
            startdate = $('#start_date').val();
            enddate = $('#end_date').val();

            dt.ajax.reload();
        };
        
        sendWa = function(patient){
           Swal.fire({
                title: 'Kirim Ucapan Ultah Via Whatsapp ?',
                type: 'question',
                showCancelButton: true,
                showConfirmButton: true,
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('admin.crm.birthday.sendWa') }}",
                        data: {patient : patient},
                        beforeSend: function(xhr){
                            Codebase.blocks('#my-block2', 'state_loading');
                        },
                        error: function(x, status, error){
                            if(x.status === 422 ){
                                var errors = x.responseJSON;
                                var errorsHtml = '';

                                $.each(errors['errors'], function(index, value){
                                    errorsHtml += '<ul><li class="text-danger">' + value + '</li></ul>';
                                });

                                    $.alert({
                                        title: "Error " + x.status + ': ' + error,
                                        icon: 'fa fa-warning',
                                        type: 'red',
                                        content: errorsHtml,
                                        columnClass: 'col-md-5 col-md-offset-3',
                                        typeAnimated: true,
                                        draggable: false,
                                    });
                                }else{
                                    var errors = x.responseJSON;
                                    $.alert({
                                        title: x.status + ': ' + error,
                                        icon: 'fa fa-warning',
                                        type: 'red',
                                        content: errors.messages,
                                    });
                                }

                                Codebase.blocks('#my-block2', 'state_normal');
                            },
                        success: function(result){
                            if(result.status){
                                $.notify({
                                    message: result.message,
                                    type: 'success'
                                });

                                // setTimeout(function(){
                                //     window.location = '{{ route('admin.crm.membership.index') }}';
                                // }, 2000); 
                            }else{
                                $.alert({
                                    title: 'Error',
                                    icon: 'fa fa-warning',
                                    type: 'orange',
                                    content: result.message,
                                });
                            }
                            Codebase.blocks('#my-block2', 'state_normal');
                        }
                    });
                }
            })
        }
    });
</script>

@endsection