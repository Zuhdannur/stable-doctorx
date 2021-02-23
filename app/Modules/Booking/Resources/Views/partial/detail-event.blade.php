<div class="block-content">
    <form class="form-vertical" method="post" action="{{ route('admin.booking.store') }}" id="formUpdate" autocomplete="off">
        @csrf
        @method('PATCH')
        <input type="hidden" name="id" id="id">
        <input type="hidden" name="type" id="type">

        <div class="form-row" id='form-logic'>
            <div class="form-group col-md-12">
                <label class="col-md-4">PID</label>:&nbsp;
                <span class="col-md-8" id="pid"></span>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-4">Nama Pasien</label>:&nbsp;
                <span class="col-md-8" id="patient_name"></span>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-4">Tanggal</label>:&nbsp;
                <span class="col-md-8" id="booking_date"></span>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-4">Waktu Mulai</label>:&nbsp;
                <span class="col-md-8" id="booking_time"></span>
            </div>
            <div class="form-group col-md-12 d-none" id="end_time_group">
                <label class="col-md-4">Waktu Selesai</label>:&nbsp;
                <span class="col-md-8" id="end_time"></span>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-4">Ruangan</label>:&nbsp;
                <span class="col-md-8" id="room"></span>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-4" id="staff-logic">Staff</label>:&nbsp;
                <span class="col-md-8" id="staff_name"></span>
            </div>
            <div class="form-group col-md-12 d-none" id="reminder-logic">
                <label class="col-md-4">Status Reminder</label>:&nbsp;
                <span class="col-md-8" id="reminder_wa"></span>
            </div>
            <div class="form-group col-md-12" id="status-logic">
                <label class="col-md-4">Status</label>:&nbsp;
                <span class="col-md-8" id="status_name"></span>
            </div>
            
        </div>
        
        <!-- Form Submission -->
        <div class="row">
            <div class="col-md-12 mt-4 mb-4" id='btn-div'>
            </div>
        </div>
        <!-- END Form Submission -->
    </form>
</div>

<script>
    const btn_tindakan = '<a href="" type="button" class="btn btn-alt-success" id="btn-tindakan">'
                            +' <i class="fa fa-edit mr-5"></i> Input Tindakan'
                        +'</a>';

    const btn_update = '<button id="btn-submit" class="btn btn-alt-primary">'
                            +'<i class="fa fa-exchange mr-5"></i> Update Status'
                        +'</button>';
    
    const btn_wa = '<button class="btn mr-2 btn-alt-success"data-toggle="tooltip" data-placement="top" title="Kirim Reminder Wa" id="btnWa">'
                        +'<i class="fa fa-whatsapp"></i>'
                    +'</button>';

    jQuery(function(){
        var BeFormValidation = function() {
            var initValidationBootstrap = function(){
                jQuery('#formUpdate').validate({
                    ignore: [],
                    errorClass: 'invalid-feedback animated fadeInDown',
                    errorElement: 'div',
                    errorPlacement: function(error, e) {
                        jQuery(e).parents('.form-group').append(error);
                    },
                    highlight: function(e) {
                        jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
                    },
                    success: function(e) {
                        jQuery(e).closest('.form-group').removeClass('is-invalid');
                        jQuery(e).remove();
                    },
                    rules: {
                        'id': {
                            required: true
                        },
                        'type': {
                            required: true
                        },
                        // 'appointment_date': {
                        //     required: false,
                        //     dateITA: true
                        // },
                        // 'appointment_time': {
                        //     required: false,
                        //     time: true
                        // },
                    },
                });
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

        BeFormValidation.init();

        sendWa = function(patient, date, btn){
            let btnHtml = $(btn).html();
            Swal.fire({
                    title: 'Kirim Reminder Via Whatsapp ?',
                    type: 'question',
                    showCancelButton: true,
                    showConfirmButton: true,
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('admin.calendar.sendWa') }}",
                            data: {
                                patient : patient,
                                date: date
                            },
                            beforeSend: function(xhr){
                                $(btn).html("<i class='fa fa-spinner fa-spin'></i>");
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
                                }else{
                                    $.alert({
                                        title: 'Error',
                                        icon: 'fa fa-warning',
                                        type: 'orange',
                                        content: result.message,
                                    });
                                }
                                $(btn).html(btnHtml);
                            }
                        });
                    }
                })
            }
    })
</script>