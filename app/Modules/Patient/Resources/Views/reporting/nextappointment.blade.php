@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('patient::labels.patient.management'))

@section('content')
<div class="block" id="my-block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('patient::labels.patient.management')</h3>
        <div class="block-options">
            <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                <a href="{{ route('admin.patient.appointment.create', [0, 0]) }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
                    <i class="fa fa-random"></i> @lang('labels.general.create_new')
                </a>
            </div>
        </div>
    </div>
    <div class="block-content block-content-full">
        <table id="table" class="table table-sm table-hover table-striped table-vcenter" width="100%">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>@lang('patient::labels.appointment.table.unique_id')</th>
                    <th>@lang('patient::labels.appointment.table.name')</th>
                    <th>@lang('patient::labels.appointment.table.date')</th>
                    <th>Sisa Waktu</th>
                    <th>Catatan</th>
                    <th>@lang('patient::labels.appointment.table.pic')</th>
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
                </tr>
            </tfoot>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
    jQuery(function(){ 
        Codebase.layout('sidebar_mini_on');

        var dt = $('#table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            pageLength: 25,
            ajax: '{!! route('admin.calendar.next-schedule') !!}',
            language: {
                url: "{!! URL::asset('js/plugins/datatables/i18n/'.str_replace('_', '-', app()->getLocale()).'.json') !!}"
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    width: "5%",
                    orderable: false, 
                    searchable: false
                },
                {
                    data: 'patient_unique_id',
                    name: 'patient.patient_unique_id'
                },
                {
                    data: 'patient_name',
                    name: 'patient.patient_name',
                    className: 'font-w600'
                },
                {
                    data: 'date',
                    className: 'text-right'
                },
                {
                    data: 'date_remainings',
                },
                {
                    data: 'next_appointment_notes',
                },
                {
                    data: 'staff_name',
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

    });
</script>
@endsection