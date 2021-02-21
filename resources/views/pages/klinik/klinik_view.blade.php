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
                            <div class="font-size-h4 font-w600"> {{ @$cabang }}</div>
                            <div class="text-muted">Jumlah Cabang</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-8">
                <a class="block block-rounded block-bordered block-link-shadow" href="{{ route('admin.patient.index') }}">
                    <div class="block-content block-content-full block-recent">
                        <div class="py-20 text-center">
                            <div class="mb-10">
                                <i class="si si-bag fa-3x text-corporate"></i>
                            </div>
                            <div class="font-size-h4 font-w600"> {{ @$pusat->nama_klinik  }}</div>
                            <div class="text-muted">Pusat Klinik</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="block-header block-header-default">
            <h3 class="block-title"> Daftar Klinik </h3>
            <div class="block-options">
                <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                    <a href="{{ route('klinik.create') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
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
                    <th>Nama Klinik</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Pusat / Cabang</th>
                    <th>Aksi</th>
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
                ajax: '{!! url('admin/masterdata/klinik/data/getData') !!}',
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
                        data: 'nama_klinik',
                        name: 'nama_klinik'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'button',
                        name: 'button',
                        className: 'text-right',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-right',
                        orderable: false,
                        searchable: false
                    }
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
                        // var result = data.data;
                        //
                        // if (result.length > 0) {
                        //     var optionCity = '<option value=""></option>';
                        //     $.each(result, function(key, value) {
                        //         optionCity += '<option value="' + value.id + '">' + value.name + '</option>';
                        //     });
                        //
                        //     optionCity += '</select></label>';
                        //
                        //     $("select" + elemen).html(optionCity);
                        //
                        // } else {
                        //     alert('data empty!');
                        // }

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
