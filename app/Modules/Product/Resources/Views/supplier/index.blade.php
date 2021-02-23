@extends('backend.layouts.app')

@section('title', app_name().' | '.__('product::menus.supplier.title'))

@section('content')
    <style type="text/css">
        tfoot {
            display: table-header-group;
        }
    </style>
    <div class="block" id="my-block">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('product::labels.supplier.management')</h3>
            <div class="block-options">
                <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                    <a href="{{ route('admin.product.supplier.create') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
                        <i class="fa fa-random"></i> @lang('labels.general.create_new')
                    </a> 
                    {{-- <a href="{{ route('admin.product.import') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
                        <i class="fa fa-random"></i> Import
                    </a> --}}
                </div>
            </div>
        </div>

        {{-- content body --}}
        <div class="block-content block-content-full">
            <table id="table" class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>@lang('product::labels.supplier.table.code')</th>
                        <th>@lang('product::labels.supplier.table.name')</th>
                        <th>@lang('product::labels.supplier.table.phone_number')</th>
                        <th>@lang('product::labels.supplier.table.company')</th>
                        <th>@lang('product::labels.supplier.table.company_phone_number')</th>
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

    <script>
        $(function () {
            var dt = $('#table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "ordering": true,
                pageLength: 25,
                autoWidth: true,
                ajax: '{!! route('admin.product.supplier') !!}',
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
                        data: 'supplier_code',
                        name: 'supplier_code',
                        className: 'font-w600'
                    },
                    {
                        data: 'supplier_name',
                        name: 'supplier_name',
                        className: 'font-w600'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number',
                    },
                    {
                        data: 'company_name',
                        name: 'company_name',
                    },
                    {
                        data: 'company_phone_number',
                        name: 'company_phone_number',
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
                initComplete: function () {
                    this.api().columns(2).every( function () {
                        var column = this;
                        var input = $('<input type="text" class="form-control form-control-sm" placeholder="Cari.." />')
                            .appendTo( $(column.footer()).empty() )
                            .on( 'keyup change', function () {
                                if ( column.search() !== this.value ) {
                                    column
                                        .search( this.value )
                                        .draw();
                                }
                            } );
                    } );
                }
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