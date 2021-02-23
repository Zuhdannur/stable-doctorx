@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.frontend.auth.register_box_title'))

@section('content')
<!-- Validation Wizards -->
<h2 class="content-heading">Form Pendaftaran</h2>
<div class="row">
    <div class="col-md-12">
        <!-- Validation Wizard Classic -->
        <div class="js-wizard-validation-classic block block-themed block-rounded block-shadow" id="my-block">
            <!-- Step Tabs -->
            <ul class="nav nav-tabs nav-tabs-block nav-fill" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#wizard-validation-classic-step1" data-toggle="tab">1. Servis</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#wizard-validation-classic-step2" data-toggle="tab">2. Data Pasien</a>
                </li>
            </ul>
            <!-- END Step Tabs -->

            <!-- Form -->
            <form class="js-wizard-validation-classic-form" action="{{ route('patient.register') }}" method="post" autocomplete="off">
                <!-- Wizard Progress Bar -->
                @csrf
                @method('POST')
                <div class="block-content block-content-sm">
                    <div class="progress" data-wizard="progress" style="height: 8px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-succes" role="progressbar" style="width: 30%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <!-- END Wizard Progress Bar -->
                <!-- Steps Content -->
                <div class="block-content block-content-full tab-content">
                    <!-- Step 1 -->
                    <div class="tab-pane active" id="wizard-validation-classic-step1" role="tabpanel">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="appointment_type">Servis</label>
                                <select class="form-control" id="appointment_type" name="appointment_type">
                                    <option selected="selected" value="">Pilih</option>
                                    @foreach ($appointmenttype as $item)
                                        <option value="{{ $item->id }}" data-role-id="{{ $item->role_id }}" data-group-id="{{ $item->room_group_id }}" {{ ( $item->id == old('doctor_id')) ? 'selected' : '' }}> {{ $item->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pid">Nomor ID Pasien (Kosongkan Jika Tidak Ada)</label>
                                <input class="form-control" type="text" id="pid" name="pid" placeholder="RM00 (Nomor Pasien dapat dilihat pada kartu anda.)">
                            </div>
                        </div>
                    </div>
                    <!-- END Step 1 -->

                    <!-- Step 2 -->
                    <div class="tab-pane" id="wizard-validation-classic-step2" role="tabpanel">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" id="patient_name" name="patient_name">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Jenis Kelamin</label>
                                {!! Form::select('gender', ['M'=>'Laki - Laki','F'=>'Perempuan'], old('gender'), ['class' => 'form-control', 'id' => 'gender', 'placeholder' => 'Pilih Jenis Kelamin', 'required' => 'true']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                <label>Golongan Darah</label>
                                <select class="form-control" id="blood_id" name="blood_id">
                                    <option selected="selected" value="">Pilih</option>
                                    @foreach ($bloodbank as $item)
                                    <option value="{{ $item->id }}" {{ ( $item->id == old('blood_group')) ? 'selected' : '' }}> {{ $item->blood_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>No. Telepon</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Tempat Lahir</label>
                                <input type="text" class="form-control" id="birth_place" name="birth_place" value="{{ old('birth_place') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Tanggal Lahir</label>
                                <input type="text" class="js-masked-date form-control" id="dob" name="dob" placeholder="dd/mm/yyyy" value="{{ old('dob') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Kota</label>
                                <br>
                                <select class="form-control" id="city_id" name="city_id" style="width: 100%" data-placeholder="Pilih">
                                    <option></option>
                                    
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Kecamatan</label>
                                <select class="form-control" name="district_id" id="district_id" class="form-control" style="width: 100%" data-placeholder="Pilih">
                                    <option></option>
                                </select>

                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Desa</label>
                                <br>
                                <select class="form-control" id="village_id" name="village_id" style="width: 100%" data-placeholder="Pilih">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Kode Pos</label>
                                <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code') }}">

                            </div>
                            <div class="form-group col-md-3">
                                <label>Agama</label>
                                <br>
                                <select class="form-control" id="religion_id" name="religion_id" style="width: 100%" data-placeholder="Pilih">
                                    <option></option>
                                    @foreach ($religions as $religion)
                                    <option value="{{ $religion->id }}"> {{ $religion->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Alamat</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Pekerjaan</label>
                                <br>
                                <select class="form-control" id="work_id" name="work_id" style="width: 100%" data-placeholder="Pilih">
                                    <option></option>
                                    @foreach ($works as $work)
                                    <option value="{{ $work->id }}"> {{ $work->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Sumber Info</label>
                                <select class="form-control" id="info_id" name="info_id" style="width: 100%" data-placeholder="Pilih">
                                    <option></option>
                                    @foreach ($info as $item)
                                    <option value="{{ $item->id }}"> {{ $item->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Media</label>
                                <select class="form-control" id="media_info_id" name="media_info_id[]" multiple="multiple" style="width: 100%" data-placeholder="Pilih">
                                    @foreach ($media as $item)
                                    <option value="{{ $item->id }}"> {{ $item->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Hobi</label>
                                <br>
                                <input type="text" class="form-control" id="hobby" name="hobby" value="{{ old('hobby') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Pasien Lama ?</label>
                                <select class="form-control" id="old_patient" name="old_patient">
                                    <option value="">Pilih</option>
                                    <option value="n"> Tidak </option>
                                    <option value="y"> Ya </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- END Step 2 -->
                </div>
                <!-- END Steps Content -->

                <!-- Steps Navigation -->
                <div class="block-content block-content-sm block-content-full bg-body-light">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" class="btn btn-alt-secondary" data-wizard="prev">
                                <i class="fa fa-angle-left mr-5"></i> Previous
                            </button>
                        </div>
                        <div class="col-6 text-right">
                            <button type="button" class="btn btn-alt-secondary" data-wizard="next">
                                Next <i class="fa fa-angle-right ml-5"></i>
                            </button>
                            <button type="submit" class="btn btn-alt-primary d-none" data-wizard="finish">
                                <i class="fa fa-check mr-5"></i> Submit
                            </button>
                        </div>
                    </div>
                </div>
                <!-- END Steps Navigation -->
            </form>
            <!-- END Form -->
        </div>
        <!-- END Validation Wizard Classic -->
    </div>
</div>
<!-- END Validation Wizards -->

<script src="{{ URL::asset('js/plugins/moment/moment.min.js') }}"></script>
<script>
    jQuery(function(){ 
        xDistrict = 0, xVillage = 0;

        Codebase.helpers(['masked-inputs', 'notify', 'select2']); 
    
        $('.datepicker').datepicker({
            todayHighlight: true,
            format: "{{ setting()->get('date_format_js') }}",
            weekStart: 1,
            language: "{!! str_replace('_', '-', app()->getLocale()) !!}",
            daysOfWeekHighlighted: "0,6",
            autoclose: true
        });
        $(document).on("focusout", ".datepicker", function() {
            $(this).prop('readonly', false);
        });

        $(document).on("focusin", ".datepicker", function() {
            $(this).prop('readonly', true);
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
                        'patient_name': {
                            required: true
                        },
                        'gender': {
                            required: true
                        },
                        'phone_number': {
                            required: true,
                            number: true
                        },
                        'zip_code': {
                            number: true
                        },
                        'dob': {
                            required: true,
                            dateITA: true
                        },
                        'address': {
                            required: true,
                        },
                        'city_id': {
                            required: true,
                        },
                        'district_id': {
                            required: true,
                        },
                        'village_id': {
                            required: true,
                        },
                        'religion_id': {
                            required: true,
                        },
                        'work_id': {
                            required: true,
                        },
                        'info_id': {
                            required: true,
                        },
                        'hobby': {
                            required: true,
                        },
                        'blood_id': {
                            required: true,
                        },
                        'appointment_type': {
                            required: true,
                        },
                        'old_patient': {
                            required: true,
                        },
                        'media_info_id': {
                            required: true,
                        },
                    },
                    submitHandler: function(form) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('admin.patient.store') }}",
                            data: $(form).serialize(),
                            dataType: 'json',
                            beforeSend: function(xhr) {
                                Codebase.blocks('#my-block', 'state_loading');
                            },
                            error: function(x, status, error) {
                                if (x.status == 403) {
                                    $.alert({
                                        title: 'Error',
                                        icon: 'fa fa-warning',
                                        type: 'red',
                                        content: "Error: " + x.status + "",
                                    });
                                } else if (x.status === 422 ) {
                                    var errors = x.responseJSON;
                                    var errorsHtml = '';
                                    $.each(errors['errors'], function (index, value) {
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

                                Codebase.blocks('#my-block', 'state_normal');
                            },
                            success: function(result) {
                                if(result.status){
                                    $('#modalForm').modal('hide');
                                    $.notify({
                                        message: result.message,
                                        type: 'success'
                                    });

                                    setTimeout(function(){// wait for 5 secs(2)
                                        location.reload(); // then reload the page.(3)
                                    }, 2000); 
                                }else{
                                    $.alert({
                                        title: 'Error',
                                        icon: 'fa fa-warning',
                                        type: 'orange',
                                        content: result.message,
                                    });
                                }
                                
                                Codebase.blocks('#my-block', 'state_normal');
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

        $('#staff_id, #room_id, #district_id, #media_info_id, #village_id, #religion_id, #work_id, #info_id').select2({
            placeholder: "Pilih",
            allowClear: true
        });

        var $citySel = $('#city_id');

        $citySel.select2({
            ajax: {
                url: '{{ url("cities") }}',
                dataType: 'json',
                type: 'GET',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term, // search term
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Pilih Kota',
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });

        function formatRepo(repo) {
            if (repo.loading) {
                return repo.text;
            }

            var $container = $(
                "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__title'></div>" +
                "<div class='select2-result-repository__description'></div>" +
                "</div>" +
                "</div>" +
                "</div>"
            );

            $container.find(".select2-result-repository__title").text(repo.city);
            $container.find(".select2-result-repository__description").text(repo.province);

            return $container;
        }

        function formatRepoSelection(repo) {
            return repo.full_name || repo.text;
        }

        populateDistrict = function(cityId, elemen) {
            if (!cityId) {
                return false;
            }

            $.ajax({
                url: '{{ url("region/district") }}/' + cityId,
                beforeSend: function(xhr) {
                    Codebase.blocks('#my-block', 'state_loading');
                },
                error: function() {
                    alert('An error has occurred');
                    Codebase.blocks('#my-block', 'state_normal');
                },
                success: function(data) {
                    var result = data.data;

                    if (result.length > 0) {
                        var optionCity = '<option value=""></option>';
                        $.each(result, function(key, value) {
                            optionCity += '<option value="' + value.id + '">' + value.name + '</option>';
                        });

                        optionCity += '</select></label>';

                        $("select" + elemen).html(optionCity);

                        $("select" + elemen).val(xDistrict).trigger('change');

                    } else {
                        alert('data empty!');
                    }

                    Codebase.blocks('#my-block', 'state_normal');
                },
                type: 'GET'
            });
        }

        populateVillages = function(districtId, elemen) {
            if (!districtId) {
                return false;
            }

            $.ajax({
                url: '{{ url("region/village") }}/' + districtId,
                beforeSend: function(xhr) {
                    Codebase.blocks('#my-block', 'state_loading');
                },
                error: function() {
                    alert('An error has occurred');
                    Codebase.blocks('#my-block', 'state_normal');
                },
                success: function(data) {
                    var result = data.data;

                    if (result.length > 0) {
                        var optionVillage = '<option value=""></option>';
                        $.each(result, function(key, value) {
                            optionVillage += '<option value="' + value.id + '">' + value.name + '</option>';
                        });

                        optionVillage += '</select></label>';

                        $("select" + elemen).html(optionVillage);

                        $("select" + elemen).val(xVillage).trigger('change');

                    } else {
                        alert('data empty!');
                    }

                    Codebase.blocks('#my-block', 'state_normal');
                },
                type: 'GET'
            });
        }

        $('body').on('change', '#city_id', function() {
            var city_id = this.value;

            if (city_id) {
                populateDistrict(city_id, '#district_id');
            }

        });

        $('body').on('change', '#district_id', function() {
            var district_id = this.value;

            if (district_id) {
                populateVillages(district_id, '#village_id');
            }

        });

    });

    //wizard with options and events
    var a = jQuery(".js-wizard-validation-classic-form");
    var i = a.validate({
        errorClass: "invalid-feedback animated fadeInDown",
        errorElement: "div",
        errorPlacement: function(a, e) {
            jQuery(e).parents(".form-group").append(a)
        },
        highlight: function(a) {
            jQuery(a).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")
        },
        success: function(a) {
            jQuery(a).closest(".form-group").removeClass("is-invalid"), jQuery(a).remove()
        },
        rules: {
            "appointment_type": {
                required: true,
            },
            "pid": {
                minlength: 5,
                remote: {
                    url: '{{ route("patient.check") }}',
                    type: 'post',
                    beforeSend: function() {
                        console.info('before send');
                        Codebase.blocks('#my-block', 'state_loading');
                    },
                    dataFilter: function(data) {
                            return JSON.parse( data ).status;
                        },
                        complete: function(data) {
                            console.info('complete');
                            Codebase.blocks('#my-block', 'state_normal');
                        },
                }
            },
            'patient_name': {
                required: true
            },
            'gender': {
                required: true
            },
            'phone_number': {
                required: true,
                number: true
            },
            'zip_code': {
                number: true
            },
            'dob': {
                required: true,
                dateITA: true
            },
            'address': {
                required: true,
            },
            'city_id': {
                required: true,
            },
            'district_id': {
                required: true,
            },
            'village_id': {
                required: true,
            },
            'religion_id': {
                required: true,
            },
            'work_id': {
                required: true,
            },
            'info_id': {
                required: true,
            },
            'hobby': {
                required: true,
            },
            'blood_id': {
                required: true,
            },
            'old_patient': {
                required: true,
            },
            'media_info_id': {
                required: true,
            },
        },
        messages: {
            pid: {
                remote: jQuery.validator.format("ID {0} tidak terdaftar!")
            },
        },
    });

    $(".js-wizard-validation-classic").bootstrapWizard({
        tabClass: "",
        nextSelector: '[data-wizard="next"]',
        previousSelector: '[data-wizard="prev"]',
        onTabShow: function(a, e, i) {
            var r = (i + 1) / e.find("li").length * 100,
                t = e.parents(".block").find('[data-wizard="progress"] > .progress-bar');
            t.length && t.css({
                width: r + 1 + "%"
            })
        },
        onNext: function(tab, navigation, index) {
            if (!a.valid()) return i.focusInvalid(), !1

            if (index == 1){
                $('#wizard-validation-classic-step2').find("input[type=text], textarea").val("");
                $('#wizard-validation-classic-step2').find("#staff_id, #room_id, #district_id, #media_info_id, #village_id, #religion_id, #work_id, #info_id").val("-1").trigger('change');

                $('#city_id').html('');
                var pid = $('#pid').val();
                if (pid) {
                    var url = '{{ route("patient.get", ":id") }}';

                    url = url.replace(':id', pid);

                    $.ajax({
                        type: "GET",
                        url: url,
                        dataType: 'json',
                        beforeSend: function(xhr) {
                            Codebase.blocks('#my-block', 'state_loading');
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

                            Codebase.blocks('#my-block', 'state_normal');
                        },
                        success: function(result) {
                            if (result.status) {
                                //Populate
                                xDistrict = result.data.district_id;
                                xVillage = result.data.village_id;

                                $('#patient_name').val(result.data.patient_name);
                                $('#gender').val(result.data.gender);
                                $('#blood_id').val(result.data.blood_id);
                                $('#phone_number').val(result.data.phone_number);
                                $('#birth_place').val(result.data.birth_place);
                                $('#dob').val(convert(result.data.dob));

                                var newOption = new Option(result.data.city.name, result.data.city_id, false, false);
                                $('#city_id').append(newOption).trigger('change');

                                $('#zip_code').val(result.data.zip_code);
                                $('#religion_id').val(result.data.religion_id);
                                $('#address').val(result.data.address);

                                $('#religion_id').val(result.data.religion_id);
                                $('#religion_id').select2().trigger('change');
                                $('#work_id').val(result.data.work_id);
                                $('#work_id').select2().trigger('change');
                                $('#info_id').val(result.data.info_id);
                                $('#info_id').select2().trigger('change');

                                $('#hobby').val(result.data.hobby);
                                $('#old_patient').val(result.data.old_patient);

                                media_ids = jQuery.map(result.data.media, function(n, i){
                                    return n.media_id;
                                });

                                $('#media_info_id').select2().select2('val', [media_ids]);
                            } else {
                                $.alert({
                                    title: 'Error',
                                    icon: 'fa fa-warning',
                                    type: 'orange',
                                    content: result.message,
                                });
                            }

                            Codebase.blocks('#my-block', 'state_normal');
                        }
                    });
                }
            }
        },
        onTabShow: function(tab, navigation, index) {
            switch (index) {
                case 0:
                    // remove 'previous'

                    $('[data-wizard="prev"]').addClass('d-none');
                    $('[data-wizard="finish"]').addClass('d-none');
                    break;
                case 1:
                    $('[data-wizard="prev"]').removeClass('d-none');
                    $('[data-wizard="next"]').addClass('d-none');
                    $('[data-wizard="finish"]').removeClass('d-none');
                    break;
            }

        },
        onTabClick: function(a, e, i) {
            return jQuery("a", e).blur(), !1
        }
    })

function convert(str) {
    var dateObj = new Date(str);
    var momentObj = moment(dateObj);
    return momentObj.format("DD/MM/YYYY") ;
}
</script>
@endsection
