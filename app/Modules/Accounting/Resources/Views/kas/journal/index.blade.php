@extends('backend.layouts.app')

@section('title', app_name().' | '.__('accounting::menus.journal.title'))

@section('content')
    <div class="block" id="my-block">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('accounting::labels.journal.show')</h3>
            <div class="block-options">
                <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                    {{-- <a href="{{ route('admin.accounting.account.journal') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="Buat Jurnal Umum">
                        <i class="fa fa-random"></i> Buat Jurnal Umum
                    </a> --}}
                    <a href="{{ url()->previous() }}" class="btn-block-option">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="block-content block-content-full">
            <table id="table" class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>@lang('accounting::labels.biaya.table.date')</th>
                        <th>@lang('accounting::labels.journal.table.transaction_code')</th>
                        <th>@lang('accounting::labels.journal.table.debit')</th>
                        <th>@lang('accounting::labels.journal.table.credit')</th>
                        <th>@lang('accounting::labels.journal.table.balance')</th>
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
                ajax: '{!! route('admin.accounting.cash.journal', $account) !!}',
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
                        data: 'transaction.trx_date',
                        name: 'transaction.trx_date',
                    },
                    {
                        data: 'transaction.transaction_code',
                        name: 'transaction.transaction_code',
                    },
                    {
                        data: 'debit',
                    },
                    {
                        data: 'credit',
                    },
                    {
                        data: 'balance',
                        name: 'balance',
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