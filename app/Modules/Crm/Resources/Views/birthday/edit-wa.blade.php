@extends('backend.layouts.app')

@section('title', app_name().' | '.__('crm::menus.birthday'))

@section('content')
<div class="block" id="my-block2">

    {{-- content header --}}
    <div class="block-header block-header-default">
        <h3 class="block-title">
            @lang('crm::menus.birthday')
            <small class="text-muted">
                &nbsp;
                @lang('crm::labels.birthday.edit')
            </small>
        </h3>
    </div>

    {{-- content body --}}
    <div class="block-content">
        <form method="post" class="js-validation-bootstrap" id="formGeneral" autocomplete="off">
            <input type="hidden" name="id" value="{{ $patient->id }}">
            <div class="row-mt-4">
                <div class="col">
                    <div class="form-group row">
                        <label for="name" class="col-md-3 form-control-label">Pasien</label>
                        <div class="col-md-4">
                            <p>: {{ $patient->patient_unique_id.' - '.$patient->patient_name }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-md-3 form-control-label">No. Whatsapp</label>
                        <div class="col-md-4">
                            <input type="text" name="wa_number" id="wa_number" value="{{ $patient->wa_number }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-1">
                            {{ form_cancel(url()->previous(), __('buttons.general.cancel')) }}
                        </div>
                        <div class="col-md-6 text-right">
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
                            'wa_number' :{
                                required: true
                            },
                        },
                        submitHandler: function(form){
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('admin.crm.birthday.storeWa') }}",
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
                                            window.location = '{{ url()->previous() }}';
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