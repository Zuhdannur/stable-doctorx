@extends('backend.layouts.app')

@section('title', app_name().' | '.__('product::menus.supplier.create'))

@section('content')
    <div class="block" id="my-block2">

        {{-- content header --}}
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('product::labels.supplier.management') <small class="text-muted">@lang('product::labels.supplier.create')</small></h3>
        </div>

        {{-- content form --}}
        <div class="block-content">
            <form class="js-validation-bootstrap" method="post" id="formGeneral" autocomplete="off">
                <div class="row mt-4">
                    <div class="col">
                        <h3 class="block-title mt-4 mb-4"><i class="fa fa-user-o"></i> Informasi Supplier</h3>
                        <div class="form-group row">
                            <div class="col-4">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" id="supplier_name" name="supplier_name">
                            </div>
                            <div class="col-4">
                                <label>Jenis Kelamin</label>
                                {!! Form::select('gender', ['M'=>'Laki - Laki','F'=>'Perempuan'], old('gender'), ['class' => 'form-control', 'placeholder' => 'Pilih Jenis Kelamin', 'required' => 'true','id' => 'gender']) !!}
                            </div>
                            <div class="col-4">
                                <label>No. Telepon</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-4">
                                <label>Alamat Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="col-4">
                                <label>Tempat Lahir</label>
                                <input type="text" class="form-control" id="birth_place" name="birth_place" value="{{ old('birth_place') }}">
                            </div>
                            <div class="col-4">
                                <label>Tanggal Lahir</label>
                                <input type="text" class="js-masked-date form-control" id="dob" name="dob" placeholder="dd/mm/yyyy" value="{{ old('dob') }}">
                            </div>
                        </div>

                        <h3 class="block-title mt-4 mb-4"><i class="fa fa-building-o"></i> Informasi Perusahaan</h3>

                        <div class="form-group row">
                            <div class="col-4">
                                <label>Nama Perusahaan</label>
                                <input type="text" class="form-control" id="company_name" name="company_name">
                            </div>
                            <div class="col-4">
                                <label>No. Tlp Perusahaan</label>
                                <input type="text" class="form-control" id="company_phone_number" name="company_phone_number">
                            </div>
                            <div class="col-4">
                                <label>Kota</label>
                                <br>
                                <select class="form-control" id="company_city_id" name="company_city_id" style="width: 100%" data-placeholder="Pilih">
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                        
                            <div class="col-4">
                                <label>Kecamatan</label>
                                <select class="form-control" name="company_district_id" id="company_district_id" class="form-control" style="width: 100%" data-placeholder="Pilih">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label>Desa</label>
                                <br>
                                <select class="form-control" id="company_village_id" name="company_village_id" style="width: 100%" data-placeholder="Pilih">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label>Alamat Perusahaan</label>
                                <input type="text" class="form-control" id="company_address" name="company_address">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col">
                        {{ form_cancel(route('admin.product.supplier'), __('buttons.general.cancel')) }}
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

            $('#gender').select2({
                placeholder: "Pilih",
            });

            // submit handler
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
                            'supplier_name': {
                                required: true
                            },
                            'gender': {
                                required: true
                            },
                            'phone_number': {
                                required: true,
                                number: true
                            },
                            'email': {
                                required: true,
                                email: true,
                            },
                            'birth_place': {
                                required: true,
                            },
                            'dob': {
                                required: true,
                                dateITA: true
                            },
                            'company_name': {
                                required: true,
                            },
                            'company_phune_number': {
                                required: true,
                                number: true
                            },
                            'company_address': {
                                required: true,
                            },
                            'company_city_id': {
                                required: true,
                            },
                            'company_district_id': {
                                required: true,
                            },
                            'company_village_id': {
                                required: true,
                            }
                        },
                        submitHandler: function(form) {
                            $.ajax({
                                type: "POST",
                                url: "{{ route('admin.product.supplier.save') }}",
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
                                            window.location = '{{ route('admin.product.supplier') }}';
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
            // end submit handler

            $('#company_district_id, #company_village_id').select2({
                placeholder: "Pilih",
                allowClear: true
            });

            $('#company_city_id').select2({
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
                allowClear: true,
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
                        // Codebase.blocks('#my-block2', 'state_loading');
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

                        } else {
                            alert('data empty!');
                        }

                        Codebase.blocks('#my-block2', 'state_normal');
                    },
                    type: 'GET'
                });
            }

            $('body').on('change', '#company_city_id', function() {
                var city_id = this.value;

                if (city_id) {
                    $('#company_village_id').val('').trigger('change');
                    populateDistrict(city_id, '#company_district_id');
                }

            });

            $('body').on('change', '#company_district_id', function() {
                var district_id = this.value;

                if (district_id) {
                    populateVillages(district_id, '#company_village_id');
                }

            });
        })
    </script>
@endsection