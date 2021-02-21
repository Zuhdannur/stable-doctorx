@extends('backend.layouts.app')

@section('title', app_name() . ' | Foto Before/After')

@section('content')
<div class="block my-block" id="my-block2">
    <div class="block-header block-header-default">
        <h3 class="block-title">Foto Before/After</h3>
        <div class="block-options">
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
        </div>
    </div>
    <div class="block-content block-content-full">
        <!-- Table -->
        <form method="post" id="invoice_form" class="js-validation-bootstrap" autocomplete="off" enctype="multipart/form-data">
            <!-- Invoice Info -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="mega-firstname">Pasien</label>
                            <select class="form-control required" id="patient_id" name="patient_id" data-parsley-required="true" data-placeholder="Pilih" style="width: 100%">
                                <option></option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}"> {{ $patient->patient_unique_id }} - {{ $patient->patient_name }} </option>
                                @endforeach    
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="mega-lastname">Tanggal</label>
                            <input type="text" class="form-control datepicker required" id="date" name="date" value="{{ old('date') }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-4">
                            <label for="mega-lastname">Tambah Foto</label>
                            <br>
                            <button type="button" onclick="document.getElementById('file-input-foto').click();" class="btn btn-sm btn-success mr-5 mb-5">
		                   		<i class="fa fa-plus mr-5"></i>Ambil Foto
		                    </button>
		                    <input id="file-input-foto" type="file" name="file-input-foto" style="display: none;" accept="image/x-png,image/jpeg" />
		                    <button type="button" class="btn btn-sm btn-info mr-5 mb-5" data-toggle="modal" data-target="#modal-webcam" data-backdrop="static" id="openCamera">
		                    	<i class="fa fa-camera"></i> via Webcam
		                    </button>
                        </div>
                        <div class="col-3">
                            <label for="mega-lastname">Before/After</label>
                            <select class="form-control required" id="type_foto" name="type_foto">
                                <option></option>
                                <option value="before">Before</option>
                                <option value="after">After</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label for="mega-lastname">&nbsp;</label>
                            <br>
                            <button type="submit" class="btn btn-sm btn-success mr-5 mb-5">
		                   		<i class="fa fa-plus mr-5"></i>Submit
		                    </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-md-6">
				    <div class="block block-rounded">
				        <div class="block-content">
				        	<div class="row items-push" id="imageGrid">
                                
                    		</div>
                    	</div>
                    </div>
                </div>
            </div>
            <!-- END Invoice Info -->
        </form>
        <!-- END Table -->
    </div>
</div>
<!-- Webcam -->
<div class="modal fade" id="modal-webcam" tabindex="-1" role="dialog" aria-labelledby="modal-webcam" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Webcam</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="block text-center">
                                <div class="block-content block-content-full block-content-sm">
                                    <div class="font-w600">Webcam</div>
                                </div>
                                <div class="block-content block-content-full bg-body-light">
                                    <div id="my_camera"></div>
                                </div>
                                <div class="block-content block-content-full">
                                    <button class="btn btn-white btn-sm btn-datatable-add blue" id="buttonStart" onclick="setup();">
                                        <i class="ace-icon fa fa-image"></i> Start
                                    </button>
                                    <button class="btn btn-white btn-sm btn-datatable-add blue" disabled="disabled" onclick="stop()" id="buttonStop">
                                        <i class="ace-icon fa fa-stop"></i> Stop
                                    </button>
                                    <button class="btn btn-white btn-sm btn-datatable-add blue" disabled="disabled" onclick="take_snapshot()" id="buttonSnap">
                                        <i class="ace-icon fa fa-camera"></i> Capture
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="block text-center">
                                <div class="block-content block-content-full block-content-sm">
                                    <div class="font-w600">Hasil Foto</div>
                                </div>
                                <div class="block-content block-content-full bg-body-light">
                                    <div id="results"></div>
                                </div>
                                <div class="block-content block-content-full">
                                    <button type="button" class="btn btn-sm btn-success pull-right" id="savePhoto" onclick="post_to_preview()" disabled="disabled">
                                        <span class="bigger-110">Save Photo</span>

                                        <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- END Webcam Modal -->

<div id="imageArea">
</div>
<link rel="stylesheet" href="{{ URL::asset('js/plugins/croppie/croppie.css') }}">
<script src="{{ URL::asset('js/plugins/croppie/croppie.js') }}"></script>
<script src="{{ URL::asset('js/plugins/croppie/exif-js.js') }}"></script>
<script src="{{ URL::asset('js/plugins/webcam/webcam.min.js') }}"></script>
<script language="JavaScript">
    Webcam.set({
        width: 1320,
        height: 1240,
        image_format: 'jpeg',
        jpeg_quality: 100
    });
</script>
<script type="text/javascript">
jQuery(function () {
    $('.datepicker').datepicker({
        todayHighlight: true,
        format: "{{ setting()->get('date_format_js') }}",
        weekStart: 1,
        language: "{!! str_replace('_', '-', app()->getLocale()) !!}",
        daysOfWeekHighlighted: "0,6",
        autoclose: true
    });

    var cacheResult = $(this).data('cache') === 'on';

    function callAjax(patientId){
    	var loadUrl = '{{ route("admin.patient.beforeafter.get", ":id") }}';
            loadUrl = loadUrl.replace(':id', patientId);

    	$.ajax({
			    url: loadUrl,
			    data: {},
			    localCache: cacheResult,
			    type: 'GET',
			    dataType: 'html',
			    beforeSend: function(xhr) {
			        Codebase.blocks('.my-block', 'state_loading');
			    },
			    error: function(x, status, error) {
			        if (x.status == 403) {
			            $.alert({
			                title: 'Error',
			                icon: 'fa fa-warning',
			                type: 'red',
			                content: "Error: " + x.status + "",
			            });
			        } else if (x.status === 422) {
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
			            $.alert({
			                title: 'Error',
			                icon: 'fa fa-warning',
			                type: 'red',
			                content: "An error occurred: " + status + "nError: " + error,
			            });
			        }

			        Codebase.blocks('.my-block', 'state_normal');
			    },
			    success: function(data) {
			    	$('#imageArea').html(data);
			        Codebase.blocks('.my-block', 'state_normal');
			    }
			});
    }
    $('#patient_id').select2({
        placeholder: "Pilih",
        minimumInputLength: 3,
        allowClear: true
    }).on("select2:select", function (e) {
        var selected = e.params.data;

        if (typeof selected !== "undefined") {
	        callAjax(selected.id);
        }
    });

	var validator = jQuery('.js-validation-bootstrap').validate({
	    ignore: [],
	    errorClass: 'invalid-feedback animated fadeInDown',
	    errorElement: 'div',
	    errorPlacement: function(error, e) {
	        jQuery(e).parents('.form-group > div').append(error);
	    },
	    highlight: function(e) {
	        jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
	    },
	    success: function(e) {
	        jQuery(e).closest('.form-group').removeClass('is-invalid');
	        jQuery(e).remove();
	    },
	    submitHandler: function(form) {
            //Fetch Image
            var formData = new FormData(form);

            $('#imageGrid').find('img').each(function () {
                imageName = $(this).attr('src');
                formData.append('inputFoto[]', imageName);
            });

            $.ajax({
                type: "POST",
                url: "{{ route('admin.patient.beforeafter.store') }}",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function(xhr) {
                    Codebase.blocks('.my-block', 'state_loading');
                },
                error: function(x, status, error) {
                    if (x.status == 403) {
                        $.alert({
                            title: 'Error',
                            icon: 'fa fa-warning',
                            type: 'red',
                            content: "Error: " + x.status + "",
                        });
                    } else if (x.status === 422) {
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
                        $.alert({
                            title: 'Error',
                            icon: 'fa fa-warning',
                            type: 'red',
                            content: "An error occurred: " + status + "nError: " + error,
                        });
                    }

                    Codebase.blocks('.my-block', 'state_normal');
                },
                success: function(data) {
                    if (data.status) {
                    	$('#imageGrid').html('');
                        Swal.fire({
                            title: data.message,
                            type: 'success',
                            showCancelButton: false,
                            showConfirmButton: true,
                        }).then((result) => {
                            if (result.value) {
                            	callAjax(data.id);
                            }
                        })
                    } else {

                        Swal.fire({
                            title: data.message,
                            type: 'error',
                            showCancelButton: false,
                            showConfirmButton: true,
                        })
                    }

                    Codebase.blocks('.my-block', 'state_normal');
                }
            });
            return false; // required to block normal submit since you used ajax
        }

	});

	var imageNumber = 0;
    createImageGrid = function (image){
        imageNumber++;
        var templateImage = '<div class="col-md-2 animated fadeIn" id="inputFoto'+imageNumber+'">'+
                                '    <div class="options-container">'+
                                '        <img name="inputFoto[]" class="img-fluid options-item" src="'+image+'" alt="">'+
                                '        <div class="options-overlay bg-primary-dark-op">'+
                                '            <div class="options-overlay-content">'+
                                '                <a data-image-id="inputFoto'+imageNumber+'" class="btn btn-sm btn-rounded btn-danger min-width-75 deletePhoto" href="javascript:void(0)">'+
                                '                    <i class="fa fa-times"></i> Hapus'+
                                '                </a>'+
                                '            </div>'+
                                '        </div>'+
                                '    </div>'+
                                '    '
                                '</div>';
        $("#imageGrid").append(templateImage);
        $("#file-input-foto").val("");
    }

    $uploadCrop = $('#upload-demo').croppie({
        boundary: {
            width: 400,
            height: 400
        },
        enableExif: true
    });

    $(document).on("change", "[type=file]", function() {
        var input = this;

        var file_size = input.files[0].size;
		if(file_size > 1048576) {
			$.alert({
			    title: 'Error',
			    icon: 'fa fa-warning',
			    type: 'red',
			    content: "Ukuran file maksimal: 1MB",
			});
			$("#file-input-foto").val("");
			return false;
		} 

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
            	createImageGrid(e.target.result);
            	
                $(input).replaceWith($(input).clone())
            }
            reader.readAsDataURL(input.files[0]);

            $("#file-input-foto").val("");
        }
    });

    $('#imageGrid').on('click', '.deletePhoto', function() {
        $('#imageGrid').find('#'+$(this).data('image-id')).remove();
    });

    var avatar = "{{ asset('media/avatars/default.png') }}";

    default_avatar = function (){
        document.getElementById('results').innerHTML =
                '<img width="240px" src="' + avatar + '"/>';
    }

    setup = function () {

        Webcam.reset();
        Webcam.on( 'error', function(err) {
            Swal.fire({
                title: 'Webcam tidak terhubung!',
                type: 'error',
                showCancelButton: false,
                showConfirmButton: true,
            })

            $('#modal-webcam').modal('hide');
        } );
        Webcam.attach('#my_camera');
        default_avatar();
        $('#buttonStart').prop('disabled', true);
        $('#buttonStop').prop('disabled', false);
        $('#buttonSnap').prop('disabled', false);
        $('#savePhoto').prop('disabled', true);
    }

    take_snapshot = function () {
        // take snapshot and get image data
        Webcam.snap(function(data_uri) {
            // display results in page
            document.getElementById('results').innerHTML =
                '<img src="' + data_uri + '"/>';
        });

        $('#savePhoto').prop('disabled', false);
    }

    stop = function () {
        // Reset
        Webcam.reset();
        default_avatar();

        $('#buttonStart').prop('disabled', false);
        $('#buttonStop').prop('disabled', true);
        $('#buttonSnap').prop('disabled', true);
        $('#savePhoto').prop('disabled', true);
    }

    post_to_preview = function () {
        var imageResult = $('#modal-webcam').find("#results > img").attr("src");

        createImageGrid(imageResult);

        $('#modal-webcam').modal('hide');
    }

    $('#modal-webcam').on('shown.bs.modal', function (e) {
        setup();
    });

    $('#modal-webcam').on('hidden.bs.modal', function (e) {
        stop();
    });

    
});
</script>
@endsection