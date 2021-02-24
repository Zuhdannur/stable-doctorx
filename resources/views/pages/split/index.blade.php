@extends('base.app')

@section('title', app_name() . ' | Stok Product')

@section('content')
    <style type="text/css">
        tfoot {
            display: table-header-group;
        }
    </style>
    <div class="block" id="my-block">
        <div class="block-header">
            <div class="col-md-4">
                <a class="block block-rounded block-bordered block-link-shadow" href="{{ route('admin.patient.index') }}">
                    <div class="block-content block-content-full block-recent">
                        <div class="py-20 text-center">
                            <div class="mb-10">
                                <i class="si si-bag fa-3x text-corporate"></i>
                            </div>
                            <div class="font-size-h4 font-w600"> {{ @$today }}</div>
                            <div class="text-muted">Pembayaran Sebagian Hari ini</div>
                            {{-- <div class="font-size-h4 font-w600">{{ $patient->total_patient }} Pasien</div> --}}
                            {{-- <div class="font-size-h4 font-w600">{{ $patient->total_patient_today }} pasien baru hari ini!</div> --}}
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="block block-rounded block-bordered block-link-shadow" href="{{ route('admin.patient.index') }}">
                    <div class="block-content block-content-full block-recent">
                        <div class="py-20 text-center">
                            <div class="mb-10">
                                <i class="si si-bag fa-3x text-corporate"></i>
                            </div>
                            <div class="font-size-h4 font-w600"> {{ @$all }} </div>
                            <div class="text-muted">Total Pembayaran Sebagian Lunas</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="block block-rounded block-bordered block-link-shadow" href="{{ route('admin.patient.index') }}">
                    <div class="block-content block-content-full block-recent">
                        <div class="py-20 text-center">
                            <div class="mb-10">
                                <i class="si si-bag fa-3x text-corporate"></i>
                            </div>
                            <div class="font-size-h4 font-w600"> {{ @$all }}</div>
                            <div class="text-muted">Total Pembayaran Sebagian Belum Lunas</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="block-content block-content-full">
            <table id="table" class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination" width="100%">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Nomor Invoice</th>
                    <th>Tanggal Invoice</th>
                    <th>Nama Pasien</th>
                    <th>Sudah Dibayar</th>
                    <th>Yang Harus Dibayar</th>
                    <th>Total</th>
                    <th>Tindakan</th>
                </tr>
                </thead>
                <tfoot>
                {{--                <tr>--}}
                {{--                    <th></th>--}}
                {{--                    <th></th>--}}
                {{--                    <th></th>--}}
                {{--                    <th></th>--}}
                {{--                    <th></th>--}}
                {{--                    <th></th>--}}
                {{--                    <th></th>--}}
                {{--                </tr>--}}
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
                ajax: '{!! url('list-splitbill/data/getData') !!}',
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
                        data: 'date',
                        name: 'date',
                    },
                    {
                        data: 'patient_name',
                        name: 'patient.patient_name',
                    },
                    {
                        data: 'sudah_dibayar',
                        name: 'sudah_dibayar'
                    },
                    {
                        data: 'remaining_payment',
                        name: 'remaining_payment'
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

            }).on('draw.dt', function () {
            } );

        });
    </script>
@endsection
