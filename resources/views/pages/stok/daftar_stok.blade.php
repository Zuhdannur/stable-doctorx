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
                    <a class="block block-rounded block-bordered block-link-shadow">
                        <div class="block-content block-content-full block-recent">
                            <div class="py-20 text-center">
                                <div class="mb-10">
                                    <i class="si si-bag fa-3x text-corporate"></i>
                                </div>
                                <div class="font-size-h4 font-w600"> {{ @$ready }} Jenis</div>
                                <div class="text-muted">tersedia</div>
                                {{-- <div class="font-size-h4 font-w600">{{ $patient->total_patient }} Pasien</div> --}}
                                {{-- <div class="font-size-h4 font-w600">{{ $patient->total_patient_today }} pasien baru hari ini!</div> --}}
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a class="block block-rounded block-bordered block-link-shadow">
                        <div class="block-content block-content-full block-recent">
                            <div class="py-20 text-center">
                                <div class="mb-10">
                                    <i class="si si-bag fa-3x text-corporate"></i>
                                </div>
                                <div class="font-size-h4 font-w600"> {{ @$min }} Jenis</div>
                                <div class="text-muted">stok segera habis</div>
                            </div>
                        </div>
                    </a>
                </div>
            <div class="col-md-4">
                <a class="block block-rounded block-bordered block-link-shadow">
                    <div class="block-content block-content-full block-recent">
                        <div class="py-20 text-center">
                            <div class="mb-10">
                                <i class="si si-bag fa-3x text-corporate"></i>
                            </div>
                             <div class="font-size-h4 font-w600"> {{ @$habis }} Jenis</div>
                            <div class="text-muted">Stok habis</div>
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
                    <th>Nama Product</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Category</th>
                    <th>Harga Rata rata</th>
                    <th>Harga Beli Terakhir</th>
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
                ajax: '{!! url('admin/masterdata/product/stok/data/getData') !!}',
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
                        name: 'name'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'unit',
                        name: 'unit'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'rata-rata',
                        name: 'rata-rata'
                    },
                    {
                        data: 'last_buy',
                        name: 'last_buy'
                    }
                ],
                initComplete: function () {

                }
            }).on('processing.dt', function (e, settings, processing) {

            }).on('draw.dt', function () {
            } );

        });
    </script>
@endsection
