@extends('backend.layouts.app')

@section('title', app_name().' | '.__('accounting::menus.purchase.title'))

@section('content')

    @widget('App\Modules\Accounting\Widgets\PurchaseWidgets')

    <div class="clearfix"></div>
    @include('accounting::reports.partial.range-date')
    
    <div class="clearfix"></div>
    <div class="clearfix"></div>
    <div class="block" id="my-block-2">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('accounting::labels.purchase.create')</h3>
            <div class="block-options">
                <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                    <a href="{{ route('admin.accounting.purchase.create') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
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
                        <th>@lang('accounting::labels.purchase.table.date')</th>
                        <th>@lang('accounting::labels.purchase.table.code')</th>
                        <th>@lang('accounting::labels.purchase.table.receiver')</th>
                        <th>@lang('accounting::labels.purchase.table.status')</th>
                        <th>@lang('accounting::labels.purchase.table.due_date')</th>
                        <th>@lang('accounting::labels.purchase.table.bill')</th>
                        <th>@lang('accounting::labels.purchase.table.total')</th>
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
                pageLength: 25,
                ajax: {
                    url : '{!! route('admin.accounting.purchase') !!}',
                    data: function ( data ) {
                        data.date_1 =  "{{ $date['date_1'] }}";
                        data.date_2 =  "{{ $date['date_2'] }}";
                    },
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
                        data: 'trx_date',
                        name: 'financeTrx.trx_date',
                        searchable: false
                    },
                    {
                        data: 'finance_trx.transaction_code',
                        name: 'financeTrx.transaction_code',
                    },
                    {
                        data: 'finance_trx.person',
                        name: 'financeTrx.person',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'due_date',
                        name: 'due_date',
                    },
                    {
                        data: 'remain_payment',
                        name: 'remain_payment',
                    },
                    {
                        data: 'total',
                        name: 'total',
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
                    Codebase.blocks('#my-block-2', 'state_loading');
                } else {
                    Codebase.blocks('#my-block-2', 'state_normal');
                }
            }).on('draw.dt', function () {
                $('[data-toggle="tooltip"]').tooltip();
                addDeleteForms();
            });
        })
    </script>
@endsection