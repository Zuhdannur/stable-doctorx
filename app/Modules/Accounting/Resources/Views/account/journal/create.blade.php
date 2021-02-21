@extends('backend.layouts.app')

@section('title', app_name().' | '.__('accounting::menus.journal.title'))

@section('content')
    <div class="block" id="my-block2">

        {{-- content header --}}
        <div class="block-header block-header-default">
            <h3 class="block-title">
                @lang('accounting::labels.journal.create')
            </h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                <a href="{{ url()->previous() }}" class="btn-block-option">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        
        {{-- content form --}}
        <div class="block-content block-content-full">
            <form method="post" class="js-validation-bootstrap" id="journal_form" autocomplete="off" enctype="multipart/form-data">
                <div class="row-mt-4">
                    <div class="col">
                        <div class="form-group row">
                            <div class="col-4">
                                <label for="date_trx" class="form-control-label">Tanggal Transaksi</label>
                                <input type="text" class="form-control datepicker" id="date_trx" name="date_trx">
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
                                <th style='width:35%'>Akun</th>
                                <th style='width:20%'>Deskripsi</th>
                                <th style='width:15%'>Type</th>
                                <th style='width:25%'>Nominal</th>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-circle btn-outline-success add" title="Tambah Item">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <select name="acc[1]" id="1accselect2" class="form-control acc required" data-placeholder="Pilih" style="width: 100%">
                                            {!! $account !!}
                                        </select>
                                    </td>
                                    <td>
                                        <textarea name="acc_desc[1]" id="1acc_desc" rows="1" class="form-control acc_desc"></textarea>
                                    </td>
                                    <td>
                                        <select name="type[1]" id="1typeselect2" class="form-control type required" data-placeholder="Pilih" style="width: 100%">
                                            {!! $type !!}
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="total[1]" id="1total" placeholder='0' class="form-control tanpa-rupiah text-right total required"/>
                                    </td>
                                </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="4" class="font-weight-bold text-right">Total Debit</td>
                                    <td>
                                        <input type="text" name='debit' placeholder='0' class="form-control tanpa-rupiah text-right" id="debit" readonly/>
                                    </td>
                                </tr>
                                <tr class="table-warning">
                                    <td colspan="4" class="font-weight-bold text-right">Total Kredit</td>
                                    <td>
                                        <input type="text" name='kredit' placeholder='0' class="form-control tanpa-rupiah text-right" id="kredit" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5">
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
                        <a href="{{ route('admin.accounting.account') }}" class="btn btn-danger"><span class="fa fa-close"></span> Kembali</a>
                        <button type="submit" class="btn btn-success"><span class="fa fa-check"></span> Simpan Transaksi</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        jQuery(function(){
            initSelect2();
                // submit handler
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
                submitHandler: function(form){
                    let kredit = $('#kredit').val();
                    let debit = $('#debit').val();

                    if(kredit != debit){
                        $.alert({
                            title: 'Error',
                            icon: 'fa fa-warning',
                            type: 'orange',
                            content: 'Jumlah Debit dan Kredit tidak sama !!',
                        });

                        return false;
                    }

                    var data = new FormData(form);
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('admin.accounting.account.journal.save') }}",
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
                                    window.location = '{{ route('admin.accounting.account') }}';
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

            updateIds();

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
                    html += '<select name="acc['+(i+1)+']" id="'+(i+1)+'accselect2" class="form-control acc required" data-placeholder="Pilih" style="width: 100%">';
                    html += '{!! $account !!}';
                    html += '</select>';
                    html += '</td>';
                    html += '<td>';
                    html += '<textarea name="acc_desc['+(i+1)+']" id="'+(i+1)+'acc_desc" rows="1" class="form-control acc_desc"></textarea>';
                    html += '</td>';
                    html += '<td>';
                    html += '<select name="type['+(i+1)+']" id="'+(i+1)+'typeselect2" class="form-control type" data-placeholder="Pilih" style="width: 100%">';
                    html += '{!! $type !!}'
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


                $('#tab_logic tbody').on('keyup change mouseup', function() {
                    calc_total();
                });

                // end table logic handler

            });

            function calc_total(){
                debit = 0;
                kredit = 0;

                $('#tab_logic tbody tr').each(function(i, element) {
                    var html = $(this).html();
                    if (html != '') {
                        type =  $('select[name = "type['+(i+1)+']"]').val();
                        total =  $('input[name = "total['+(i+1)+']"]').val();
                        if(type == 1){
                            debit = parseInt(debit) + parseInt(toNumber(total)) ;
                        }else if(type == 2){
                            kredit =  parseInt(kredit) + parseInt(toNumber(total));
                        }
                    }
                });

                $('#kredit').val(formatRupiah(kredit, '{{ setting()->get('currency_symbol') }}'));
                $('#debit').val(formatRupiah(debit, '{{ setting()->get('currency_symbol') }}'));
            }

            function updateIds() {
                $('#tab_logic tbody tr').each(function(i, element) {
                    var html = $(this).html();
                    if (html != '') {
                        $(this).find('.acc').attr('name', 'acc['+(i+1)+']');
                        $(this).find('.type').attr('name', 'type['+(i+1)+']');
                        $(this).find('.acc_desc').attr('name', 'acc_desc['+(i+1)+']');
                        $(this).find('.total').attr('name', 'total['+(i+1)+']');
                    }
                });

                validator.resetForm();
            }

            function initSelect2(){
                // select2
                $('.acc').select2({
                    placeholder: "Pilih"
                });

                $('.type').select2({
                    placeholder: "Pilih",
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

        })
    </script>
@endsection