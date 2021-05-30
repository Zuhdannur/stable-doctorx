@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('log.show'))

@section('content')

    @include('accounting::reports.partial.range-date')

    <div class="clearfix"></div>
    <div class="clearfix"></div>

    <div class="block" id="my-block-2">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('log.show')</h3>
            <div class="block-options">
                <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                    <a href="{{ route('admin.accounting.biaya.create') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
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
                        <th class="text-center">@lang('log.tables.date')</th>
                        <th class="text-center">@lang('log.tables.action')</th>
                        <th class="text-center">@lang('log.tables.desc')</th>
                        <th class="text-center">@lang('log.tables.user')</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="view_billing" role="dialog" style="z-index: 10000">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title" id="title"></h3>
                        <a class="btn btn-sm btn-success invoiceDetail">Lihat Invoice</a>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <p id="date"></p>
                        <p >Status : <span class="badge badge-info" id="status"></span></p>
                        <p id="in_paid">Terbayar : </p>
                        <table id="detail" class="table table-striped ">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>

    <script>
         jQuery(function(){
            var dt = $('#table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ordering: false,
                pageLength: 25,
                ajax: {
                        url : '{!! route('admin.crm.log.show') !!}',
                        data : function(data){
                            data.date_1 =  "{{ $date['date_1'] }}";
                            data.date_2 =  "{{ $date['date_2'] }}";
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
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'action',
                        name: 'action',
                    },
                    {
                        data: 'desc',
                        name: 'desc',
                    },
                    {
                        data: 'username',
                        name: 'username',
                    },
                ]
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

            $('body').on('click','.btnDetailBilling',function () {
                var item = JSON.parse($(this).val())
                $("#title").text("Log Aktivitas Invoice : "+item.invoice_no)
                $("#date").text("Tanggal :" +item.date)

                var status = ""

                switch (item.status) {
                    case "0":
                        status = "Unpaid"
                        break;
                    case "1":
                        status = "Paid"
                        break;
                    default:
                        status = "Partial Paid"
                }

                $("#status").text(status)
                $("#in_paid").text("Terbayar : "+item.in_paid)
                var index = 0;

                $.each(item.inv_detail , function (key,value) {
                    index++;
                    $('#detail tr:last').after('<tr>' +
                        '<td>'+ index + '</td>' +
                        '<td>'+ value.product_id + '</td>' +
                        '<td>'+ value.qty + '</td>' +
                        '<td>'+ value.price + '</td>' +
                        '</tr>');

                    $("#view_billing").modal('show')
                })


            })
        })
    </script>
@endsection
