@extends('backend.layouts.app')

@section('title', app_name(). ' | '.__('accounting::menus.settings.tax'))

@section('content')
    <div class="block" id="my-block2">

        {{-- content header --}}
        <div class="block-header block-header-default">
            <h3 class="block-title">
                @lang('accounting::labels.settings.tax.main')
                <small class="text-muted">
                    &nbsp;
                    @lang('accounting::labels.settings.tax.create')
                </small>
            </h3>
        </div>

        {{-- content body --}}
        <div class="block-content">
            <form method="post" class="js-validation-bootstrap" id="formGeneral" autocomplete="off">
                @csrf
                <div class="row-mt-4">
                    <div class="col">
                        <div class="form-group row">
                            <small class="text-danger">Note : Data yang disimpan tidak akan bisa dihapus dan hanya bisa di edit nama dan persentasenya saja !!</small>
                        </div>
                        <div class="form-group row">
                            <label for="tax_name" class="col-md-2 form-control-label">Nama Pajak</label>
                            <div class="col-md-4">
                                <input type="text" name="tax_name" id="tax_name" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="percentage" class="col-md-2 form-control-label">Persentase Pajak</label>
                            <div class="col-md-4">
                                <input type="number" name="percentage" id="percentage" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tax_sales" class="col-md-2 form-control-label">Akun Pajak Keluaran</label>
                            <div class="col-md-4">
                                <select class="form-control select2" id="tax_sales" name="tax_sales" style="width: 100%" data-placeholder="Pilih">
                                    {!! $accountList !!}
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tax_purchase" class="col-md-2 form-control-label">Akun Pajak Masukan</label>
                            <div class="col-md-4">
                                <select class="form-control select2" id="tax_purchase" name="tax_purchase" style="width: 100%" data-placeholder="Pilih">
                                    {!! $accountList !!}
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-1">
                                {{ form_cancel(route('admin.accounting.settings.tax'), __('buttons.general.cancel')) }}
                            </div>
                            <div class="col-md-5 text-right">
                                {{ form_submit(__('buttons.general.crud.create')) }}
                            </div>
        
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <script>
        jQuery(function(){

            $('.select2').select2({
                placeholder: 'Pilih'
            });

            var BeFormValidation = function() {
                var initValidationBootstrap = function(){
                    jQuery('.js-validation-bootstrap').validate({
                        ignore: [],
                        errorClass: 'invalid-feedback animated fadeInDown',
                        errorElement: 'div',
                        errorPlacement: function(error, e){
                            jQuery(e).parents('.form-group > div').append(error);
                        },
                        highlight: function(e){
                            jQuery(e).closest('form-group').removeClass('is-invalid').addClass('is-invalid');
                        },
                        success: function(e){
                            jQuery(e).closest('.form-group').removeClass('is-invalid');
                            jQuery(e).remove();
                        },
                        rules:{
                            'tax_name' :{
                                required: true
                            },
                            'percentage':{
                                required: true
                            },
                            'tax_sales':{
                                required: true
                            },
                            'tax_purchase':{
                                required: true
                            },
                        },
                        submitHandler: function(form){
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('admin.accounting.settings.tax.save') }}",
                                data: $(form).serialize(),
                                beforeSend: function(xhr){
                                    Codebase.blocks('#my-block2', 'state_loading');
                                },
                                error: function(x, status, error){
                                    if(x.status === 422 ){
                                        var errors = x.responseJSON;
                                        var errorsHtml = '';

                                        $.each(errors['errors'], function(index, value){
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
                                    }else{
                                        var errors = x.responseJSON;
                                        $.alert({
                                            title: x.status + ': ' + error,
                                            icon: 'fa fa-warning',
                                            type: 'red',
                                            content: errors.messages,
                                        });
                                    }

                                    Codebase.blocks('#my-block2', 'state_normal');
                                },
                                success: function(result){
                                    if(result.status){
                                        $.notify({
                                            message: result.message,
                                            type: 'success'
                                        });

                                        setTimeout(function(){
                                            window.location = '{{ route('admin.accounting.settings.tax') }}';
                                        }, 2000); 
                                    }else{
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
                };

                return {
                    init: function () {
                        // Init Bootstrap Forms Validation
                        initValidationBootstrap();

                        // Init Validation on Select2 change
                        jQuery('.js-select2').on('change', function(){
                            jQuery(this).valid();
                        });
                    }
                };
            }();

            // Initialize when page loads
            jQuery(function(){ BeFormValidation.init(); });

        })
    </script>
@endsection
