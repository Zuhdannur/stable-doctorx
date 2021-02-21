@extends('backend.layouts.app')

@section('title', app_name().' | '.__('crm::menus.point'))

@section('content')
<div class="block" id="my-block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('crm::labels.point.radeem.main')</h3>
        <div class="block-options">
            <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                <a href="{{ route('admin.crm.point.create') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
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
                    <th>@lang('crm::labels.point.radeem.table.code')</th>
                    <th>@lang('crm::labels.point.radeem.table.point')</th>
                    <th>@lang('crm::labels.point.radeem.table.nominal_gift')</th>
                    <th class="text-center" style="width: 100px;">@lang('labels.general.actions')</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script>
    $(function () {
        var dt = $('#table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            "ordering": false,
            pageLength: 25,
            autoWidth: true,
            ajax: '{!! route('admin.crm.point.index') !!}',
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
                    data: 'code',
                    name: 'code',
                },
                {
                    data: 'point',
                    name: 'point',
                    className: 'font-w600'
                },
                {
                    data: 'nominal_gift',
                    name: 'nominal_gift',
                },
                {
                    data: 'action',
                    name: 'action',
                    width: "8%",
                    className: 'text-right',
                    orderable: false, 
                    searchable: false
                }
            ],
        }).on('processing.dt', function (e, settings, processing) {
            if (processing) {
                Codebase.blocks('#my-block', 'state_loading');
            } else {
                Codebase.blocks('#my-block', 'state_normal');
            }
        }).on('draw.dt', function () {
            $('[data-toggle="tooltip"]').tooltip();
            addDeleteForms();
        });
    });
</script>
@endsection