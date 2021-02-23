@extends('base.app')

@section('title', app_name() . ' | Setting Modul')

@section('content')
    <style type="text/css">
        tfoot {
            display: table-header-group;
        }
    </style>
    <div class="block" id="my-block">
        <div class="block-header block-header-default">
            <h3 class="block-title"> Setting Modul </h3>
            <div class="block-options">
                <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">

                    <a href="{{ route('setting-modul.create') }}" class="btn btn-sm btn-alt-warning" data-toggle="tooltip" title="@lang('labels.general.create_new')">
                        <i class="fa fa-file"></i> Atur Urutan Modul
                    </a>
                    &nbsp;&nbsp;
                    <a href="{{ route('setting-modul.create') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
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
                    <th>Nama Modul</th>
                    <th>Url</th>
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
                ajax: '{!! url('admin/masterdata/setting-modul/data/getData') !!}',
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
                        data: 'nama_modul',
                        name: 'nama_modul'
                    },
                    {
                        data: 'url_modul',
                        name: 'url_modul'
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
