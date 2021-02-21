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
                    <th>@lang('patient::labels.patient.table.name')</th>
                    <th>@lang('patient::labels.patient.table.unique_id')</th>
                    <th>@lang('patient::labels.patient.table.phone_number')</th>
                    <th>@lang('patient::labels.patient.table.gender')</th>
                    <th>@lang('patient::labels.patient.table.dob')</th>
                    <th>@lang('patient::labels.patient.table.age')</th>
                    <th>@lang('labels.general.created_at')</th>
                    <th>@lang('labels.general.updated_at')</th>
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
                                            <div class="col-12">
                                                <label>Alamat</label>
                                                <textarea name="address" id="address" rows="4" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label>Photo</label>
                                                <input type="file" name="photo" id="photo" class="dropify form-control" data-height="20" data-max-file-size="1M" data-allowed-file-extensions="jpg png jpeg" />
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
                                                <input type="text" class="form-control datepicker" id="appointment_date" name="appointment_date">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label>Jam</label>
                                                <input type="text" class="js-masked-time form-control" id="appointment_time" name="appointment_time" value="00:00">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label>Agenda</label>
                                                <select class="form-control" id="appointment_type" name="appointment_type">
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
        Codebase.helpers(['masked-inputs']); 
    });
    $(function () {
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
                    data: 'patient_name',
                    name: 'patient_name',
                    className: 'font-w600'
                },
                {
                    data: 'patient_unique_id',
                    name: 'patient_unique_id',
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
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
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
                    'dob': {
                        required: true,
                    },
                    'address': {
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
                                    errorsHtml += '<ul class="list-group"><li class="list-group-item alert alert-danger">' + value + '</li></ul>';
                                });

                                swal({
                                    title: "Error " + x.status + ': ' + error,
                                    html: errorsHtml,
                                    width: 'auto',
                                    showCancelButton: false,
                                    closeOnConfirm: true,
                                    closeOnCancel: true,
                                    type: 'error'
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

    $('#staff_id, #room_id').select2({
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
                        optionSections += '<option value="' + value.id + '">' + value.employee_id + ' - ' + value.full_name + '</option>';
                    });

                    optionSections += '</select></label>';

                    $("select" + elemen).html(optionSections);
                } else {
                    $("select"+elemen).html(optionSections);

                    $.notify({
                        message: data.message,
                        type: 'danger'
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
                        optionSections += '<option value="' + value.id + '">' + value.room_name + ' - ' + value.group_name + '</option>';
                    });

                    optionSections += '</select></label>';

                    $("select" + elemen).html(optionSections);
                } else {
                    $("select"+elemen).html(optionSections);

                    $.notify({
                        message: data.message,
                        type: 'danger'
                    });
                }

                Codebase.blocks('#my-block2', 'state_normal');
            },
            type: 'GET'
        });
    }

    $('body').on('change', '#appointment_type', function() {
        var role_id = $(this).find(':selected').attr('data-role-id');
        var room_group_id = $(this).find(':selected').attr('data-group-id');

        if (role_id) {
            populateStaff(role_id, '#staff_id');
        }

        if (room_group_id) {
            populateRoom(room_group_id, '#room_id');
        }
    });
</script>
@endsection