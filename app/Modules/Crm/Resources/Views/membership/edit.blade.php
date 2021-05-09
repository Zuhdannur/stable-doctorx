@extends('backend.layouts.app')

@section('title', app_name().' | '.__('crm::menus.membership'))

@section('content')
<div class="block" id="my-block2">

    {{-- content header --}}
    <div class="block-header block-header-default">
        <h3 class="block-title">
            @lang('crm::labels.membership.main')
            <small class="text-muted">
                &nbsp;
                @lang('crm::labels.membership.edit')
            </small>
        </h3>
    </div>

    {{-- content body --}}
    <div class="block-content">
        <form method="post" class="js-validation-bootstrap" id="formGeneral" autocomplete="off">
            @method('PATCH')
            <input type="hidden" name="id" value="{{ $membership->id}}">
            <div class="row-mt-4">
                <div class="col">
                    <div class="form-group row">
                        <label for="name" class="col-md-3 form-control-label">Pasien</label>
                        <div class="col-md-4">
                            <p>: {{ $patient }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-md-3 form-control-label">Pilih Kategori Membership</label>
                        <div class="col-md-4">
                            <select name="membership" id="membership" class="form-control select2" width="100%">
                                    {!! $membershipList !!}
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-md-3 form-control-label">Pilih Grade</label>
                        <div class="col-md-4">
                            <select name="grade" id="grade" class="form-control select2" width="100%">
                                {!! $grade !!}
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-md-3 form-control-label">Jumlah Point</label>
                        <div class="col-md-4">
                           <input type="number" name="total_point" id="total_point" class="form-control required" value="{{ $membership->total_point}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-1">
                            {{ form_cancel(route('admin.crm.settings.membership'), __('buttons.general.cancel')) }}
                        </div>
                        <div class="col-md-6 text-right">
                            {{ form_submit(__('buttons.general.crud.update')) }}
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
            placeholder : '-Pilih-',
        });

        $('#membership').val("{{ $membership->ms_membership_id}}").trigger('change');

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
                            'membership':{
                                required: true
                            },
                        },
                        submitHandler: function(form){
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('admin.crm.membership.store') }}",
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
                                            window.location = '{{ route('admin.crm.membership.index') }}';
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
