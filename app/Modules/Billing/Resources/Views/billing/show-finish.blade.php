@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('billing::labels.billing.management'))

@section('content')
<!-- Invoice -->
<h2 class="content-heading d-print-none">
    <a href="{{ route('admin.billing.create', [0,0]) }}" class="btn btn-sm btn-rounded btn-success float-right">Buat Invoice Baru</a>
    Invoice
</h2>
<div class="block" id="my-block2">
    <div class="block-header block-header-default">
        <h3 class="block-title">#{{$billing->invoice_no}}</h3>
        <div class="block-options">
            <div class="btn-group" role="group" aria-label="Third group">
                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" id="toolbarDrop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="si si-printer"></i> Cetak</button>
                <div class="dropdown-menu" aria-labelledby="toolbarDrop" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 34px, 0px);">
                    <h6 class="dropdown-header">Format</h6>
                    <a class="dropdown-item printInv" href="javascript:void(0)">
                        <i class="fa fa-fw fa-bell mr-5"></i>Print Preview
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.billing.print.pdf', $billing->id) }}" target="_blank">
                        <i class="fa fa-fw fa-envelope-o mr-5"></i>PDF Preview
                    </a>
                </div>
            </div>
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
            <a href="{{ route('admin.billing.index') }}" class="btn-block-option">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="block-content">
        {{-- Invoice Info --}}
        @include('billing::billing.components.invoice-info')
        {{-- END Invoice Info  --}}

        {{-- table history payments --}}
        @include('billing::billing.components.invoice-payment-histories')
        {{-- end of table history payments --}}

        {{-- Table --}}
        <div class="table-responsive push">
            <h5>Invoice Detail</h5>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;"></th>
                        <th>Product</th>
                        <th class="text-center" style="width: 90px;">Qnt</th>
                        <th class="text-right" style="width: 120px;">Unit</th>
                        <th class="text-right" style="width: 120px;">Pajak</th>
                        <th class="text-right" style="width: 120px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($billing->invDetail as $key => $item)
                        @php
                            $amount = $item->qty * $item->price + ($item->qty * $item->price * $item->tax_value / 100);
                        @endphp
                    <tr>
                        <td class="text-center">{{ ($key+1) }}</td>
                        <td>
                            <p class="font-w600 mb-5">{{ $item->productname }}</p>
                            <div class="text-muted">{{ $item->productdesc }}</div>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-pill badge-primary">{{ $item->qty }}</span>
                        </td>
                        <td class="text-right">{{ currency()->rupiah($item->price, setting()->get('currency_symbol')) }}</td>
                        <td class="text-center">
                            <span>{{ $item->tax_label }}</span>
                        </td>
                        <td class="text-right">{{ currency()->rupiah($amount, setting()->get('currency_symbol')) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" class="font-w600 text-right">Subtotal</td>
                        <td class="text-right">{{ currency()->rupiah($billing->subtotal, setting()->get('currency_symbol')) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="font-w600 text-right">Total Tax</td>
                        <td class="text-right">{{ currency()->rupiah($billing->tax_total, setting()->get('currency_symbol')) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="font-w600 text-right">Diskon {{ $billing->discount_percent }}</td>
                        <td class="text-right">{{ currency()->rupiah($billing->discountprice, setting()->get('currency_symbol')) }}</td>
                    </tr>
                    {{-- if membership --}}
                    @if ($billing->patient->membership)
                        <tr>
                            <td colspan="5" class="font-w600 text-right">Potongan Radeem</td>
                            <td colspan="2" class="text-right">{{ currency()->rupiah($billing->radeem_point, setting()->get('currency_symbol')) }}</td>
                        </tr>
                    @endif
                    <tr class="table-warning">
                        <td colspan="5" class="font-w700 text-uppercase text-right">Total</td>
                        <td class="font-w700 text-right">
                            @php
                                $total = $billing->subtotal + $billing->tax_total - $billing->discountprice - $billing->radeem_point
                            @endphp
                            {{ currency()->rupiah($total, setting()->get('currency_symbol')) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- END Table -->

        <!-- Footer -->
        <p class="text-center">Ringkasan dan Catatan Faktur : {{ $billing->notes ?? '-' }}</p>
        <p class="text-muted text-center">Faktur dibuat dan valid tanpa tanda tangan dan meterai.</p>
        <!-- END Footer -->
        @if($billing->status == config('billing.invoice_unpaid'))
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <div class="form-group row">
                    <label for="acc_cash" class="form-control-label">Bayar Ke :</label>
                    <div class="col-md-8">
                        <select name="acc_cash" id="acc_cash" class="form-control" width="100%" required>
                            {!! $cashAccountList !!}
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <p class="text-muted text-center"></p>
        <p class="text-muted text-center"></p>
        <p class="text-muted text-center"><button type="button" class="btn btn-success submitInvoice" data-id="{{$billing->id}}"><span class="fa fa-check"></span> Submit Transaksi & Cetak</button></p>
        @endif
    </div>
</div>
<!-- END Invoice -->

<script type="text/javascript">
jQuery(function () {
    $('#acc_cash').select2({
        placeholder: 'Pilih'
    });

    $(document).on('click', '.printInv', function() {
        $.ajax({
            url: "{{ route('admin.billing.print.html', $billing->id) }}",
            type: 'GET',
            beforeSend: function(xhr) {
                Codebase.blocks('#my-block2', 'state_loading');
            },
            error: function(x, status, error) {
                if (x.status == 403) {
                    $.alert({
                        title: 'Error',
                        icon: 'fa fa-warning',
                        type: 'red',
                        content: "Error: " + x.status + "",
                    });
                } else {
                    $.alert({
                        title: 'Error',
                        icon: 'fa fa-warning',
                        type: 'red',
                        content: "An error occurred: " + status + "nError: " + error,
                    });
                }

                Codebase.blocks('#my-block2', 'state_normal');
            },
            success: function(response) {
                Popup(response);
                Codebase.blocks('#my-block2', 'state_normal');
            },
        });
    });

    function Popup(data)
    {
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);


        return true;
    }

    printInvoice = function(id){
        var url = '{{ route("admin.billing.print.html", ":id") }}';
        url = url.replace(':id', id);

        $.ajax({
            url: url,
            type: 'GET',
            beforeSend: function(xhr) {
                Codebase.blocks('#my-block2', 'state_loading');
            },
            error: function(x, status, error) {
                if (x.status == 403) {
                    $.alert({
                        title: 'Error',
                        icon: 'fa fa-warning',
                        type: 'red',
                        content: "Error: " + x.status + "",
                    });
                } else {
                    $.alert({
                        title: 'Error',
                        icon: 'fa fa-warning',
                        type: 'red',
                        content: "An error occurred: " + status + "nError: " + error,
                    });
                }

                Codebase.blocks('#my-block2', 'state_normal');
            },
            success: function(response) {
                Popup(response);
                Codebase.blocks('#my-block2', 'state_normal');
            },
        });
    }

    $(document).on('click', '.submitInvoice', function() {
        var billId = $(this).data('id');
        var accCash = $('#acc_cash').val();

        var url = '{{ route("admin.billing.storepaid", [":id", ":acc_cash"]) }}';
            url = url.replace(':id', billId);
            url = url.replace(':acc_cash', accCash);

        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data transaksi akan di simpan dan tidak dapat diperbarui!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: 'json',
                    beforeSend: function(xhr) {
                        Codebase.blocks('#my-block2', 'state_loading');
                    },
                    error: function(x, status, error) {
                        if (x.status == 403) {
                            $.alert({
                                title: 'Error',
                                icon: 'fa fa-warning',
                                type: 'red',
                                content: "Error: " + x.status + "",
                            });
                        } else if (x.status === 422) {
                            var errors = x.responseJSON;
                            var errorsHtml = '';
                            $.each(errors['errors'], function(index, value) {
                                errorsHtml += '<ul><li class="text-danger">' + value + '</li></ul>';
                            });

                            $.alert({
                                title: "Error " + x.status + ': ' + error,
                                icon: 'fa fa-warning',
                                type: 'red',
                                content: errorsHtml,
                                columnClass: 'col-md-5 col-md-offset-3',
                                typeAnimated: true,
                                draggable: false,
                            });

                        } else {
                            $.alert({
                                title: 'Error',
                                icon: 'fa fa-warning',
                                type: 'red',
                                content: "An error occurred: " + status + "nError: " + error,
                            });
                        }

                        Codebase.blocks('#my-block2', 'state_normal');
                    },
                    success: function(result) {
                        if (result.status) {
                            $.notify({
                                message: result.message,
                                type: 'success'
                            });

                            printInvoice(result.data.id);

                            setTimeout(function() { 
                                window.location = '{{ route('admin.billing.index') }}';
                            }, 1000);
                        } else {
                            $.alert({
                                title: 'Error',
                                icon: 'fa fa-warning',
                                type: 'orange',
                                content: result.message,
                            });
                        }

                        Codebase.blocks('#my-block2', 'state_normal');
                    }
                });
                return false; // required to block normal submit since you used ajax
            }
        })

    });

});
</script>
@endsection