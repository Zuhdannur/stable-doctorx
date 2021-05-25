@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('billing::labels.billing.management'))

@section('content')
<!-- Invoice -->
<h2 class="content-heading d-print-none">
    <a href="{{ route('admin.billing.create', [0,0]) }}" class="btn btn-sm btn-rounded btn-success float-right">Buat Invoice Baru</a>Invoice
</h2>
<div class="block" id="my-block2">
    <div class="block-header block-header-default">
        <h3 class="block-title">#{{$billing->invoice_no}}</h3>
        <div class="block-options">
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

        <form method="post" id="invoice_form" class="js-validation-bootstrap  mt-4" autocomplete="off">
           {{-- additional form --}}
           @include('billing::billing.components.additional-form')
           {{-- end of additional lform --}}

           {{-- additional form --}}
           @include('billing::billing.components.invoice-show-table')
           {{-- end of additional lform --}}

            <p class="text-center">Ringkasan dan Catatan Faktur : {{ $billing->notes ?? '-' }}</p>
            <p class="text-muted text-center">Faktur dibuat dan valid tanpa tanda tangan dan meterai.</p>
            <p class="text-muted text-center"><button type="button" class="btn btn-success submitInvoice" data-id="{{$billing->id}}"><span class="fa fa-check"></span> Submit Transaksi & Cetak</button></p>
            <p class="text-muted text-center">
                <button type="button" class="btn btn-success d-none" data-id="{{$billing->id}}" id="cetakUlang">
                    <span class="fa fa-print mr-1"></span>Cetak Ulang
                </button>
            </p>
            <p class="text-muted text-center">
                <a href="{{ route('admin.billing.index') }}" class="btn-block-option d-none" id="backButton">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </p>
        </form>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    var $point_total = 0;
    const const_point = "{{ isset($billing->patient->membership->total_point) ? $billing->patient->membership->total_point : 0 }}";
    jQuery(function () {
        Codebase.blocks('#my-block2', 'fullscreen_toggle');

        $(document).ready(function(){

            $('.select2').select2({
                placeholder: 'Pilih',
                allowClear : 'true'
            });

            $('.item_radeem').on('change', function(){
                calc();
            });

            $('#table_logic tbody').on('keyup change mouseup', function(e) {
                calc();
            });

            $('#marketing').on('change', function(){
                _discount = $(this).find(':selected').attr('data-discount');
                _point= $(this).find(':selected').attr('data-point');
                _membership_point = $('#point').attr('data-point');
                if(_point !== undefined) {
                    _membership_point = parseInt(_membership_point) + parseInt(_point);
                }
                $('#totalPoint').html(const_point + ' + ' + _membership_point);
                $('#point').html(_membership_point);
                $('#discountPercent').html(_discount + '%');
                $('#discountPercent').data('discount', _discount);
                calc();
            })

            $('#total_receive').on('keyup change mouseup', function() {
                calc_kembalian();
            });

            calc();
            updateIds();

            /* Table Logic */
            var i = 1;
            $(document).on('click', '.add', function() {
                var html = '';
                html += '<tr class="radeem">';
                html += '<td><button type="button" name="remove" class="btn btn-sm btn-circle btn-outline-danger remove" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
                html += '<td colspan="2">';
                html += '<select name="item_radeem['+(i+1)+']" id="'+(i+1)+'item_radeem" class="form-control item_radeem select2" width="70%">';
                html += '{!! $radeem !!}';
                html += '</select>';
                html += '</td>';
                html += '<td>';
                html += '<input type="number" name="qty_point['+(i+1)+']" id="'+(i+1)+'qty_point" class="form-control qty_point" value="0">';
                html += '</td>';
                html += '<td>';
                html += '<input type="text" name="ammount_point['+(i+1)+']" id="'+(i+1)+'ammount_point" class="form-control ammount_point" value="0" readonly>';
                html += '</td>';
                html += '<td colspan="2">';
                html += '<input type="text" name="nominal_point['+(i+1)+']" id="'+(i+1)+'nominal_point" class="form-control nominal_point" value="0" readonly>';
                html += '</td>';
                html += '</tr>';

                i++;

                row = $(this).closest("tr").closest('tr');
                $(row).after(html);

                $('.select2').select2({
                    placeholder: 'Item Radeems',
                    allowClear : 'true'
                });
                calc();
                updateIds();
            });

            $(document).on('click', '.remove', function() {
                $(this).closest('tr').remove();
                calc();
                updateIds();
            });
            /** end of tabel logic*/

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

            $(document).on('click', '.submitInvoice', function() {
                var billId = $(this).data('id');
                // var accCash = $('#acc_cash').val();
                var form = $('#invoice_form').serialize();

                var url = '{{ route("admin.billing.storepaid", [":id"]) }}';
                url = url.replace(':id', billId);

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
                        try {
                            if($('#isPayAll').val() == '2'){
                                total_amount = toNumber($('#total').html());
                                total_pay = parseInt($('#totalPay').val())

                                if(total_pay > total_amount) throw new Error('Nominal jumlah yang dibayarkan tidak boleh lebih besar dari jumlah total.')
                            }

                            let sum_point = parseInt(const_point) + parseInt($('#point').html());
                            if($point_total > sum_point) throw new Error('Point Pasien Tidak Mencukupi !!!')
                        } catch (error) {
                            $.alert({
                                title: "Kesalahan",
                                icon: 'fa fa-warning',
                                type: 'red',
                                content: error,
                            });
                            return false;
                        }

                        $('.submitInvoice').hide();

                        $.ajax({
                            type: "POST",
                            url: url,
                            dataType: 'json',
                            data: form,
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
                                        content: x.responseJSON.message,
                                    });
                                }
                                $('.submitInvoice').show();
                                Codebase.blocks('#my-block2', 'state_normal');
                            },
                            success: function(result) {
                                if (result.status) {
                                    $.notify({
                                        message: result.message,
                                        type: 'success'
                                    });
                                    $('.submitInvoice').remove();
                                    $('#cetakUlang').removeClass('d-none');
                                    $('#backButton').removeClass('d-none');
                                    $('#cetakUlang').on('click', function() {
                                        printInvoice(result.data.id)
                                    });
                                    $('select').prop('disabled', true);
                                    $('input').prop('disabled', true)

                                    printInvoice(result.data.id);

                                } else {
                                    $.alert({
                                        title: 'Error',
                                        icon: 'fa fa-warning',
                                        type: 'orange',
                                        content: result.message,
                                    });
                                    $('.submitInvoice').show();
                                }

                                Codebase.blocks('#my-block2', 'state_normal');
                            }
                        });
                        return false; // required to block normal submit since you used ajax
                    }
                })
            });

            $('#isPayAll').on('change', function (e) {
                if($(this).val() == '2') {
                    $('#totalPayGroup').removeClass('d-none')
                }else {
                    $('#totalPayGroup').addClass('d-none')
                }
            })
        });

        function calc(){
            var ammount = 0;
            var tax = 0;
            var discount = parseInt($('#discountPercent').data('discount'));
            var total = 0;
            var potongan_point = calc_potongan_point();

            $('#table_logic tbody .billing_detail').each(function(i, element){
                var html = $(this).html();
                if(html != ''){
                    let tax_val = parseInt(toNumber($(this).find('.tax_ammount').html()) );
                    ammount = ammount + parseInt(toNumber($(this).find('.ammount').html()));
                    tax = tax + tax_val;
                }
            });

            discount_sum = parseInt((ammount + tax) * (discount / 100));
            total = ammount + tax - discount_sum - potongan_point;
            $('#discount').html(formatRupiah(discount_sum, '{{ setting()->get('currency_symbol') }}'));
            $('#total_potongan_point').html(formatRupiah(potongan_point, '{{ setting()->get('currency_symbol') }}'));
            $('#sub_total').html(formatRupiah(ammount, '{{ setting()->get('currency_symbol') }}'));
            $('#total').html(formatRupiah(total, '{{ setting()->get('currency_symbol') }}'));

            calc_nominal_point();
            calc_kembalian();
        }

        function calc_nominal_point(){
            $('#table_logic tbody .radeem').each(function(i, element){
                var html = $(this).html();
                if (html != '') {
                    let point = $(this).find('.item_radeem :selected').attr('data-point');
                    let gift = $(this).find('.item_radeem :selected').attr('data-gift');

                    if(point == '' || point == 'null'){
                        point = 0;
                    }

                    if(gift == '' || gift == 'null'){
                        gift = 0;
                    }

                    total_point = point * $(this).find('.qty_point').val();
                    total_gift = gift * $(this).find('.qty_point').val();

                    if(total_point == '' || total_point == 'null' || isNaN(total_point)){
                        total_point = 0;
                    }

                    if(total_gift == '' || total_gift == 'null' || isNaN(total_point)){
                        total_gift = 0;
                    }

                    // sum globat point
                    $point_total = point + $point_total;

                    $(this).find('.ammount_point').val(total_point);
                    $(this).find('.nominal_point').val(formatRupiah(total_gift, '{{ setting()->get('currency_symbol') }}'));
                }
            });
        }

        function calc_potongan_point(){
            var potongan = 0;
            $('#table_logic tbody .radeem').each(function(i, element){
                var html = $(this).html();

                if(html != ''){
                    is_potongan = parseInt(toNumber($(this).find('.nominal_point').val()));

                    if(is_potongan == '' || isNaN(is_potongan) || is_potongan == null){
                        is_potongan = 0;
                    }

                    potongan = potongan + is_potongan;
                }
            });

            return potongan;
        }

        function calc_kembalian() {
            total_amount = toNumber($('#total').html());

            if($('#isPayAll').val() == '2'){
                total_amount = $('#totalPay').val()
            }

            total_receive = $('#total_receive').val();
            totalKembalian = toNumber(total_receive) - total_amount;

            if(totalKembalian.toString().indexOf("-") > -1){
                $('#kembalianTr').addClass('table-danger').removeClass('table-success');
                $('.submitInvoice').attr("disabled", true);
            }else{
                if(total_amount == '0'){
                    $('#kembalianTr').addClass('table-danger').removeClass('table-success');
                    $('.submitInvoice').attr("disabled", true);
                }else{
                    $('#kembalianTr').addClass('table-success').removeClass('table-danger');
                    $('.submitInvoice').attr("disabled", false);
                }
            }

            $('#total_kembalian').val(formatRupiah(totalKembalian, '{{ setting()->get('currency_symbol') }}'));
        }

        function updateIds() {
            $('#table_logic tbody .radeem').each(function(i, element) {
                var html = $(this).html();
                if (html != '') {
                    $(this).find('.item_radeem').attr('name', 'item_radeem['+(i+1)+']');
                    $(this).find('.qty_point').attr('name', 'qty_point['+(i+1)+']');
                    $(this).find('.ammount_point').attr('name', 'ammount_point['+(i+1)+']');
                    $(this).find('.nominal_point').attr('name', 'nominal_point['+(i+1)+']');
                }
            });
        }

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

            Swal.fire({
                title: 'Tulisakan Pesan Pada Struk',
                input: 'text',
                inputValue: 'BARANG YANG SUDAH DI BELI TIDAK BISA DI TUKAR/ DI KEMBALIKAN',
                inputPlaceholder: 'Tuliskan Pesan Manual',
                confirmButtonText: 'Cetak Invoice',
            }).then(function (isConfirm) {
               if(isConfirm.value) {
                   $.ajax({
                       url: url,
                       type: 'POST',
                       data: {
                         message : isConfirm.value
                       },
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
            });


        }
    });
</script>
@endpush
