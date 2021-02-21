<div class="block-content">
    <form class="js-validation-bootstrap form-vertical" method="post" action="{{ route('admin.booking.store-appointment') }}" id="formGroup" autocomplete="off">
        @csrf
        @method('POST')
        <input type="hidden" name="qId" id="qId" value="{{ $qid }}">
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>Pasien</label>
                <select class="form-control required" id="pid" name="pid" data-parsley-required="true" data-placeholder="Pilih" style="width: 100%">
                    <option></option>
                    @foreach ($patient as $patient)
                    <option value="{{ $patient->patient_unique_id }}"> {{ $patient->patient_unique_id }} - {{ $patient->patient_name }} </option>
                    @endforeach    
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Tanggal</label>
                <input type="text" class="form-control datepicker" id="appointment_date" name="appointment_date">
            </div>
            <div class="form-group col-md-6">
                <label>Jam</label>
                <input type="text" class="js-masked-time form-control" id="appointment_time" name="appointment_time">
            </div>
            <div class="form-group col-md-12">
                <label>Dokter</label>
                <select class="form-control" id="staff_id" name="staff_id" style="width: 100%" data-placeholder="Pilih">
                    <option></option>
                    @foreach ($staff as $person)
                    <option value="{{ $person->id }}" {{ ( $person->id == old('staff_id')) ? 'selected' : '' }}> {{ $person->user->full_name }} </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-12">
                <label>Ruangan</label>
                <select class="form-control" id="room_id" name="room_id" style="width: 100%" data-placeholder="Pilih">
                    <option></option>
                    @foreach ($roomList as $item)
                    <option value="{{ $item->id }}" {{ ( $item->id == old('room_id')) ? 'selected' : '' }}> {{ $item->name }} - {{ $item->group->floor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-12">
                <label>Catatan</label>
                <input type="text" class="form-control" id="notes" name="notes">
            </div>
            <div class="form-group col-md-12">
                <label>Flag Info</label>
                <input type='text' class="form-control" id="patient_flag_id" name="patient_flag_id"/>
            </div>
        </div>

        <!-- Form Submission -->
        <div class="row items-push">
                    <div class="col-lg-7 offset-lg-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-alt-success">
                                <i class="fa fa-plus mr-5"></i> Submit
                            </button>
                        </div>
                    </div>
        </div>
        <!-- END Form Submission -->
    </form>
</div>

<script>
    jQuery(function(){
        var BeFormValidation = function() {
            var initValidationBootstrap = function(){
                jQuery('.js-validation-bootstrap').validate({
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
                        'pid': {
                            required: true
                        },
                        'staff_id': {
                            required: true
                        },
                        /*'notes': {
                            required: true
                        },*/
                        'patient_flag_id': {
                            required: true
                        },
                        'appointment_date': {
                            required: true,
                            dateITA: true
                        },
                        'appointment_time': {
                            required: true,
                            time: true
                        },
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

        $(document).ready(function(){
            $("#patient_flag_id").spectrum("set", '{{ $patientflag }}');

            $("#patient_flag_id").spectrum({
                showPaletteOnly: true,
                showPalette:true,
                hideAfterPaletteSelect: true,
                preferredFormat: "name",
                color: 'green',
                palette: {!! str_replace('"', "'", json_encode(array_map('strtolower', array_column($flags->toArray(), 'name')), JSON_HEX_QUOT)) !!},
            });

        })
    })
</script>