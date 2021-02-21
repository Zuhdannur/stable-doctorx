@extends('backend.layouts.app')

@section('title', app_name(). ' | '. __('accounting::menus.settings.tax'))

@section('content')
    <div class="block" id="my-block">
        {{-- content header --}}
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('accounting::labels.settings.tax.main')</h3>
            <div class="block-options">
                <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                    <a href="{{ route('admin.accounting.settings.tax.create') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
                        <i class="fa fa-random"></i> @lang('labels.general.create_new')
                    </a>
                </div>
            </div>
        </div>

        {{-- content body --}}
        <div class="block-content block-content-full">
            <table id="table" class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>@lang('accounting::labels.settings.table_tax.name')</th>
                        <th>@lang('accounting::labels.settings.table_tax.percentage') (%)</th>
                        <th>@lang('accounting::labels.settings.table_tax.purchasing')</th>
                        <th>@lang('accounting::labels.settings.table_tax.selling')</th>
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
            pageLength: 20,
            ajax: '{!! route('admin.accounting.settings.tax') !!}',
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
                    data: 'tax_name',
                    name: 'tax_name',
                },
                {
                    data: 'percentage',
                    name: 'percentage',
                },
                {
                    data: 'acc_sales.account_name',
                    name: 'acc_sales.account_name',
                },
                {
                    data: 'acc_purchase.account_name',
                    name: 'acc_purchase.account_name',
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
        });
    })
    </script>
@endsection