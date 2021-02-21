<div class="modal fade" role="dialog" aria-labelledby="modal-popout" aria-hidden="true" id="modalForm">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <form class="js-validation-bootstrap" method="post" id="formGeneral" action="{{ route('admin.patient.timeline', $patient->patient_unique_id) }}" enctype="multipart/form-data" autocomplete="off">
                <div class="block block-themed block-transparent mb-0" id="my-block2">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title modal-title">Tambah Timeline ( {{ $patient->patient_name }} )</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <div id="status_settings"></div>
                        <div class="row">
                        	<input type="hidden" class="form-control" id="patient_id" name="patient_id" value="{{ $patient->patient_unique_id }}">
                            <div class="col-12">
                                <div class="block mb-0">
                                    <div class="block-content">
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label>Judul</label>
                                                <input type="text" class="form-control" id="title" name="title">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label>Tanggal</label>
                                                <input type="text" class="js-masked-date form-control" id="timeline_date" name="timeline_date" placeholder="dd/mm/yyyy" value="{{ old('timeline_date') }}">
                                                
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label>Deskripsi</label>
                                                <input type="text" class="form-control" id="description" name="description">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label>File</label>
                                                <input type="file" name="document" id="document" class="dropify form-control" data-height="20" data-max-file-size="1M" data-allowed-file-extensions="jpg png jpeg" />
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-alt-success">
                            <i class="fa fa-check"></i> Simpan Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function(){ 
	Codebase.helpers(['masked-inputs', 'notify']); 
});

$('.dropify').dropify({
    messages: {
        'default': 'Drag and drop a file here or click',
        'replace': 'Drag and drop or click to replace',
        'remove': 'Hapus',
        'error': 'Ooops, something wrong happended.'
    },
    tpl: {
        wrap: '<div class="dropify-wrapper"></div>',
        loader: '<div class="dropify-loader"></div>',
        message: '<div class="dropify-message"><span class="file-icon" /> <p>@{{ default }}</p></div>',
        preview: '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message">@{{ replace }}</p></div></div></div>',
        filename: '<p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p>',
        clearButton: '<button type="button" class="dropify-clear btn-xs" style="margin-top: -9px;">@{{ remove }}</button>',
        errorLine: '<p class="dropify-error">@{ error }}</p>',
        errorsContainer: '<div class="dropify-errors-container"><ul></ul></div>'
    }
});

var BeFormValidation = function() {
    var initValidationBootstrap = function(){
        jQuery('.js-validation-bootstrap').validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e) {
                jQuery(e).parents('.form-group > div').append(error);
            },
            highlight: function(e) {
                jQuery(e).closest('.form-group > div').removeClass('is-invalid').addClass('is-invalid');
            },
            success: function(e) {
                jQuery(e).closest('.form-group > div').removeClass('is-invalid');
                jQuery(e).remove();
            },
            rules: {
                'title': {
                    required: true
                },
                'description': {
                    required: true
                },
				'timeline_date': {
					required: true,
					dateITA: true
				},
            },
            submitHandler: function(form) {
            	var formData = new FormData(form);

		        $.ajax({
		            type: 'POST',
		            url: $(form).attr('action'),
		            data: formData,
		            dataType: 'json',
		            processData: false,
                    contentType: false,
		            beforeSend: function(xhr) {
		                Codebase.blocks('#my-block2', 'state_loading');
		            },
		            error: function(x, status, error) {
		                if (x.status === 422) {
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
						    var errors = x.responseJSON;
						    $.alert({
						        title: x.status + ': ' + error,
						        icon: 'fa fa-warning',
						        type: 'red',
						        content: errors.message,
						    });
						}

						Codebase.blocks('#my-block2', 'state_normal');
		            },
		            success: function(result) {
		                if (result.status) {
		                	$('#modalForm').modal('hide');
		                	
		                    $.confirm({
	                            icon: 'fa fa-smile-o',
	                            theme: 'modern',
	                            closeIcon: true,
	                            content: result.message,
	                            animation: 'scale',
	                            type: 'blue',
	                            buttons: {
							        close: function(){
							        	location.reload();
        							}
							    }
	                        });
		                } else {
							$.alert({
		                        icon: 'fa fa-warning',
		                        type: 'red',
		                        content: result.message,
		                        columnClass: 'col-md-5 col-md-offset-3',
		                        typeAnimated: true,
		                        draggable: false,
		                    });
		                }

		                Codebase.blocks('#my-block2', 'state_normal');
		            }
		        });
		        return false; // required to block normal submit since you used ajax
		    }
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
</script>