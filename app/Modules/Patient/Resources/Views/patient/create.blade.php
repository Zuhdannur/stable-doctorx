@extends('backend.layouts.app')

@section('title', __('patient::labels.diagnoseitem.management') . ' | Create Pasien')

@section('content')
<div class="block" id="my-block2">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('patient::labels.diagnoseitem.management') <small class="text-muted">@lang('patient::labels.diagnoseitem.create')</small></h3>
        
    </div>
    <div class="block-content">
        <form class="js-validation-bootstrap" method="post" id="formGeneral" autocomplete="off">
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        <div class="col-4">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control" id="patient_name" name="patient_name">
                        </div>
                        <div class="col-4">
                            <label>Jenis Kelamin</label>
                            {!! Form::select('gender', ['M'=>'Laki - Laki','F'=>'Perempuan'], old('gender'), ['class' => 'form-control', 'placeholder' => 'Pilih Jenis Kelamin', 'required' => 'true']) !!}
                        </div>
                        <div class="col-4">
                            <label>Golongan Darah</label>
                            <select class="form-control" id="blood_id" name="blood_id">
                                <option selected="selected" value="">Pilih</option>
                                @foreach ($bloodbank as $item)
                                <option value="{{ $item->id }}" {{ ( $item->id == old('blood_group')) ? 'selected' : '' }}> {{ $item->blood_name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4">
                            <label>No. Telepon</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number">
                        </div>
                        <div class="col-4">
                            <label>No. Whatsapp</label>
                            <input type="text" class="form-control" id="phone_number" name="wa_number">
                        </div>
                        <div class="col-4">
                            <label>Tempat Lahir</label>
                            <input type="text" class="form-control" id="birth_place" name="birth_place" value="{{ old('birth_place') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-4">
                            <label>Tanggal Lahir</label>
                            <input type="text" class="js-masked-date form-control" id="dob" name="dob" placeholder="dd/mm/yyyy" value="{{ old('dob') }}">
                        </div>
                        <div class="col-4">
                            <label>Kota</label>
                            <br>
                            <select class="form-control" id="city_id" name="city_id" style="width: 100%" data-placeholder="Pilih">
                            </select>
                        </div>
                        <div class="col-4">
                            <label>Kecamatan</label>
                            <select class="form-control" name="district_id" id="district_id" class="form-control" style="width: 100%" data-placeholder="Pilih">
                                <option></option>
                            </select>                            
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-4">
                            <label>Desa</label>
                            <br>
                            <select class="form-control" id="village_id" name="village_id" style="width: 100%" data-placeholder="Pilih">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label>Kode Pos</label>
                            <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code') }}">
                        </div>
                        <div class="col-4">
                            <label>Agama</label>
                            <br>
                            <select class="form-control" id="religion_id" name="religion_id" style="width: 100%" data-placeholder="Pilih">
                                <option></option>
                                @foreach ($religions as $religion)
                                <option value="{{ $religion->id }}"> {{ $religion->name }} </option>
                                @endforeach
                            </select>                            
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-4">
                            <label>Alamat</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
                        </div>
                        <div class="col-4">
                            <label>Pekerjaan</label>
                            <br>
                            <select class="form-control" id="work_id" name="work_id" style="width: 100%" data-placeholder="Pilih">
                                <option></option>
                                @foreach ($works as $work)
                                <option value="{{ $work->id }}"> {{ $work->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <label>Sumber Info</label>
                            <select class="form-control" id="info_id" name="info_id" style="width: 100%" data-placeholder="Pilih">
                                <option></option>
                                @foreach ($info as $item)
                                <option value="{{ $item->id }}"> {{ $item->name }} </option>
                                @endforeach
                            </select>                            
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-4">
                            <label>Media</label>
                            <select class="form-control" id="media_info_id" name="media_info_id[]" style="width: 100%" data-placeholder="Pilih" multiple>
                                <option></option>
                                @foreach ($media as $item)
                                <option value="{{ $item->id }}"> {{ $item->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <label>Hobi</label>
                            <br>
                            <input type="text" class="form-control" id="hobby" name="hobby" value="{{ old('hobby') }}">
                        </div>
                        <div class="col-4">
                            <label>Flag Info</label>
                            <select class="form-control" id="patient_flag_id" name="patient_flag_id" style="width: 100%" data-placeholder="Pilih">
                                <option></option>
                                @foreach ($flags as $flag)
                                <option class="font-w600 text-{{ $flag->color_code }}" value="{{ $flag->id }}"> {{ $flag->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4">
                            <label>Apakah Pasien Lama ?</label>
                            <select class="form-control" id="old_patient" name="old_patient">
                                <option value="">Pilih</option>
                                <option value="n" selected> Tidak </option>
                                <option value="y"> Ya </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('admin.masterdata.diagnoseitem.index'), __('buttons.general.cancel')) }}
                </div>

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.create')) }}
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    jQuery(function(){ 
        Codebase.helpers(['masked-inputs', 'notify', 'select2']); 

        $('#media_info_id').select2({
            placeholder: "Pilih",
            multiple: true
        });
    
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
                        jQuery(e).parents('.form-group > div').append(error);
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
                        'wa_number': {
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
                        'patient_flag_id': {
                            required: true,
                        },
                        'old_patient': {
                            required: true,
                        },
                        'hobby': {
                            required: true,
                        },
                        'blood_id': {
                            required: true,
                        },
                        'queue_date': {
                            required: true,
                            dateITA: true
                        },
                        'queue_time': {
                            required: true,
                            time: true
                        },
                        'admission_type': {
                            required: true,
                        },
                        'status_id': {
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
                                Codebase.blocks('#my-block2', 'state_loading');
                            },
                            error: function(x, status, error) {
                                if (x.status === 422 ) {
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
                                if(result.status){
                                    $.notify({
                                        message: result.message,
                                        type: 'success'
                                    });

                                    setTimeout(function(){
                                        window.location = '{{ route('admin.patient.index') }}';
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


        $('#district_id, #village_id, #religion_id, #work_id, #info_id').select2({
            placeholder: "Pilih",
            allowClear: true
        });

        $('#city_id').select2({
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
                    Codebase.blocks('#my-block2', 'state_loading');
                },
                error: function() {
                    alert('An error has occurred');
                    Codebase.blocks('#my-block2', 'state_normal');
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

                        $("select" + elemen).val("{{ old('district_id') }}").trigger('change');
                    } else {
                        alert('data empty!');
                    }

                    Codebase.blocks('#my-block2', 'state_normal');
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
                    Codebase.blocks('#my-block2', 'state_loading');
                },
                error: function() {
                    alert('An error has occurred');
                    Codebase.blocks('#my-block2', 'state_normal');
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

                        $("select" + elemen).val("{{ old('village_id') }}").trigger('change');

                    } else {
                        alert('data empty!');
                    }

                    Codebase.blocks('#my-block2', 'state_normal');
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
</script>
@endsection
