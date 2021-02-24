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
            <h3 class="block-title">@lang('accounting::labels.biaya.table_title')</h3>
            <div class="block-options">
                <div class="btn-toolbar float-right" role="toolbar"
                     aria-label="@lang('labels.general.toolbar_btn_groups')">
                    <a href="{{ route('admin.accounting.biaya.create') }}" class="btn btn-sm btn-alt-info"
                       data-toggle="tooltip" title="@lang('labels.general.create_new')">
                        <i class="fa fa-random"></i> @lang('labels.general.create_new')
                    </a>
                </div>
            </div>
        </div>

        <div class="block-content block-content-full">
            <table id="table"
                   class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination"
                   width="100%">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Nomor Invoice</th>
                    <th>Nama Pasien</th>
                    <th>Qty</th>
                    <th>Status Hari ini</th>
                    <th>Note</th>
                    <th>Total</th>
                    <th>Tindakan</th>
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
                    url : '{!! url('admin/accounting/rekap-penjualan/data/getData') !!}',
                    data: function(data) {
                        data.awal = $("#date_1").val()
                        data.akhir = $("#date_2").val()
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
                        data: 'invoice_no',
                        name: 'invoice_no',
                        className: 'font-w600'
                    },
                    {
                        data: 'patient_name',
                        name: 'patient.patient_name',
                    },
                    {
                        data: 'qty',
                        name: 'qty',
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'note',
                        name: 'note'
                    },
                    {
                        data: 'total',
                        name: 'total',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        width: "8%",
                        className: 'text-right',
                        orderable: false,
                        searchable: false
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



        })
    </script>
@endsection
