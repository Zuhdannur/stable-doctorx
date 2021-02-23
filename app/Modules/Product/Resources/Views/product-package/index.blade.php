@extends('backend.layouts.app')

@section('title', app_name() . ' | Paket Obat')

@section('content')
<style type="text/css">
tfoot {
    display: table-header-group;
}
.toolbar {
    float:left;
}
</style>
<div class="block" id="my-block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Paket Obat</h3>
        <div class="block-options">
            <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                <a href="{{ route('admin.product.productpackage.create') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
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
                    <th>Nama Paket</th>
                    <th>Deskripsi</th>
                    <th>Diskon</th>
                    <th>Jumlah Item</th>
                    <th class="text-center" style="width: 100px;">@lang('labels.general.actions')</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<script id="details-template" type="text/x-handlebars-template">
    <div class="text-center"><span class="badge badge-secondary">List Produk Paket @{{ name }}</span></div>
    <button type="button" data-id="@{{id}}" data-title="Tambah Data Produk @{{ name }}" class="btn btn-sm btn-secondary d-none mb-10 addProduct">Tambah Produk</button>
    <table class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination" id="product-@{{id}}">
        <thead>
            <tr>
                <th>No.</th>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</script>
<script>
    $(function () {
        var template = Handlebars.compile($("#details-template").html());

        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ordering: false,
            pageLength: 25,
            autoWidth: true,
            ajax: '{!! route('admin.product.productpackage.index') !!}',
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
                    className: 'font-w600'
                },
                {
                    data: 'description',
                    name: 'description',
                },
                {
                    data: 'discount',
                    name: 'discount',
                },
                {
                    data: 'description',
                    name: 'description',
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
        } );

        // Add event listener for opening and closing details
        $('#table tbody').on('click', '.details-control', function() {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            var tableId = 'product-' + row.data().id;

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(template(row.data())).show();
                initTable(tableId, row.data());
                tr.addClass('shown');
                tr.next().find('td').addClass('no-padding bg-gray');
            }
        });

        function initTable(tableId, data) {
            $('#' + tableId).DataTable({
                processing: true,
                serverSide: true,
                ajax: data.details_url,
                // dom: '<"toolbar">frtip',
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
                        data: 'product',
                        name: 'product'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'price',
                        name: 'price'
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
                /*initComplete: function(){
                    $("div.toolbar").html('<button type="button" data-id="@{{id}}" class="btn btn-sm btn-warning mb-10">Tambah Produk</button>');           
                }*/  
            })
        }

    });
</script>
@endsection