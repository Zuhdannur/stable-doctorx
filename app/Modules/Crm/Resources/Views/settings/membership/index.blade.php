@extends('backend.layouts.app')

@section('title', app_name(). ' | ' .__('crm::menus.settings.membership'))

@section('content')
    <div class="block" id="my-block">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('crm::labels.settings.membership.main')</h3>
            <div class="block-options">
                <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                    <a href="{{ route('admin.crm.settings.membership.create') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
                        <i class="fa fa-random"></i> @lang('labels.general.create_new')
                    </a>
                </div>
            </div>
        </div>
        
        <div class="block-content block-content-full">
            <table id="table" class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th>@lang('crm::labels.settings.membership.table.name')</th>
                        <th class="text-center" width="200px" >@lang('crm::labels.settings.membership.table.point')</th>
                        <th class="text-center">@lang('crm::labels.settings.membership.table.min_trx')</th>
                        <th class="text-center" style="width: 100px;">@lang('labels.general.actions')</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <script>
        jQuery(function(){
            var dt = $('#table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ordering: false,
                pageLength: 10,
                ajax: '{!! route('admin.crm.settings.membership') !!}',
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
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'point',
                        name: 'point',
                        class : 'text-center',
                    },
                    {
                        data: 'min_trx',
                        name: 'min_trx',
                        class: 'text-right',
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
        })
    </script>
@endsection