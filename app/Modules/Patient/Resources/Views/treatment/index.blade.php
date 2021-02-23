@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('patient::labels.patient.management'))

@section('content')
<div class="block" id="my-block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('patient::labels.patient.management')</h3>
        <div class="block-options">
            <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                <a href="{{ route('admin.patient.treatment.create', [0, 0]) }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
                    <i class="fa fa-random"></i> @lang('labels.general.create_new')
                </a>
            </div>
        </div>
    </div>
    <div class="block-content block-content-full">
        <div class="row">
                <div class="col-lg-4 offset-lg-4">
                    <div class="form-group row">
                        <div class="col-12">
                            <label for="startdate">Tanggal</label>
                            <div class="input-daterange input-group" data-date-format="{{ setting()->get('date_format_js') }}" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                <input type="text" class="form-control" id="start_date" name="start_date" placeholder="Dari" data-week-start="1" data-autoclose="true" data-today-highlight="true" autocomplete="off" value="{{ isset($input['start_date']) ? $input['start_date'] : null }}" readonly>
                                <div class="input-group-prepend input-group-append">
                                    <span class="input-group-text font-w600">s/d</span>
                                </div>
                                <input type="text" class="form-control" id="end_date" name="end_date" placeholder="Hingga" data-week-start="1" data-autoclose="true" data-today-highlight="true" autocomplete="off" value="{{ isset($input['end_date']) ? $input['end_date'] : null }}" readonly>&nbsp;
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
                    <th>#</th>
                    <th>@lang('patient::labels.treatment.table.app_no')</th>
                    <th>@lang('patient::labels.treatment.table.unique_id')</th>
                    <th>@lang('patient::labels.treatment.table.name')</th>
                    <th>@lang('patient::labels.treatment.table.date')</th>
                    <th>@lang('patient::labels.treatment.table.pic')</th>
                    <th>@lang('patient::labels.treatment.table.status')</th>
                    <th>@lang('patient::labels.treatment.table.notes')</th>
                    <th class="text-center" style="width: 100px;"></th>
                </tr>
            </thead>
           
        </table>
    </div>
</div>

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

        var startdate, enddate;

        var dt = $('#table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ordering: false,
            pageLength: 25,
            ajax: {
                url: '{!! route('admin.patient.treatment.index') !!}',
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
                    data: 'DT_RowIndex',
                    name: 'id',
                    width: "5%",
                    orderable: false, 
                    searchable: false
                },
                {
                    data: 'treatment_no',
                    name: 'treatment_no',
                },
                {
                    data: 'patient_unique_id',
                    name: 'patient.patient_unique_id',
                },
                {
                    data: 'patient_name',
                    name: 'patient.patient_name',
                    className: 'font-w600'
                },
                {
                    data: 'date',
                    name: 'date',
                    className: 'text-right'
                },
                {
                    data: 'staff_name',
                    name: 'staff_name',
                },
                {
                    data: 'status',
                    name: 'status',
                },
                {
                    data: 'notes',
                    name: 'notes',
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
                Codebase.blocks('#my-block', 'state_loading');
            } else {
                Codebase.blocks('#my-block', 'state_normal');
            }
        }).on('draw.dt', function () {
            $('[data-toggle="tooltip"]').tooltip();
            addDeleteForms();
        } );

        getData = function(){
            startdate = $('#start_date').val();
            enddate = $('#end_date').val();

            dt.ajax.reload();
        }

    });
</script>
@endsection