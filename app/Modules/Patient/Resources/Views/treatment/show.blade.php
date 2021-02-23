@extends('backend.layouts.app')

@section('title', __('patient::labels.treatment.management') . ' | ' . __('patient::labels.treatment.create'))

@section('content')
<div class="row row-deck">
    <div class="col-lg-12">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Data Pasien</h3>
                <div class="block-options">
                    <a href="{{ route('admin.patient.treatment.index') }}" class="btn btn-sm btn-secondary mr-5 mb-5">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="block-content">
                <table class="table table-borderless table-sm table-striped">
                    <tbody>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">APP ID</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $treatment->treatment_no }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Pasien ID</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $treatment->patient->patient_unique_id }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Pasien</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $treatment->patient->patient_name }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Usia</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $treatment->patient->age }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Hobi</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $treatment->patient->hobby }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Telepon</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $treatment->patient->phone_number }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Tgl Lahir</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $treatment->patient->dob }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Jenis Kelamin</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $treatment->patient->gender }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Gol. Darah</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $treatment->patient->blood->blood_name }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Flag</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $treatment->patient->flag['name'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left" colspan="6">
                                <span class="text-muted">Notes: {{ $treatment->notes }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    @if($treatment->status_id == config('admission.admission_waiting') || $treatment->status_id == config('admission.admission_processed'))
    <div class="col-md-6">
                    <div class="block block-rounded" id="after">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Foto Before</h3>
                        </div>
                        <div class="block-content">
                            <div class="row items-push js-gallery img-fluid-100 js-gallery-enabled">
                                <div class="col-md-8 animated fadeIn" id="afterImage">
                                    
                                </div>
                            </div>
                            <div class="row items-push js-gallery img-fluid-100 js-gallery-enabled">
                                @if(isset($patient->beforeafter))
                                    @foreach($patient->beforeafter->where('type', 'before')->where('date', $treatment->created_at->format('Y-m-d')) as $item)
                                    <div class="col-md-6 col-lg-4 col-xl-3 animated fadeIn">
                                        <div class="options-container fx-item-zoom-in fx-overlay-zoom-out">
                                            <img class="img-fluid options-item" src="{{ URL::asset($item->image) }}" alt="">
                                            <div class="options-overlay bg-black-op">
                                                <div class="options-overlay-content">
                                                    <h6 class="h6 text-white mb-5">Image</h6>
                                                    <h6 class="h6 text-white-op mb-15">More Details</h6>
                                                    <a class="btn btn-sm btn-rounded btn-alt-primary min-width-75 viewAfter" data-pict="{{ URL::asset($item->image) }}" href="javascript:void(0)">
                                                        <i class="fa fa-pencil"></i> Lihat
                                                    </a>
                                                    <a class="btn btn-sm btn-rounded btn-alt-danger min-width-75" href="javascript:void(0)">
                                                        <i class="fa fa-times"></i> Hapus
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

    <div class="col-md-6">
            <div class="block my-block" id="my-block2">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Foto After (Saat Ini)</h3>
                    <div class="block-options">
                        <button type="button" onclick="document.getElementById('file-input-foto').click();" class="btn btn-sm btn-success mr-5 mb-5">
                            <i class="fa fa-plus mr-5"></i>Tambah Foto
                        </button>
                        <input id="file-input-foto" type="file" name="file-input-foto" style="display: none;" accept="image/x-png,image/jpeg" />
                        <button type="button" class="btn btn-sm btn-info mr-5 mb-5" data-toggle="modal" data-target="#modal-webcam" data-backdrop="static" id="openCamera">
                            <i class="fa fa-camera"></i> Webcam
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full">
                    <div class="row items-push" id="imageGrid">
                                
                    </div>
                </div>
            </div>
    </div>

    <div class="col-lg-12">
        <div class="block block-rounded my-block">
            <form method="post" id="invoice_form" class="js-validation-bootstrap" autocomplete="off">
            <input type="hidden" name="tId" id="tId" value="{{ $treatment->id }}">
            <div class="block-header block-header-default">
                <h3 class="block-title">Tindakan dan Catatan</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="form-row">
                    <div class="form-group col-md-6 d-none">
                        <label>Tindakan</label>
                        <!-- <input type="text" class="form-control" id="service" name="service"> -->
                        <select class="form-control" id="service_id" name="service_id" style="width: 100%" data-placeholder="Pilih">
                            <option></option>
                            @foreach ($dataservice as $item)
                            <option value="{{ $item->id }}" {{ ( $item->id == old('service_id')) ? 'selected' : '' }}>{{ $item->code }} - {{ $item->name }} - {{ $item->category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Keterangan/Catatan Pasien</label>
                        <textarea class="form-control js-simplemde" id="notes" name="notes" required="required" value=""></textarea>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-success"><span class="fa fa-check"></span> Selesai</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

    @else
    <div class="col-lg-12">
        <div class="block block-rounded my-block">
            <form method="post" id="invoice_form" class="js-validation-bootstrap" autocomplete="off">
            <input type="hidden" name="tId" id="tId" value="{{ $treatment->id }}">
            <div class="block-header block-header-default">
                <h3 class="block-title">Catatan</h3>
            </div>
            <div class="block-content block-content-full">
                <p class="mb-0">@markdown($treatment->service_notes)</p>
            </div>
            </form>
        </div>
    </div>
    @endif
</div>

@if($prescription)
    @include('patient::prescription.partials.hasil-diagnosa')
@else
    @include('patient::treatment.partials.treatment-details')
@endif

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

<link rel="stylesheet" href="{{ URL::asset('js/plugins/croppie/croppie.css') }}">
<script src="{{ URL::asset('js/plugins/croppie/croppie.js') }}"></script>
<script src="{{ URL::asset('js/plugins/croppie/exif-js.js') }}"></script>
<script src="{{ URL::asset('js/plugins/webcam/webcam.min.js') }}"></script>
<script language="JavaScript">
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 100
    });
</script>
<script type="text/javascript">
jQuery(function () {
    Codebase.helpers(['simplemde']);

    $('#service_id').select2({
        placeholder: "Pilih",
        allowClear: true
    });

    var validator = jQuery('.js-validation-bootstrap').validate({
        ignore: ":hidden",
        errorClass: 'invalid-feedback animated fadeInDown',
        errorElement: 'div',
        rules: {
            "service_id": {
                required: true
            },
            "notes": {
                required: true
            }
        },
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
        checkForm: function() {
            this.prepareForm();
            for (var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++) {
                if (this.findByName(elements[i].name).length != undefined && this.findByName(elements[i].name).length > 1) {
                    for (var cnt = 0; cnt < this.findByName(elements[i].name).length; cnt++) {
                        this.check(this.findByName(elements[i].name)[cnt]);
                    }
                } else {
                    this.check(elements[i]);
                }
            }
            return this.valid();
        },
        submitHandler: function(form) {
            var formData = new FormData(form);

            $('#imageGrid').find('img').each(function () {
                imageName = $(this).attr('src');
                formData.append('inputFoto[]', imageName);
            });

            $.ajax({
                type: "POST",
                url: "{{ route('admin.patient.treatment.update') }}",
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
                success: function(result) {
                    if (result.status) {
                        Swal.fire({
                            title: result.message,
                            type: 'success',
                            showCancelButton: false,
                            showConfirmButton: true,
                        }).then((result) => {
                            if (result.value) {
                               window.location = '{{ route('admin.patient.treatment.index') }}';
                            }
                        })
                    } else {
                        Swal.fire({
                            title: result.message,
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
        viewport: {
            width: 300,
            height: 300,
            type: 'square'
        },
        enableExif: true
    });

    $(document).on("change", "[type=file]", function() {
        var input = this
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                createImageGrid(e.target.result);
                
                $(input).replaceWith($(input).clone())
            }
            reader.readAsDataURL(input.files[0]);

            // $('#modal-popout').modal('show');
        }
    })

    $('#modal-popout').on('shown.bs.modal', function() {
        $uploadCrop.croppie('bind');
        // $uploadCrop.croppie('setZoom', 1.0);  
    });

    $('#modal-popout').on('hidden.bs.modal', function() {
        $("#file-input-foto").val("");
    });

    $("#sipFoto").on('click', function(e) {
        $uploadCrop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function(response) {
            createImageGrid(response);
            $('#modal-popout').modal('hide');
        });
    })

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