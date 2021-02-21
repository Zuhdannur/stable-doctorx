<!-- Pop Out Modal -->
<div class="modal fade" id="modal-form-appointment" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
        <div class="modal-content">
        	<form class="js-validation-bootstrap form-vertical" method="post" action="{{ route('admin.patient.appointment.store') }}" id="formGroup" autocomplete="off">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title" id="titleHeader">Tambah Data Konsultasi</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    @csrf @method('POST')
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
				            <div class="form-control-plaintext">{{ $start }}</div>
				            <input type="hidden" id="appointment_date" name="appointment_date" value="{{ $start }}">
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
				                @foreach ($room as $item)
				                <option value="{{ $item->id }}" {{ ( $item->id == old('room_id')) ? 'selected' : '' }}> {{ $item->name }} - {{ $item->group->floor->name }}</option>
				                @endforeach
				            </select>
				        </div>
				        <div class="form-group col-md-12">
				            <label>Catatan/Keterangan</label>
				            <input type="text" class="form-control" id="notes" name="notes">
				        </div>
				        <div class="form-group col-md-12">
				            <label>Flag Info</label>
				            <input type='text' class="form-control" id="patient_flag_id" name="patient_flag_id" />
				        </div>
				    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-alt-success">
                    <i class="fa fa-plus mr-5"></i> Submit
                </button>
            </div>
            </form>
        </div>
    </div>
	<!-- END Pop Out Modal -->

	<link rel="stylesheet" href="{{ URL::asset('js/plugins/spectrum/spectrum.css') }}">
	<script src="{{ URL::asset('js/plugins/spectrum/spectrum.js') }}"></script>
	<script type="text/javascript">
	jQuery(function(){ 
	    Codebase.helpers(['masked-inputs']); 

	    $('#pid').select2({
	        placeholder: "Pilih",
	        minimumInputLength: 3,
	        allowClear: true
	    }).on("select2:select", function (e) {
	        var selected = e.params.data;

	        if (typeof selected !== "undefined") {
	            var url = '{{ route("patient.getflag", ":id") }}';
	            url = url.replace(':id', selected.id);

	           $.ajax({
	            url: url,
	            beforeSend: function(xhr) {
	                Codebase.blocks('#my-block', 'state_loading');
	            },
	            error: function() {
	                alert('An error has occurred');
	                Codebase.blocks('#my-block', 'state_normal');
	            },
	            success: function(result) {
	                if(result.status){
	                    $('#patient_flag_id').val(result.data.patient_flag_id);
	                    $("#patient_flag_id").spectrum("set", result.data.name.toLowerCase());
	                }else{
	                    alert('data empty!');
	                }
	                
	                Codebase.blocks('#my-block', 'state_normal');
	            },
	            type: 'GET'
	        });
	        }
	    });
	});

	var lastDate = new Date();
	lastDate.setDate(lastDate.getDate());//any date you want
	$('#appointment_time').val((lastDate.getHours()<10?'0':'') + lastDate.getHours() + ":" + (lastDate.getMinutes()<10?'0':'') + lastDate.getMinutes());

	$('#staff_id, #room_id').select2({
	    placeholder: "Pilih",
	    allowClear: true
	});

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
	                'notes': {
	                    required: true
	                },
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

	// Initialize when page loads
	jQuery(function(){ BeFormValidation.init(); });

	$("#patient_flag_id").spectrum({
	    showPaletteOnly: true,
	    showPalette:true,
	    hideAfterPaletteSelect: true,
	    preferredFormat: "name",
	    color: 'green',
	    palette: [
	        {!! str_replace('"', "'", json_encode(array_map('strtolower', array_column($flags->toArray(), 'name')), JSON_HEX_QUOT)) !!}
	    ]
	});

	</script>
</div>