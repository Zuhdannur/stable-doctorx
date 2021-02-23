@extends('backend.layouts.app')

@section('title', app_name().' | '.__('accounting::menus.purchase.title'))

@section('content')
    <div class="block" id="my-block2">

        {{-- content header --}}
        <div class="block-header block-header-default">
            <h3 class="block-title">
                @lang('accounting::labels.biaya.pay')
            </h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                <a href="{{ url()->previous() }}" class="btn-block-option">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        {{-- content body --}}
        <div class="block-content block-content-full">

            <div class="row px-5 pt-5">
                <div class="col-4 contact-details">
                    <h5>Penerima: {{ $purchase->financeTrx->person }}</h5>
                    <h6>{{ $purchase->financeTrx->transaction_code }}</h6>
                </div>
                <div class="invoice-details col-4 offset-4 text-right">
                    <h6>Dibuat Pada: {{ $purchase->created_at }}</h6>
                    <h6>Jatuh Tempo: {{ strtotime($purchase->due_date) > 0 ? date('d/m/y', strtotime($purchase->due_date)) : ' - ' }}</h6>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive push">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>Items</th>
                            <th class="text-center" style="width: 90px;">Deskripsi</th>
                            <th class="text-right" style="width: 60px;">Qty</th>
                            <th class="text-center" style="width: 150px;">Harga Satuan</th>
                            <th class="text-right" style="width: 120px;">Pajak</th>
                            <th class="text-right" style="width: 150px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sub_total = 0;
                            $totalTax = 0;
                            $potonganValue = '0';
                            $potongan_account = '';
                            $no = 1;
                        @endphp
    
                        @foreach($purchase->detail as $key => $item)
                            <tr>
                                <td class="text-center">{{ ($no++) }}</td>
                                @php
                                    $product = explode('#',$item->items, 2);
                                    $product_detail = explode('-', $product[1]);
                                @endphp
                                <td>
                                    <p class="font-w600 mb-5">{{ (isset($product_detail[0]) ? $product_detail[0] : '' ).( isset($product_detail[1]) ? '-'.$product_detail[1] : '')}}</p>
                                    <div class="text-muted">{{ isset($product_detail[2]) ? $product_detail[2] : '' }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-primary">{{ $item->desc }}</span>
                                </td>
                                <td class="text-right">
                                    {{ $item->qty }}
                                </td>
                                <td class="text-right">
                                    {{ currency()->rupiah($item->price, setting()->get('currency_symbol')) }}
                                </td>
                                <td class="text-right">
                                    {{ $item->tax_label}}
                                </td>
                                <td class="text-right">
                                    {{ $item->price_total}}
                                    @php
                                        $sub_total = $sub_total + $item->price_total;
                                        $sum_tax = $item->price_total * $item->tax_value / 100;
                                        $totalTax = $totalTax + $sum_tax;
                                    @endphp
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="6" class="font-w600 text-right">Subtotal</td>
                            <td class="text-right">{{ currency()->rupiah($sub_total, setting()->get('currency_symbol')) }}</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="font-w600 text-right">Total Tax</td>
                            <td class="text-right">{{ currency()->rupiah($totalTax, setting()->get('currency_symbol')) }}</td>
                        </tr>
                        <tr>
                            @if ($purchase->financeTrx->potongan)
                                {{-- get potongan --}}
                                @php
                                    $potongan = $purchase->getPotongan();
                                    if($potongan){
                                        $potonganValue = $potongan->value;
                                        $potongan_account = $potongan->account->account_name ? $potongan->account->account_name : '';
                                    }else{
                                        $potonganValue = 0;
                                    }
                                @endphp 
                                <td colspan="6" class="font-w600 text-right">Potongan ( {{ $potongan_account}}) : {{ $purchase->financeTrx->potongan.' %' }}</td>
                                <td class="text-right">{{ currency()->rupiah($potonganValue, setting()->get('currency_symbol')) }}</td>
                            @else
                                <td colspan="6" class="font-w600 text-right">Potongan 0%</td>
                                <td class="text-right">{{ currency()->rupiah(0, setting()->get('currency_symbol')) }}</td>
                            @endif
                        </tr>
                        <tr class="table-warning">
                            <td colspan="6" class="font-w700 text-uppercase text-right">Total</td>
                            <td class="font-w700 text-right">{{ currency()->rupiah($purchase->total, setting()->get('currency_symbol')) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- END Table -->
    
            <!-- Footer -->
            <p class="text-center">Ringkasan dan Catatan : {{ $purchase->financeTrx->memo ?? '-' }}</p>
            <!-- END Footer -->
    
            <div class="row-mt-4">
                {{-- form --}}
                <form method="post" class="js-validation-bootstrap" id="purchase_pay" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="purchase_id" value="{{  $purchase->id }}">
                    <div class="row-mt-4">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="acc_cash" class="form-control-label">Tanggal Bayar</label>
                                <input type="text" class="form-control datepicker" id="date_trx" name="date_trx">
                            </div>
                        </div>
                    </div>
                    <div class="row-mt-4">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="acc_cash" class="form-control-label">Bayar dari</label>
                                <select name="acc_cash" id="acc_cash" class="form-control required" data-placeholder="Pilih" style="width: 100%">
                                    {!! $cashList !!}
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="total_pay" class="form-control-label">Total Bayar</label>
                                <input type="text" class="form-control tanpa-rupiah text-right" id="total_pay" name="total_pay" value="{{ $purchase->remain_payment}}">
                            </div>
                            <div class="col-md-4">
                                <label for="remain" class="form-control-label">Sisa Tagihan</label>
                                <input type="text" class="form-control tanpa-rupiah text-right" id="remain" name="remain" readonly value="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-md-8">
                            <label class="form-control-label" for="example-textarea-input">Catatan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="6" placeholder="Catatan.."></textarea>
                        </div>
    
                        <div class="col-4">
                            <label for="mega-lastname">Lampiran</label>
                            <br>
                            <button type="button" onclick="document.getElementById('file-input').click();" class="btn btn-sm btn-success mr-5 mb-5">
                                   <i class="fa fa-plus mr-5"></i>Upload File
                            </button>
                            <input id="file-input" type="file" name="file-input" style="display: none;" accept="application/msword,application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,image/x-png,image/jpeg,image/jpg,application/vnd.openxmlformats-officedocument.wordprocessingml.document"/>
                            <small>Ukuran maks file 10 mb</small>
                            <div id='file-info'></div>
                        </div>
                    </div>
    
                    <div class="row-mt-4 clearfix">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('admin.accounting.biaya') }}" class="btn btn-danger"><span class="fa fa-close"></span> Kembali</a>
                            <button type="submit" class="btn btn-success"><span class="fa fa-check"></span> Simpan Pembayaran</button>
                        </div>
                    </div>
                </form>
                {{-- end form --}}
            </div>
        </div>
    </div>

    <script>
        var remain_payment = "{{ $purchase->remain_payment }}";
        jQuery(function(){

            // select2
            $('#acc_cash').select2({
                placeholder: "Pilih"
            });

            // date picker
            $('.datepicker').datepicker({
                todayHighlight: true,
                format: "{{ setting()->get('date_format_js') }}",
                weekStart: 1,
                language: "{!! str_replace('_', '-', app()->getLocale()) !!}",
                daysOfWeekHighlighted: "0,6",
                autoclose: true
            });

            var lastDate = new Date();
            lastDate.setDate(lastDate.getDate());//any date you want
            $(".datepicker").datepicker('setDate', lastDate);

            $(document).on("focusout", ".datepicker", function() {
                $(this).prop('readonly', false);
            });

            $(document).on("focusin", ".datepicker", function() {
                $(this).prop('readonly', true);
            });
            // end date picker

            // file handler
            $(document).on("change", "[type=file]", function() {

                if($(this).val() != ''){
                    var input = this;
                    var file_size = input.files[0].size;
                    var file_type = input.files[0].type;

                    var acc_type = [
                        "application/msword",
                        "application/pdf",
                        "application/vnd.ms-excel",
                        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                        "image/x-png",
                        "image/jpeg",
                        "image/jpg",
                        "application/vnd.openxmlformats-officedocument.wordprocessingml.document" 
                    ];
                    
                    if(file_size > 10485760){
                        $.alert({
                            title: 'Error',
                            icon: 'fa fa-warning',
                            type: 'red',
                            content: "Ukuran file maksimal: 10MB",
                        });
                        $("#file-input").val("");      
                        return false;
                    }

                    if(acc_type.indexOf(file_type) == -1){
                        $.alert({
                            title: 'Error',
                            icon: 'fa fa-warning',
                            type: 'red',
                            content: "Ekstensi File tidak valid !!! <br> Accepted Extension : .jpg, .png, .pdf, .doc, .xls, .xlsx, .docx",
                        });
                        $("#file-input").val("");      
                        return false;
                    }

                    $('#file-info').html('<label class="form-control-label">'+input.files[0].name+
                                    '&nbsp;<small>'+formatBytes(file_size)+'<small></label>'+
                                    '<br><button class="btn btn-danger btn-sm" id="cancel-file"><i class="fa fa-close"></i></button>');
                    $('#cancel-file').on('click', function(){
                        $('#file-info').html('');
                        $('#file-input').html('');
                    })                    
                }
            });

            $(document).ready(function(){
                $('#total_pay').on('keyup change mouseup', function(){
                    calc_remain();
                })
            });

             // submit handler
             var validator = jQuery('.js-validation-bootstrap').validate({
                ignore: [],
                errorClass: 'invalid-feedback animated fadeInDown',
                errorElement: 'div',
                errorPlacement: function(error, e){
                    jQuery(e).parents('.form-group > div').append(error);
                },
                highlight: function(e) {
                    jQuery(e).closest('form-group').removeClass('is-invalid').addClass('is-invalid');
                },
                success: function(e) {
                    jQuery(e).closest('.form-group').removeClass('is-invalid');
                    jQuery(e).remove();
                },
                rules:{
                    'acc_cash' :{
                        required: true
                    },
                    'date_trx':{
                        required: true
                    },
                    'total_pay':{
                        required: true
                    }
                },
                submitHandler: function(form){
                    var data = new FormData(form);
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('admin.accounting.purchase.savePay') }}",
                        data: data,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        beforeSend: function(xhr){
                            Codebase.blocks('#my-block2','state_loading');
                        },
                        error: function(x, status, error){
                            if (x.status == 403) {
                                $.alert({
                                    title: 'Error',
                                    icon: 'fa fa-warning',
                                    type: 'red',
                                    content: "Error: "+ x.status + "",
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
                        success: function(result){
                            if (result.status) {
                                $.notify({
                                    message: result.message,
                                    type: 'success'
                                });

                                setTimeout(function() { 
                                    window.location = '{{ route('admin.accounting.purchase') }}';
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
            });
            // end submit handler

            function calc_remain(){
                total = parseInt(remain_payment - toNumber($('#total_pay').val()));

                $('#remain').val(formatRupiah(total, '{{ setting()->get('currency_symbol') }}'));
            }

            function formatBytes(bytes, decimals = 2) {
                if (bytes === 0) return '0 Bytes';

                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

                const i = Math.floor(Math.log(bytes) / Math.log(k));

                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            }
        })
    </script>
@endsection