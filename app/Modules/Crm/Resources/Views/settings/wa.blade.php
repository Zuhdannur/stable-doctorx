@extends('backend.layouts.app')

@section('title', app_name().' | '.__('crm::menus.settings.whatsapp'))

@section('content')
    <div class="block" id="">

        {{-- content header --}}
        <div class="block-header block-header-default">
            <h3 class="block-title">
                @lang('crm::labels.settings.wa.main')
                <small class="text-muted">
                    &nbsp;
                    @lang('crm::labels.settings.wa.vendor')
                </small>
            </h3>
        </div>

        {{-- content block --}}
        <div class="block" id="my-block2">
            <ul class="nav nav-tabs nav-tabs-block align-items-center" data-toggle="tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#tab-woo" name="woo">Woo Wandroid</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab-twilio" name="twillio">Twillio</a>
                </li>
            </ul>

            <div class="block-content block-content-full tab-content overflow-hidden ribbon ribbon-primary">
                <div class="tab-pane fade fade-right show active" id="tab-woo" role="tabpanel">
                    <form method="post" class="js-validation-bootstrap" id="formWoo" autocomplete="off">
                        <div class="row-mt-4">
                            <div class="col">
                                <div class="form-group row">
                                    <span class="text-danger">Note : Isikan Data Sesuai dengan data pada vendor !!</span>
                                </div>

                                <div class="form-group row">
                                    <label for="cs_id" class="col-md-2 form-control-label">CS ID </label>
                                    <div class="col-md-4">
                                        <input type="text" name="cs_id" id="name" class="form-control" value="{{ isset($wooConfig->cs_id) ? $wooConfig->cs_id : ''}}">
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <div class="col-md-1">
                                        {{ form_cancel(route('admin.dashboard'), __('buttons.general.cancel')) }}
                                    </div>
                                    <div class="col-md-5 text-right">
                                        {{ form_submit(__('buttons.general.crud.save')) }}
                                    </div>
                
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade fade-right show" id="tab-twilio" role="tabpanel">
                    <form method="post" class="js-validation-bootstrap" id="formTwilio" autocomplete="off">
                        <div class="row-mt-4">
                            <div class="col">
                                <div class="form-group row">
                                    <span class="text-danger">Note : Isikan Data Sesuai dengan data pada vendor !!</span>
                                </div>
                                <div class="form-group row">
                                    <label for="sid" class="col-md-2 form-control-label">SID </label>
                                    <div class="col-md-4">
                                        <input type="text" name="sid" id="sid" class="form-control" value="{{ isset($twillioConfig->sid) ? $twillioConfig->sid : ''}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="token" class="col-md-2 form-control-label">Token </label>
                                    <div class="col-md-4">
                                        <input type="text" name="token" id="token" class="form-control" value="{{ isset($twillioConfig->sid) ? $twillioConfig->token : ''}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="senders" class="col-md-2 form-control-label">Wa Phone Senders</label>
                                    <div class="col-md-4">
                                        <input type="text" name="senders" id="senders" class="form-control" value="{{ isset($twillioConfig->senders) ? $twillioConfig->senders : ''}}">
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <div class="col-md-1">
                                        {{ form_cancel(route('admin.dashboard'), __('buttons.general.cancel')) }}
                                    </div>
                                    <div class="col-md-5 text-right">
                                        {{ form_submit(__('buttons.general.crud.save')) }}
                                    </div>
                
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="clear-fix"></div>
    <div class="clear-fix"></div>
    
    <div class="block" id="my-block3">
        {{-- content header --}}
        <div class="block-header block-header-default">
            <h3 class="block-title">
                @lang('crm::labels.settings.wa.main')
                <small class="text-muted">
                    &nbsp;
                    @lang('crm::labels.settings.wa.msg')
                </small>
            </h3>
        </div>

        {{-- content body --}}
        <div class="block-content">
            <form method="post" class="js-validation-bootstrap" id="formGeneral" autocomplete="off">
                <div class="row-mt-4">
                    <div class="col">
                        <div class="form-group row">
                            <label for="is_vendor" class="col-md-2 form-control-label">Vendor Aktif</label>
                            <div class="col-md-4">
                                <select name="is_vendor" id="is_vendor" class="form-control" width="100%">
                                   {!! $list !!}
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="msg" class="col-md-2 form-control-label">Pesan Ultah</label>
                            <div class="col-md-4">
                            <textarea name="msg" id="msg" cols="30" rows="5" class="form-control" value="{{ $msg }}"> {{ $msg }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="msg_reminder" class="col-md-2 form-control-label">Pesan Reminder Booking</label>
                            <div class="col-md-4">
                            <textarea name="msg_reminder" id="msg_reminder" cols="30" rows="5" class="form-control" value="{{ $msgReminder }}"> {{ $msgReminder }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-1">
                                {{ form_cancel(route('admin.crm.settings.membership'), __('buttons.general.cancel')) }}
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
            var BeFormValidation = function() {
                var initValidationBootstrap = function(){
                    jQuery('#formWoo').validate({
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
                            'cs_id' :{
                                required: true
                            },
                        },
                        submitHandler: function(form){
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('admin.crm.settings.wa.woo') }}",
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
                                            window.location = '{{ route('admin.crm.settings.wa') }}';
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

            var BeFormValidation2 = function() {
                var initValidationBootstrap = function(){
                    jQuery('#formTwilio').validate({
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
                            'sid' :{
                                required: true
                            },
                            'token' :{
                                required: true
                            },
                            'senders' :{
                                required: true
                            },
                        },
                        submitHandler: function(form){
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('admin.crm.settings.wa.twillio') }}",
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
                                            window.location = '{{ route('admin.crm.settings.wa') }}';
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

            var BeFormValidation3 = function() {
                var initValidationBootstrap = function(){
                    jQuery('#formGeneral').validate({
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
                            'msg' :{
                                required: true
                            },
                        },
                        submitHandler: function(form){
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('admin.crm.settings.wa.msg') }}",
                                data: $(form).serialize(),
                                beforeSend: function(xhr){
                                    Codebase.blocks('#my-block3', 'state_loading');
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

                                    Codebase.blocks('#my-block3', 'state_normal');
                                },
                                success: function(result){
                                    if(result.status){
                                        $.notify({
                                            message: result.message,
                                            type: 'success'
                                        });

                                        setTimeout(function(){
                                            window.location = '{{ route('admin.crm.settings.wa') }}';
                                        }, 2000); 
                                    }else{
                                        $.alert({
                                            title: 'Error',
                                            icon: 'fa fa-warning',
                                            type: 'orange',
                                            content: result.message,
                                        });
                                    }
                                    Codebase.blocks('#my-block3', 'state_normal');
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
            jQuery(function(){ BeFormValidation2.init(); });
            jQuery(function(){ BeFormValidation3.init(); });
        })
    </script>
@endsection