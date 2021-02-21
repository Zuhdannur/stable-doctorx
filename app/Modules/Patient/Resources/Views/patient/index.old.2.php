@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('patient::labels.patient.management'))

@section('content')
<div class="block" id="my-block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('patient::labels.patient.management')</h3>
        <div class="block-options">
            <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                <button class="btn btn-sm btn-alt-info" title="@lang('labels.general.create_new')" data-toggle="modal" data-target="#modalForm" data-backdrop="static" data-keyboard="false" data-key="logo_square" data-title="@lang('labels.general.create_new')">
                    <i class="fa fa-random"></i> @lang('labels.general.create_new')
                </button>
            </div>
        </div>
    </div>
    <div class="block-content block-content-full">
        <table id="table" class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination" width="100%">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>@lang('patient::labels.patient.table.unique_id')</th>
                    <th>@lang('patient::labels.patient.table.name')</th>
                    <th>@lang('patient::labels.patient.table.phone_number')</th>
                    <th>@lang('patient::labels.patient.table.gender')</th>
                    <th>@lang('patient::labels.patient.table.dob')</th>
                    <th>@lang('patient::labels.patient.table.age')</th>
                    <th class="text-center" style="width: 100px;">@lang('labels.general.actions')</th>
                </tr>
            </thead>
           
        </table>
    </div>
</div>

<div class="modal fade" role="dialog" aria-labelledby="modal-popout" aria-hidden="true" id="modalForm">
    <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
        <div class="modal-content">
            <form class="js-validation-bootstrap" method="post" id="formGeneral" autocomplete="off">
                <div class="block block-themed block-transparent mb-0" id="my-block2">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title modal-title"></h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <div id="status_settings"></div>
                        <div class="row">
                            <div class="col-8">
                                <div class="block mb-0">
                                    <div class="block-content">
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <label>Nama Lengkap</label>
                                                <input type="text" class="form-control" id="patient_name" name="patient_name">
                                            </div>
                                            <div class="col-6">
                                                <label>Jenis Kelamin</label>
                                                {!! Form::select('gender', ['M'=>'Laki - Laki','F'=>'Perempuan'], old('gender'), ['class' => 'form-control', 'placeholder' => 'Pilih Jenis Kelamin', 'required' => 'true']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <label>Golongan Darah</label>
                                                <select class="form-control" id="blood_id" name="blood_id">
                                                    <option selected="selected" value="">Pilih</option>
                                                    @foreach ($bloodbank as $item)
                                                        <option value="{{ $item->id }}" {{ ( $item->id == old('blood_group')) ? 'selected' : '' }}> {{ $item->blood_name }} </option>
                                                    @endforeach    
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label>No. Telepon</label>
                                                <input type="text" class="form-control" id="phone_number" name="phone_number">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <label>Tempat Lahir</label>
                                                <input type="text" class="form-control" id="birth_place" name="birth_place" value="{{ old('birth_place') }}">
                                            </div>
                                            <div class="col-6">
                                                <label>Tanggal Lahir</label>
                                                <input type="text" class="js-masked-date form-control" id="dob" name="dob" placeholder="dd/mm/yyyy" value="{{ old('dob') }}">
                                                
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-6">
                                                <label>Kota</label><br>
                                                <select class="form-control" id="city_id" name="city_id" style="width: 100%" data-placeholder="Pilih">
                                                    <option></option>
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}" {{ ( $city->id == old('city_id')) ? 'selected' : '' }}> {{ $city->name }} </option>
                                                    @endforeach    
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label>Kecamatan</label>
                                                <select class="form-control" name="district_id" id="district_id" class="form-control" style="width: 100%" data-placeholder="Pilih">
                                                    <option></option>
                                                </select>
                                                
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-6">
                                                <label>Desa</label><br>
                                                <select class="form-control" id="village_id" name="village_id" style="width: 100%" data-placeholder="Pilih">
                                                    <option></option>  
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label>Kode Pos</label>
                                                <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code') }}">
                                                
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <label>Agama</label><br>
                                                <select class="form-control" id="religion_id" name="religion_id" style="width: 100%" data-placeholder="Pilih">
                                                    <option></option>
                                                    @foreach ($religions as $religion)
                                                        <option value="{{ $religion->id }}"> {{ $religion->name }} </option>
                                                    @endforeach    
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label>Alamat</label>
                                                <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <label>Pekerjaan</label><br>
                                                <select class="form-control" id="work_id" name="work_id" style="width: 100%" data-placeholder="Pilih">
                                                    <option></option>
                                                    @foreach ($works as $work)
                                                        <option value="{{ $work->id }}"> {{ $work->name }} </option>
                                                    @endforeach    
                                                </select>
                                            </div>
                                            <div class="col-6">
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
                                            <div class="col-6">
                                                <label>Hobi</label><br>
                                                <input type="text" class="form-control" id="hobby" name="hobby" value="{{ old('hobby') }}">
                                            </div>
                                            <div class="col-6">
                                                <label>Flag Info</label>
                                                <select class="form-control" id="patient_flag_id" name="patient_flag_id" style="width: 100%" data-placeholder="Pilih">
                                                    <option></option>
                                                    @foreach ($flags as $flag)
                                                        <option class="font-w600 text-{{ $flag->color_code }}" value="{{ $flag->id }}"> {{ $flag->name }} </option>
                                                    @endforeach    
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="block mb-0 bg-body-dark">
                                    <div class="block-content">
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label>Tanggal Jadwal</label>
                                                <input type="text" class="form-control datepicker" id="queue_date" name="queue_date">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label>Jam</label>
                                                <input type="text" class="js-masked-time form-control" id="queue_time" name="queue_time">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label>Servis</label>
                                                <select class="form-control" id="admission_type" name="admission_type">
                                                    <option selected="selected" value="">Pilih</option>
                                                    @foreach ($appointmenttype as $item)
                                                        <option value="{{ $item->id }}" data-role-id="{{ $item->role_id }}" data-group-id="{{ $item->room_group_id }}" {{ ( $item->id == old('doctor_id')) ? 'selected' : '' }}> {{ $item->name }} </option>
                                                    @endforeach    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label>PIC</label><br>
                                                <select class="form-control" id="staff_id" name="staff_id" style="width: 100%" data-placeholder="Pilih">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label>Ruangan/Tempat</label><br>
                                                <select class="form-control" id="room_id" name="room_id" style="width: 100%" data-placeholder="Pilih">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label>Status</label>
                                                <select class="form-control" id="status_id" name="status_id">
                                                    <option selected="selected" value="">Pilih</option>
                                                    @foreach ($appointmentstatus as $item)
                                                        <option value="{{ $item->id }}" {{ ( $item->id == old('status_id')) ? 'selected' : '' }}> {{ $item->name }} </option>
                                                    @endforeach    
                                                </select>
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

<script>
    jQuery(function(){ 
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

        var dt = $('#table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ordering: false,
            pageLength: 10,
            ajax: '{!! route('admin.patient.index') !!}',
            language: {
                url: "{!! URL::asset('js/plugins/datatables/i18n/'.str_replace('_', '-', app()->getLocale()).'.json') !!}"
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'id',
                    width: "5%",
                    orderable: false, 
                    searchable: false
                },
                {
                    data: 'patient_unique_id',
                    name: 'patient_unique_id',
                },
                {
                    data: 'patient_name',
                    name: 'patient_name',
                    className: 'font-w600'
                },
                {
                    data: 'phone_number',
                    name: 'phone_number',
                },
                {
                    data: 'gender',
                    name: 'gender',
                },
                {
                    data: 'dob',
                    name: 'dob',
                },
                {
                    data: 'age',
                    name: 'age',
                },
                {
                    data: 'action',
                    name: 'action',
                    width: "8%",
                    className: 'text-right',
                    orderable: false, 
                    searchable: false
                }
            ]
        }).on('processing.dt', function (e, settings, processing) {
            if (processing) {
                Codebase.blocks('#my-block', 'state_loading');
            } else {
                Codebase.blocks('#my-block', 'state_normal');
            }
        }).on('draw.dt', function () {
            $('[data-toggle="tooltip"]').tooltip();
            addDeleteForms();
        } );

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

                                Codebase.blocks('#my-block2', 'state_normal');
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

        $('#modalForm').on('show.bs.modal', function(event) {
            var key = $(event.relatedTarget).attr('data-key');
            var title = $(event.relatedTarget).attr('data-title');
            
            $(this).find('.modal-title').text(title);
            $(this).find('#key').val(key);
        });

        $('#staff_id, #room_id, #city_id, #district_id, #village_id, #religion_id, #work_id, #info_id').select2({
            placeholder: "Pilih",
            allowClear: true
        });

        populateStaff = function(roleId, elemen) {
            if (!roleId) {
                return false;
            }

            var url = '{{ route("admin.humanresource.staff.getbyrole", ":id") }}';

            url = url.replace(':id', roleId);

            $.ajax({
                url: url,
                beforeSend: function(xhr) {
                    Codebase.blocks('#my-block2', 'state_loading');
                },
                error: function() {
                    alert('An error has occurred');
                    Codebase.blocks('#my-block2', 'state_normal');
                },
                success: function(data) {
                    var result = data.data;
                    var optionSections = '<option></option>';
                    if (result.length > 0) {
                        
                        $.each(result, function(key, value) {
                            optionSections += '<option value="' + value.staff_id + '">' + value.employee_id + ' - ' + value.full_name + '</option>';
                        });

                        optionSections += '</select></label>';

                        $("select" + elemen).html(optionSections);
                    } else {
                        $("select"+elemen).html(optionSections);

                        $.alert({
                            icon: 'fa fa-warning',
                            type: 'red',
                            content: data.message,
                        });
                    }

                    Codebase.blocks('#my-block2', 'state_normal');
                },
                type: 'GET'
            });
        }

        populateRoom = function(groupId, elemen) {
            if (!groupId) {
                return false;
            }

            var url = '{{ route("admin.room.getbygroupid", ":id") }}';

            url = url.replace(':id', groupId);

            $.ajax({
                url: url,
                beforeSend: function(xhr) {
                    Codebase.blocks('#my-block2', 'state_loading');
                },
                error: function() {
                    alert('An error has occurred');
                    Codebase.blocks('#my-block2', 'state_normal');
                },
                success: function(data) {
                    var result = data.data;
                    var optionSections = '<option></option>';
                    if (result.length > 0) {
                        
                        $.each(result, function(key, value) {
                            optionSections += '<option value="' + value.room_id + '">' + value.room_name + ' - ' + value.group_name + '</option>';
                        });

                        optionSections += '</select></label>';

                        $("select" + elemen).html(optionSections);
                    } else {
                        $("select"+elemen).html(optionSections);

                        $.alert({
                            icon: 'fa fa-warning',
                            type: 'red',
                            content: data.message,
                        });
                    }

                    Codebase.blocks('#my-block2', 'state_normal');
                },
                type: 'GET'
            });
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

        $('body').on('change', '#admission_type', function() {
            var role_id = $(this).find(':selected').attr('data-role-id');
            var room_group_id = $(this).find(':selected').attr('data-group-id');

            if (role_id) {
                populateStaff(role_id, '#staff_id');
            }

            if (room_group_id) {
                populateRoom(room_group_id, '#room_id');
            }
        });

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