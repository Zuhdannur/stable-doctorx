@extends('base.app')

@section('title', app_name() . ' | Daftar Invoice')

@section('content')

    <div class="clearfix"></div>

    <div class="block font-size-sm" id="my-block">
        <div class="block-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row justify-content-center">
                        <div class="col-6 text-center">
                            <label for="startdate">Tanggal</label>
                            <div class="input-daterange input-group"
                                 data-date-format="{{ setting()->get('date_format_js') }}" data-week-start="1"
                                 data-autoclose="true" data-today-highlight="true">
                                <input type="text" class="form-control datepicker" id="date_1" name="date_1"
                                       placeholder="Dari" data-week-start="1" data-autoclose="true"
                                       data-today-highlight="true" autocomplete="off">
                                <div class="input-group-prepend input-group-append">
                                    <span class="input-group-text font-w600">s/d</span>
                                </div>
                                <input type="text" class="form-control datepicker" id="date_2" name="date_2"
                                       placeholder="Hingga" data-week-start="1" data-autoclose="true"
                                       data-today-highlight="true" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="clearfix"></div>

    <div class="block" id="my-block-2">
        <div class="block-header block-header-default">
            <div class="d-flex justify-content-between">
                <h3 class="block-title">Data Rekap Produk</h3>
                &nbsp;&nbsp;
{{--                <select class="form-control" name="filter" id="filters">--}}
{{--                    <option value="semua">Semua Produk</option>--}}
{{--                    <option value="filter">Penjualan</option>--}}
{{--                </select>--}}
            </div>
            <div class="block-options">
                <div class="btn-toolbar float-right" role="toolbar"
                     aria-label="@lang('labels.general.toolbar_btn_groups')">
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
                <div class="col-6">
                    <label for="startdate">Tampilkan</label>
                    <select class="form-control" name="filter" id="filters">
                        <option value="semua">Semua Produk</option>
                        <option value="filter">Penjualan</option>
                    </select>
                </div>
            </div>

            <br>
            <table id="table"
                   class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination"
                   width="100%">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Nama Produk</th>
                    <th>Terjual</th>
                    <th>Retur</th>
                    <th>Satuan</th>
                    <th>Total Penjualan</th>
                    <th>Total Retur</th>
                    <th>Stok</th>
                </tr>
                </thead>

            </table>
        </div>
    </div>

    <script>
        jQuery(function () {

            Codebase.helpers(['datepicker', 'notify', 'select2']);

            $('.datepicker').datepicker({
                dateFormat: 'dd-mm-yy'
            }).datepicker("setDate", new Date());

            $(document).on("focusout", ".datepicker", function () {
                $(this).prop('readonly', false);
            });

            $(document).on("focusin", ".datepicker", function () {
                $(this).prop('readonly', true);
            });

            var dt = $('#table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ordering: false,
                pageLength: 25,
                autoWidth: true,
                ajax: {
                    url : '{!! url('admin/accounting/rekap-produk/data/getData') !!}',
                    data: function(data) {
                        data.awal = $("#date_1").val()
                        data.akhir = $("#date_2").val()
                        data.filter = $("#filters").val()
                        data.category = $("#category").val()
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
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'font-w600'
                    },
                    {
                        data: 'qty_sold',
                        name: 'qty_sold',
                    },
                    {
                        data: 'retur',
                        name: 'retur',
                    },
                    {
                        data: 'satuan',
                        name: 'satuan'
                    },
                    {
                        data: 'total_sold',
                        name: 'total_sold'
                    },
                    {
                        data: 'total_retur',
                        name: 'total_retur',
                    },
                    {
                        data: 'stok',
                        name: 'stok',
                    }
                    // {
                    //     "visible": false,
                    //     data: 'discount_percent',
                    //     name: 'discount',
                    // },

                    // {
                    //     data: 'status',
                    //     name: 'status',
                    // },

                ],
                initComplete: function () {

                }
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

            $(".datepicker").on('change',function () {
                dt.ajax.reload()
            })

            $("#filters").select2()

            $("#filters").on('change',function () {
                dt.ajax.reload()
            })

            $("#category").select2()

            $("#category").on('change',function () {
                dt.ajax.reload()
            })

        })
    </script>
@endsection
