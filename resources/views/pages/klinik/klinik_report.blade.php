@extends('base.app')

@section('title', app_name() . ' | Lapora Klinik')

@section('content')
    <style type="text/css">
        tfoot {
            display: table-header-group;
        }
    </style>
    <div class="block" id="my-block">
        <div class="block-header justify-content-start">
            <div class="col-md-4">
                <a class="block block-rounded block-bordered block-link-shadow">
                    <div class="block-content block-content-full block-recent">
                        <div class="py-20 text-center">
                            <div class="mb-10">
                                <i class="si si-users fa-3x text-corporate"></i>
                            </div>
                            <div class="font-size-h4 font-w600"> {{ count(@$user) }}</div>
                            <div class="text-muted">Jumlah Pegawai</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="block block-rounded block-bordered block-link-shadow">
                    <div class="block-content block-content-full block-recent">
                        <div class="py-20 text-center">
                            <div class="mb-10">
                                <i class="si si-wallet fa-3x text-corporate"></i>
                            </div>
                            <div class="font-size-h4 font-w600"> {{ '0'  }}</div>
                            <div class="text-muted">Jumlah Total Omset</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="block block-rounded block-bordered block-link-shadow">
                    <div class="block-content block-content-full block-recent">
                        <div class="py-20 text-center">
                            <div class="mb-10">
                                <i class="si si-user fa-3x text-corporate"></i>
                            </div>
                            <div class="font-size-h4 font-w600"> {{ @$lama." / ".@$baru  }}</div>
                            <div class="text-muted">Pasien Lama / Baru</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="block-header block-header-default justify-content-start">
            <div class="col-md-3">
                <a href="" class="block block-link-shadow text-right">
                    <div class="block-content block-content-full clearfix">
                        <div class="float-left mt-10 d-none d-sm-block">
                            <i class="si si-docs fa-3x text-body-bg-dark"></i>
                        </div>
                        <div class="font-size-h3 font-w600 js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="1500">{{ $produk ?? 0 }}</div>
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Produk</div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="" class="block block-link-shadow text-right">
                    <div class="block-content block-content-full clearfix">
                        <div class="float-left mt-10 d-none d-sm-block">
                            <i class="si si-bag fa-3x text-body-bg-dark"></i>
                        </div>
                        <div class="font-size-h3 font-w600 js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="1500">{{ $service ?? 0 }}</div>
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Service</div>
                    </div>
                </a>
            </div>
        </div>
        <div class="block-content block-content-full">
            <h3 class="block-title"> Laporan Klinik </h3>
            <br>
            <table id="table" class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination" width="100%">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Komponen</th>
                    <th>Nominal</th>
                </tr>
                </thead>
                <tfoot>
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
                ajax: '{!! url('admin/masterdata/klinik/data/getReport') !!}',
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
                        data: 'komponen',
                        name: 'komponen'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal'
                    },
                ],
                initComplete: function () {

                }
            }).on('processing.dt', function (e, settings, processing) {

            }).on('draw.dt', function () {
            } );

            $("body").on('click','.btnUpdate',function () {
                $.ajax({
                    url: '{{ url("admin/masterdata/klinik/data/simpan") }}' ,
                    type: 'POST',
                    data: {
                        id: $(this).val()
                    },
                    beforeSend: function(xhr) {
                        Codebase.blocks('#my-block2', 'state_loading');
                    },
                    error: function() {
                        alert('An error has occurred');
                        Codebase.blocks('#my-block2', 'state_normal');
                    },
                    success: function(data) {
                        location.reload();
                        Codebase.blocks('#my-block2', 'state_normal');
                    },
                });
            })

            $("body").on('click','.btnHapus',function () {
                var form  = $(this).parent()
                Swal.fire({
                    title: 'Hapus Klinik?',
                    text: 'You won\'t be able to revert this!',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: `Hapus`,
                    denyButtonText: `Batalkan`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if(!result.value) return
                    form.submit();
                })
            })
        });
    </script>
@endsection
