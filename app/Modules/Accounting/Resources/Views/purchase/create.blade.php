@extends('backend.layouts.app')

@section('title', app_name().' | '.__('accounting::menus.purchase.title'))

@section('content')
    <div class="block" id="my-block-2">

         {{-- content header --}}
         <div class="block-header block-header-default">
            <h3 class="block-title">
                @lang('accounting::labels.purchase.main')
                <small class="text-muted">
                    &nbsp;
                    @lang('accounting::labels.purchase.create')
                </small>
            </h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
            </div>
        </div>

         {{-- content form --}}

         <div class="block-content block-content-full">
            <form method="post" class="js-validation-bootstrap" id="send_form" autocomplete="off" enctype="multipart/form-data">
                <div class="row-mt-4">
                    <div class="col">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="supplier" class="form-control-label">Supplier</label>
                                <select name="supplier" id="supplier" class="form-control required" width='100%'>
                                    {!! $supplier !!}
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="date_trx" class="form-control-label">Tanggal Transaksi</label>
                                <input type="text" class="form-control datepicker required" id="date_trx" name="date_trx">
                            </div>
                            <div class="col-md-4 due_date">
                                <label for="due_date" class="form-control-label">Tgl Jatuh Tempo</label>
                                <input type="text" class="form-control datepicker required" id="due_date" name="due_date">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row-mt-4">
                    <div class="row clearfix">
                        <span id="error"></span>
                        <table class="table table-bordered table-hover tabel-striped tabel-vcenter" id="tab_logic">
                            <thead>
                                <th></th>
                                <th style='width:30%'>Produk</th>
                                <th style='width:15%'>Deskripsi</th>
                                <th style='width:10%'>Qty</th>
                                <th style='width:15%'>Harga Satuan</th>
                                <th style='width:10%'>Pajak</th>
                                <th style='width:25%'>Jumlah</th>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-circle btn-outline-success add" title="Tambah Item">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <select name="product[1]" id="1productselect2" class="form-control product required" data-placeholder="Pilih" style="width: 100%">
                                            {!! $product !!}
                                        </select>
                                    </td>
                                    <td>
                                        <textarea name="desc[1]" id="1desc" rows="1" class="form-control desc"></textarea>
                                    </td>
                                    <td>
                                        <input type="number" name="qty[1]" id="1qty" class="form-control required qty">
                                    </td>
                                    <td>
                                        <input type="text" name="price[1]" id="1price" placeholder='0' class="form-control tanpa-rupiah text-right price required"/>
                                    </td>
                                    <td>
                                        <select name="tax_form[1]" id="1tax_formselect2" class="form-control tax_form" data-placeholder="Pilih" style="width: 100%">
                                            {!! $tax !!}
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="total[1]" id="1total" placeholder='0' class="form-control tanpa-rupiah text-right total required"/>
                                    </td>
                                </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="6" class="font-weight-bold text-right">Sub Total</td>
                                    <td>
                                        <input type="text" name='sub_total' placeholder='0' class="form-control tanpa-rupiah text-right" id="sub_total" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <label for="potongan" class="form-control-label font-weight-bold">Potongan %</label>
                                        <select name="potongan" id="potongan" class="form-control" data-placeholder="Pilih" style="width: 100%">
                                            {!! $allAccount !!}
                                        </select>                           
                                    </td>
                                    <td>
                                        <input type="text" name='potongan_value' id ='potongan_value' placeholder='0' class="form-control tanpa-rupiah text-right  mt-3" readonly max='100'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <label for="biaya" class="form-control-label font-weight-bold">Biaya Tambahan</label>
                                        <select name="biaya" id="biaya" class="form-control" data-placeholder="Pilih" style="width: 100%">
                                            {!! $beban !!}
                                        </select>                           
                                    </td>
                                    <td>
                                        <input type="text" name='biaya_value' id ='biaya_value' placeholder='0' class="form-control tanpa-rupiah text-right mt-3" readonly/>
                                    </td>
                                </tr>
                                <tr class="table-warning">
                                    <td colspan="6" class="font-weight-bold text-right">Total</td>
                                    <td>
                                        <input type="text" name='total_amount' placeholder='0' class="form-control tanpa-rupiah text-right" id="total_amount" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="7">
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
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-md-12 text-center">
                        <a href="{{ route('admin.accounting.purchase') }}" class="btn btn-danger"><span class="fa fa-close"></span> Kembali</a>
                        <button type="submit" class="btn btn-success"><span class="fa fa-check"></span> Simpan Transaksi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        jQuery(function(){
            //select2
            $('#potongan').select2({
                placeholder: "Pilih",
                allowClear: true
            });

            //select2
            $('#biaya').select2({
                placeholder: "Pilih",
                allowClear: true
            });

            $('#supplier').select2({
                placeholder: "Pilih",
            });

            initSelect2();

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
            // end file handler

            $(document).ready(function(){
                // table logic handler
                var i = 1;

                $(document).on('click', '.add', function() {
                    var html = '';
                    html += '<tr>';
                    html += '<td><button type="button" name="remove" class="btn btn-sm btn-circle btn-outline-danger remove" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
                    html += '<td>';
                    html += '<select name="product['+(i+1)+']" id="'+(i+1)+'productselect2" class="form-control product required" data-placeholder="Pilih" style="width: 100%">';
                    html += '{!! $product !!}';
                    html += '</select>';
                    html += '</td>';
                    html += '<td>';
                    html += '<textarea name="desc['+(i+1)+']" id="'+(i+1)+'desc" rows="1" class="form-control desc"></textarea>';
                    html += '</td>';
                    html += '<td>';
                    html += '<input type="number" name="qty['+(i+1)+']" id="'+(i+1)+'qty" class="form-control required qty">';
                    html += '</td>';
                    html += '<td>';
                    html += '<input type="text" name="price['+(1+1)+']" id="'+(i+1)+'price" placeholder="0" class="form-control tanpa-rupiah text-right price required"/>';
                    html += '</td>';
                    html += '<td>';
                    html += '<select name="tax_form['+(i+1)+']" id="'+(i+1)+'tax_formselect2" class="form-control tax_form" data-placeholder="Pilih" style="width: 100%">';
                    html += '{!! $tax !!}';
                    html += '</select>';
                    html += '</td>';
                    html += '<td>';
                    html += '<input type="text" name="total['+(i+1)+']" id="'+(i+1)+'total" placeholder="0" class="form-control tanpa-rupiah text-right total required"/>';
                    html += '</td>';
                    html += '</tr>';
                   
                    
                    $('#tab_logic > tbody').append(html);

                    i++;
                    initSelect2();
                    updateIds();
                });

                $(document).on('click', '.remove', function() {
                    $(this).closest('tr').remove();
                    updateIds();
                });

                $("#tab_logic").on('change', '.product',function () {
                    var element = $(this).closest('tr');
                    var price = $(this).find(':selected').attr('data-purchase-price');
                    
                    element.find(".qty").val(1);
                    // element.find(".price").val(formatRupiah(price, '{{ setting()->get('currency_symbol') }}')).change();
                    element.find(".price").val(price).change();

                    calc();
                });

                $("#tab_logic").on('change', '.tax_form',function () {
                    calc();
                });

                $('#tab_logic tbody').on('keyup change mouseup', function() {
                    calc();
                });

                $('#tab_logic tfoot #potongan_value').on('keyup change mouseup', function() {
                    calc();
                });

                $('#tab_logic tfoot #biaya_value').on('keyup change mouseup', function() {
                    calc();
                });

                $('#potongan').on('change', function(){
                    let potongan = $(this).val();
                    if(potongan != ''){
                        $('#potongan_value').prop('readonly' ,false);
                    }else{
                        $('#potongan_value').val(0);
                        $('#potongan_value').prop('readonly', true);
                    }
                });

                $('#biaya').on('change', function(){
                    let potongan = $(this).val();
                    if(potongan != ''){
                        $('#biaya_value').prop('readonly' ,false);
                    }else{
                        $('#biaya_value').val(0);
                        $('#biaya_value').prop('readonly', true);
                    }
                });

                updateIds()
               // end table logic handler
            });

            // validation
            var validator = jQuery('.js-validation-bootstrap').validate({
                ignore: [],
                errorClass: 'invalid-feedback animated fadeInDown',
                errorElement: 'div',
                errorPlacement: function(error, e){
                    jQuery(e).parents('.form-group > div').append(error);
                    jQuery(e).parents('td').append(error);
                },
                highlight: function(e) {
                    jQuery(e).closest('form-group').removeClass('is-invalid').addClass('is-invalid');
                    jQuery(e).closest('td').removeClass('is-invalid').addClass('is-invalid');
                },
                success: function(e) {
                    jQuery(e).closest('.form-group').removeClass('is-invalid');
                    jQuery(e).closest('td').removeClass('is-invalid');
                    jQuery(e).remove();
                },
                checkForm: function() {
                    this.prepareForm();
                    for (var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++) {
                        if (this.findByName(elements[i].name).length != undefined && this.findByName(elements[i].name).length > 1) {
                            for (var cnt = 0; cnt < this.findByName(elements[i].name).length; cnt++) {
                                this.check(this.findByName(elements[i].name)[cnt]);
                            }
                        } else {
                            this.check(elements[i]);
                        }
                    }
                    return this.valid();
                },
                submitHandler: function(form) {
                    var data = new FormData(form);
                    $.ajax({
                        type: "POST",
                        url:"{{ route('admin.accounting.purchase.store') }}",
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

            function updateIds() {
                $('#tab_logic tbody tr').each(function(i, element) {
                    var html = $(this).html();
                    if (html != '') {
                        $(this).find('.product').attr('name', 'product['+(i+1)+']');
                        $(this).find('.desc').attr('name', 'acc_desc['+(i+1)+']');
                        $(this).find('.qty').attr('name', 'qty['+(i+1)+']');
                        $(this).find('.tax_form').attr('name', 'tax_form['+(i+1)+']');
                        $(this).find('.price').attr('name', 'price['+(i+1)+']');
                        $(this).find('.total').attr('name', 'total['+(i+1)+']');
                    }
                });

                validator.resetForm();
            }

            function initSelect2(){
                // select2
                $('.product').select2({
                    placeholder: "Pilih"
                });

                $('.tax_form').select2({
                    placeholder: "Pilih",
                    allowClear: true
                });
                // end select2
            }

            function formatBytes(bytes, decimals = 2) {
                if (bytes === 0) return '0 Bytes';

                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

                const i = Math.floor(Math.log(bytes) / Math.log(k));

                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            }

            function calc() {
                $('#tab_logic tbody tr').each(function(i, element) {
                    var html = $(this).html();
                    if (html != '') {
                        var qty = $(this).find('.qty').val();
                        var price = $(this).find('.price').val();

                        //sum tax
                        var tax = $(this).find('.tax_form > :selected').attr('data-tax');
                        if(isNaN(tax)){
                            tax = 0;
                        }else{
                            tax = tax / 100;
                        }

                        var total = qty * toNumber(price);
                        tax_sum = parseInt((total * tax));
                        var jumlah = total + tax_sum;

                        $(this).find('.total').val(formatRupiah(jumlah, '{{ setting()->get('currency_symbol') }}'));
                        calc_total();
                    }
                });
            }

            function calc_total(){
                total = 0;
                $('.total').each(function() {
                    total += parseInt(toNumber($(this).val()));
                });

                $('#sub_total').val(formatRupiah(total, '{{ setting()->get('currency_symbol') }}'));

                var potongan = $('#potongan_value').val();
                var biaya = parseInt(toNumber($('#biaya_value').val()));

                if(isNaN(potongan)){
                    potongan = 0;
                }else{
                    potongan = total * potongan / 100;
                }

                if(isNaN(biaya)){
                    biaya = 0;
                }
                
                total_amount = total - potongan + biaya;
                $('#total_amount').val(formatRupiah(total_amount, '{{ setting()->get('currency_symbol') }}'));
            }
        })
    </script>
@endsection