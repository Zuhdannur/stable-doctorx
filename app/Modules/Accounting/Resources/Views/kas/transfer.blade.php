@extends('backend.layouts.app')

@section('title', app_name(). ' | '. __('accounting::menus.kas.transfer'))

@section('content')
    <div class="block" id="my-block2">

        {{-- content header --}}
        <div class="block-header block-header-default">
            <h3 class="block-title">
                @lang('accounting::labels.cash.cash')
                <small class="text-muted">
                    &nbsp;
                    @lang('accounting::labels.cash.transfer')
                </small>
            </h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
            </div>
        </div>

        {{-- content form --}}
        <div class="block-content block-content-full">
            <form method="post" class="js-validation-bootstrap" id="receipt_form" autocomplete="off" enctype="multipart/form-data">
                <div class="row-mt-4">
                    <div class="col">
                        <div class="form-group row">
                            <label for="acc_cash_id" class="col-md-12 form-control-label">Transfer dari : {{ $account->account_name." ($account->account_code)" }}</label>
                            <input type="hidden" name="acc_cash" value="{{ $account->id }}">
                        </div>
                    </div>
                </div>

                <div class="row-mt-4">
                    <div class="col">
                        <div class="form-group row">
                            <div class="col-4">
                                <label for="acc_trf" class="form-control-label">Transfer Ke</label>
                                <select name="acc_trf" id="acc_trf" class="form-control required" data-placeholder="Pilih" style="width: 100%">
                                    {!! $cashAccountList !!}
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="date_trx" class="form-control-label">Tanggal Transaksi</label>
                                <input type="text" class="form-control datepicker" id="date_trx" name="date_trx">
                            </div>
                            <div class="col-4">
                                <label for="total" class="form-control-label">Jumlah</label>
                                <input type="text" name='total' placeholder='0' class="form-control tanpa-rupiah text-right required"/>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Detail Transaction Transfer --}}
                <div class="row-mt-4">
                    <div class="col">
                        <div class="form-group row">
                            <div class="col-8">
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
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-md-12 text-center">
                        <a href="{{ route('admin.accounting.cash') }}" class="btn btn-danger"><span class="fa fa-close"></span> Kembali</a>
                        <button type="submit" class="btn btn-success"><span class="fa fa-check"></span> Simpan Transaksi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        jQuery(function() {

            // select2
            $('#acc_trf').select2({
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

            var validator = jQuery('.js-validation-bootstrap').validate({
                ignore: [],
                errorClass: 'invalid-feedback animated fadeInDown',
                errorElement: 'div',
                errorPlacement: function(error, e) {
                    jQuery(e).parents('.form-group > div').append(error);
                },
                highlight: function(e) {
                    jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
                },
                success: function(e) {
                    jQuery(e).closest('.form-group').removeClass('is-invalid');
                    jQuery(e).remove();
                },
                submitHandler: function(form){
                    var data = new FormData(form);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.accounting.cash.store_transfer') }}",
                        data: data,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        beforeSend: function(xhr) {
                            Codebase.blocks('.my-block', 'state_loading');
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

                                Codebase.blocks('.my-block', 'state_normal');
                        },
                        success: function(data) {
                            if (data.status) {
                                Swal.fire({
                                    title: data.message,
                                    type: 'success',
                                    showCancelButton: false,
                                    showConfirmButton: true,
                                }).then((result) => {
                                    if (result.value) {
                                        window.location = '{{ route('admin.accounting.cash') }}';
                                    }
                                })
                            } else {
                                Swal.fire({
                                    title: data.message,
                                    type: 'error',
                                    showCancelButton: false,
                                    showConfirmButton: true,
                                })
                            }

                            Codebase.blocks('.my-block', 'state_normal');
                        }
                    });
                    return false; // required to block normal submit since you used ajax
                }
            })
        });

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';

            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

            const i = Math.floor(Math.log(bytes) / Math.log(k));

            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

    </script>
@endsection