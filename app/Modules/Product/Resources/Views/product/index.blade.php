@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('product::labels.product.management'))

@section('content')
<style type="text/css">
tfoot {
    display: table-header-group;
}
</style>
<div class="block" id="my-block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('product::labels.product.management')</h3>
        <div class="block-options">
            <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                <a href="{{ route('admin.product.create') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
                    <i class="fa fa-random"></i> @lang('labels.general.create_new')
                </a>
                &nbsp;
                &nbsp;
                <a href="{{ route('admin.product.import') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
                    <i class="fa fa-random"></i> Import
                </a>
            </div>
        </div>
    </div>
    <div class="block-content block-content-full">
        <div class="d-flex">
            <div class="col-6">
                <label for="startdate">Kategori</label>
                <select class="form-control" name="category" id="category" >
                    <option value="semua">Semua Kategori</option>
                    @foreach(\App\Modules\Product\Models\ProductCategory::where('id_klinik',auth()->user()->id_klinik)->get() as $row)
                        <option value="{{ $row->id }}">{{ "$row->name" }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <br>
        <table id="table" class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination" width="100%">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>@lang('product::labels.product.table.category')</th>
                    <th>@lang('product::labels.product.table.code')</th>
                    <th>@lang('product::labels.product.table.name')</th>
                    <th>@lang('product::labels.product.table.purchase_price')</th>
                    <th>@lang('product::labels.product.table.price')</th>
                    <th>@lang('product::labels.product.table.stock')</th>
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
            ordering: false,
            pageLength: 25,
            autoWidth: true,
            ajax: {
                url : '{!! route('admin.product.index') !!}',
                data: function (e) {
                    e.category = $("#category").val()
                }
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
                    searchable: true
                },
                {
                    data: 'category',
                    name: 'category',
                },
                {
                    data: 'code',
                    name: 'code',
                    className: 'font-w600'
                },
                {
                    data: 'name',
                    name: 'name',
                    className: 'font-w600',
                    searchable: true
                },
                {
                    data: 'purchase_price',
                    name: 'purchase_price',
                    searchable: true
                },
                {
                    data: 'price',
                    name: 'price',
                    searchable: true
                },
                {
                    data: 'quantity',
                    name: 'quantity',
                    searchable: true
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
                // this.api().columns(1).every( function () {
                //     var column = this;
                //     var select = $('<select class="form-control"><option value="">Semua</option></select>')
                //         .appendTo( $(column.footer()).empty() )
                //         .on( 'change', function () {
                //             var val = $.fn.dataTable.util.escapeRegex(
                //                 $(this).val()
                //             );
                //
                //             column
                //                 .search( val ? '^'+val+'$' : '', true, false )
                //                 .draw();
                //         } );
                //
                //     column.data().unique().sort().each( function ( d, j ) {
                //         select.append( '<option value="'+d+'">'+d+'</option>' )
                //     } );
                // } );

                // this.api().columns(3).every( function () {
                //     var column = this;
                //     var input = $('<input type="text" class="form-control" placeholder="Cari.." />')
                //         .appendTo( $(column.footer()).empty() )
                //         .on( 'keyup change', function () {
                //             if ( column.search() !== this.value ) {
                //                 column
                //                     .search( this.value )
                //                     .draw();
                //             }
                //         } );
                // } );
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

        $("#category").select2()

        $("#category").on('change',function () {
            dt.ajax.reload()
        })
    });
</script>
@endsection
