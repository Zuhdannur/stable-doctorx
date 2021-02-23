<div class="block" id="my-block">
    {{-- content header --}}
    <div class="block-header block-header-default">
        <h3 class="block-title">
            @lang('crm::labels.radeem.data_patient')
        </h3>
    </div>

    {{-- content body --}}
    <div class="block-content">
        <div class="row-mt-4">
            <div class="row">
                <div class="col-md-4">
                            <div class="form-group row">
                                <label for="pid" class="col-md-6 form-control-label">Patient Unique ID</label>
                                <div class="col-md-6">
                                    <p class="text-muted">: {{ $patient->patient_unique_id}}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pid" class="col-md-6 form-control-label">@lang('crm::labels.radeem.patient_name')</label>
                                <div class="col-md-6">
                                    <p class="text-muted">: {{ $patient->patient_name}}</p>
                                </div>
                            </div>
                </div>
                <div class="col-md-4">
                            <div class="form-group row">
                                <label for="pid" class="col-md-6 form-control-label">@lang('crm::labels.radeem.dob')</label>
                                <div class="col-md-6">
                                    <p class="text-muted">: {{ date('d - m - Y',strtotime($patient->dob))}}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pid" class="col-md-6 form-control-label">@lang('crm::labels.radeem.phone_number')</label>
                                <div class="col-md-6">
                                    <p class="text-muted">: {{ $patient->phone_number }}</p>
                                </div>
                            </div>
                </div>
                <div class="col-md-4">
                            <div class="form-group row">
                                <label for="pid" class="col-md-6 form-control-label">@lang('crm::labels.radeem.membership')</label>
                                <div class="col-md-6">
                                    <p class="text-muted">: {{ $patient->membership->ms_membership->name }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pid" class="col-md-6 form-control-label">@lang('crm::labels.radeem.point')</label>
                                <div class="col-md-6">
                                    <p class="text-muted">: {{ number_format($patient->membership->total_point, 0, ',' ,'.') }}</p>
                                </div>
                            </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="block" id="my-block">

    {{-- content header --}}
    <div class="block-header block-header-default">
        <h3 class="block-title">
            @lang('crm::labels.radeem.form')
        </h3>
    </div>

    {{-- content body --}}
    <div class="block-content">
        <div class="row-mt-4">
            <div class="col-md-8">
                <form method="post" class="js-validation-bootstrap" id="formRadeem" autocomplete="off">
                    <input type="hidden" name="id" value="{{$patient->membership->id}}">
                    <div class="form-group row">
                        <label for="item_radeem" class="col-md-4 form-control-label">Item Radeems</label>
                        <div class="col-md-8">
                            <select name="item_radeem" id="item_radeem" class="form-control" width="100%">
                                {!! $radeem !!}
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="point" class="col-md-4 form-control-label">Point</label>
                        <div class="col-md-3">
                            <input type="text" name="point" id="point" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nominal" class="col-md-4 form-control-label">Nominal Penukaran</label>
                        <div class="col-md-8">
                        <input type="text" name="nominal" id="nominal" class="form-control text-right tanpa-rupiah" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="item_ammount" class="col-md-4 form-control-label">Jumlah Item</label>
                        <div class="col-md-3">
                        <input type="number" name="item_ammount" id="item_ammount" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="total_point" class="col-md-4 form-control-label">Total Point</label>
                        <div class="col-md-8">
                        <input type="text" name="total_point" id="total_point" class="form-control text-right tanpa-rupiah" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="total" class="col-md-4 form-control-label">Total Nominal</label>
                        <div class="col-md-8">
                        <input type="text" name="total" id="total" class="form-control text-right tanpa-rupiah" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-1">
                            {{ form_cancel(route('admin.crm.radeem.index'), __('buttons.general.cancel')) }}
                        </div>
                        <div class="col-md-11 text-right">
                            {{ form_submit(__('crm::labels.radeem.radeem')) }}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(function(){
        const const_point = "{{$patient->membership->total_point }}";

        $('#item_radeem').select2({
            placeholder : 'Pilih'
        });

        $(document).ready(function(){
            $('#formRadeem').on('change', '#item_radeem', function(){
                let point = $(this).find(':selected').attr('data-point');
                let gift = $(this).find(':selected').attr('data-gift');

                if(point == '' || point == 'null'){
                    point = 0;
                }

                if(gift == '' || gift == 'null'){
                    gift = 0;
                }
                $('#point').val(point);
                gift = formatRupiah(gift, '{{ setting()->get('currency_symbol') }}');
                $('#nominal').val(gift);

                item_ammount = $('#item_ammount').val();
                if(item_ammount == null || item_ammount == '' || item_ammount < 1){
                    $('#item_ammount').val(1);
                }
                calc();
            })

            $('#item_ammount').on('keyup change mouseup', function(){
                calc();
            })
        });

        var BeFormValidation = function() {
            var initValidationBootstrap = function(){
                jQuery('#formRadeem').validate({
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
                        'item_radeem':{
                            required: true,
                        },
                        'item_ammount':{
                            required: true,
                            min: 1
                        },
                    },
                    submitHandler: function(form){
                        let point = toNumber($('#point').val());
                        let item = toNumber($('#item_ammount').val());
                        let is_total = parseInt(point * item);

                        if(is_total > const_point){

                            $.alert({
                                title: "Kesalahan",
                                icon: 'fa fa-warning',
                                type: 'red',
                                content: "Point Pasien Tidak Mencukupi !!!",
                            });

                            return false;
                        }

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('admin.crm.radeem.save') }}",
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
                                        window.location = '{{ route('admin.crm.radeem.index') }}';
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
            let total_point = parseInt($('#point').val()) * parseInt($('#item_ammount').val());
            if(isNaN(total_point) || total_point == null){
                total_point = 0;
            }
            $('#total_point').val(total_point);

            let item_point = parseInt($('#item_ammount').val());
            let total_nominal = item_point * toNumber($('#nominal').val());

            
            if(isNaN(total_nominal) || total_nominal == null){
                total_nominal = 0;
            }else if(total_nominal > 100000000000000000000){
                $.alert({
                    title: "Kesalahan",
                    icon: 'fa fa-warning',
                    type: 'red',
                    content: "Nominal Mencapai Batas Maksimal, Silahkan input dengan nilai lebih kecil !!!",
                });
                $('#item_ammount').val(0);
                total_nominal = 0;
            }
            
            $('#total').val( formatRupiah(total_nominal, '{{ setting()->get('currency_symbol') }}'));

        }
    })
</script>