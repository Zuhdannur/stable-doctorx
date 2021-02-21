@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('patient::labels.patient.management'))

@section('content')
<div class="block" id="my-block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('patient::labels.patient.management')</h3>
        <div class="block-options">
            <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                <a href="{{ route('admin.patient.create') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
                    <i class="fa fa-random"></i> @lang('labels.general.create_new')
                </a>
                <button class="btn btn-sm btn-alt-info d-none" title="@lang('labels.general.create_new')" data-toggle="modal" data-target="#modalForm" data-backdrop="static" data-keyboard="false" data-key="logo_square" data-title="@lang('labels.general.create_new')">
                    <i class="fa fa-random"></i> @lang('labels.general.create_new')
                </button>
            </div>
        </div>
    </div>
    <div class="block-content block-content-full">
        <table id="table" class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination" width="100%">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>@lang('patient::labels.patient.table.unique_id')</th>
                    <th>@lang('patient::labels.patient.table.name')</th>
                    <th>@lang('patient::labels.patient.table.phone_number')</th>
                    <th>@lang('patient::labels.patient.table.gender')</th>
                    <th>@lang('patient::labels.patient.table.dob')</th>
                    <th>@lang('patient::labels.patient.table.age')</th>
                    <th class="text-center" style="width: 100px;">@lang('labels.general.actions')</th>
                </tr>
            </thead>
           
        </table>
    </div>
</div>

<!-- Statistics -->
    @widget('\App\Modules\Patient\Widgets\Statistics') 
<!-- END Patients -->
<script>
    jQuery(function(){ 
        var dt = $('#table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ordering: false,
            pageLength: 10,
            ajax: '{!! route('admin.patient.index') !!}',
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
                    data: 'patient_unique_id',
                    name: 'patient_unique_id',
                },
                {
                    data: 'patient_name',
                    name: 'patient_name',
                    className: 'font-w600'
                },
                {
                    data: 'phone_number',
                    name: 'phone_number',
                },
                {
                    data: 'gender',
                    name: 'gender',
                },
                {
                    data: 'dob',
                    name: 'dob',
                },
                {
                    data: 'age',
                    name: 'age',
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
    });
</script>
@endsection