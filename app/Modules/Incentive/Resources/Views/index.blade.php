@extends('backend.layouts.app')

@section('title', app_name() . ' | Insentif')

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
        <h3 class="block-title">Insentif</h3>
        <div class="block-options">
            <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                <a href="{{ route('admin.incentive.create') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
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
                    <th>Grup Insentif</th>
                    <th>Keterangan</th>
                    <th>Komisi Obat/Produk</th>
                    <th class="text-center" style="width: 100px;"></th>
                </tr>
            </thead>
           
        </table>
    </div>
</div>
<script id="details-template" type="text/x-handlebars-template">
    <div class="text-center"><span class="badge badge-secondary">Item @{{ name }}</span></div>
    <table class="table table-sm table-hover table-bordered table-striped table-vcenter js-dataTable-full-pagination" id="product-@{{id}}">
        <thead>
            <tr>
                <th>No.</th>
                <th>ITEM</th>
                <th>KOMISI/PERSENTASE</th>
                <th>KOMISI/PERSENTASE</th>
                <th>NILAI</th>
                <th>HARGA</th>
                <th>NILAI KOMISI</th>
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
            order: [[ 1, 'asc' ]],
            pageLength: 10,
            ajax: '{!! route('admin.incentive.index') !!}',
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
                    name: 'description'
                },
                {
                    data: 'product_incentive_percent',
                    name: 'product_incentive_percent'
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
            var groupColumn = 2;
            $('#' + tableId).DataTable({
                processing: true,
                serverSide: true,
                ajax: data.details_url,
                order: [[ groupColumn, 'asc' ]],
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
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'value_type',
                        name: 'value_type'
                    },
                    {
                        data: 'value',
                        className: 'text-right',
                        name: 'value'
                    },
                    {
                        data: 'price',
                        className: 'text-right',
                        name: 'price'
                    },
                    {
                        data: 'value_incentive',
                        className: 'text-right',
                        name: 'value_incentive'
                    }
                ],
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;
         
                    api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                '<tr class="group" style="background-color: #6c757d; color: white;"><td colspan="7">'+group+'</td></tr>'
                            );
         
                            last = group;
                        }
                    } );
                }
            });

            // Order by the grouping
            $('#' + tableId).on( 'click', 'tr.group', function () {
                var currentOrder = table.order()[0];
                if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
                    table.order( [ groupColumn, 'desc' ] ).draw();
                }
                else {
                    table.order( [ groupColumn, 'asc' ] ).draw();
                }
            } );
        }
    });
</script>
@endsection