@extends('backend.layouts.app')

@section('title', app_name().' | '.__('product::menus.product.opname'))

@section('content')
    <div class="block" id="my-block2">
        {{-- content header --}}
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('product::labels.product.opname') <small class="text-muted">@lang('product::labels.product.create')</small></h3>
        </div>

        {{-- content body --}}
        <div class="block-content">
            <form class="js-validation-bootstrap" method="post" id="formGeneral" autocomplete="off">
                <div class="row-mt-4">
                    <div class="col">
                        <div class="form-froup row">
                            <label class="col-md-2">Produk</label>
                            <div class="col-md-10">
                                <label for="id">: {{ $product->code.' - '.$product->name}}</label>
                                <input type="hidden" name="id" value="{{ $product->id }}">
                            </div>
                        </div>

                        <div class="form-froup row">
                            <label class="col-md-2">Kategory</label>
                            <div class="col-md-10">
                                <label>: {{ $product->category->name }}</label>
                            </div>
                        </div>

                        <div class="form-froup row">
                            <label class="col-md-2">Stock Tercatat</label>
                            <div class="col-md-10">
                                <label>: {{ $product->quantity }}</label>
                                <input type="hidden" name="qty" id="qty" value="{{ $product->quantity }}">
                            </div>
                        </div>
                        <div class="form-group row mt-2">
                            <label for="acc_name" class="col-md-2 form-control-label">Akun Penyesuaian</label>
                            <div class="col-md-6">
                                <select name="acc_opname" id="acc_opname" class="form-control"  data-placeholder="Pilih" style="width: 100%">
                                    {!! $account !!}
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="acc_name" class="col-md-2 form-control-label">Kuantitas Sekarang</label>
                            <div class="col-md-2">
                                <input type="number" name="qty_now" id="qty_now" class="form-control">
                            </div>
                            <label for="acc_name" class="col-md-2 form-control-label">Jumlah Penyesuaian</label>
                            <div class="col-md-2">
                                <input type="number" name="penyesuaian" id="penyesuaian" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-1">
                        {{ form_cancel(route('admin.accounting.account'), __('buttons.general.cancel')) }}
                    </div>

                    <div class="col-md-7 text-right">
                        {{ form_submit(__('buttons.general.crud.create')) }}
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        jQuery(function(){
            $('#acc_opname').select2({
                placeholder: 'Pilih'
            })

            $(document).ready(function(){
                $('#qty_now').on('keyup change mouseup', function() {
                    calc();
                });
            })

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
                            'acc_opname' :{
                                required: true
                            },
                            'qty_now' :{
                                required: true
                            }
                        },
                        submitHandler: function(form){
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('admin.product.storeOpname') }}",
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
                                            window.location = '{{ route('admin.product.index') }}';
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

            function calc(){
                qty = toNumber( $('#qty').val() );
                qty_now = parseInt( $('#qty_now').val() );

                penyesuaian = qty_now - qty;
                $('#penyesuaian').val(penyesuaian);
            }
        })
    </script>
@endsection