@extends('backend.layouts.app')

@section('title' , app_name().' | '.__('crm::menus.membership'))

@section('content')
<div class="block" id="my-block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('crm::labels.membership.main')</h3>
        <div class="block-options">
            <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                <a href="{{ route('admin.crm.membership.create') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
                    <i class="fa fa-random"></i> @lang('labels.general.create_new')
                </a>
            </div>
        </div>
    </div>

    <div class="block-content block-content-full">
        <table id="table" class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination" width="100%">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>@lang('crm::labels.membership.table.unique_id')</th>
                    <th>@lang('patient::labels.patient.table.name')</th>
                    <th>@lang('patient::labels.patient.table.phone_number')</th>
                    <th>@lang('patient::labels.patient.table.gender')</th>
                    <th>@lang('crm::labels.membership.table.membership')</th>
                    <th>@lang('crm::labels.membership.table.point')</th>
                    <th>@lang('billing::labels.billing.table.total')</th>
                    <th class="text-center" style="width: 100px;">@lang('labels.general.actions')</th>
                </tr>
            </thead>

        </table>
    </div>


</div>
@widget('\App\Modules\Crm\Widgets\MembershipWidgets')

<script>
    jQuery(function(){
        var dt = $('#table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ordering: false,
            pageLength: 10,
            ajax: '{!! route('admin.crm.membership.index') !!}',
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
                    data: 'patient.patient_unique_id',
                    name: 'patient.patient_unique_id',
                },
                {
                    data: 'patient.patient_name',
                    name: 'patient.patient_name',
                },
                {
                    data: 'patient.phone_number',
                    name: 'patient.phone_number',
                    class: 'text-right',
                },
                {
                    data: 'patient.gender',
                    name: 'patient.gender',
                    class: 'text-right',
                },
                {
                    data: 'ms_membership.name',
                    name: 'ms_membership.name',
                    class: 'text-center',
                },
                {
                    data: 'total_point',
                    name: 'total_point',
                    class: 'text-right',
                },
                {
                    data: 'total_amount',
                    name: 'total_amount',
                    class: 'text-right',
                },
                {
                    data: 'action',
                    name: 'action',
                    width: "15%",
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
    })
</script>
@endsection
