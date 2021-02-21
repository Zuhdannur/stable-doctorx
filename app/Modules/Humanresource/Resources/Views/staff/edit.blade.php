@extends('backend.layouts.app')

@section('title', __('humanresource::labels.staff.management') . ' | ' . __('humanresource::labels.staff.create'))

@section('content')
<form class="js-validation-bootstrap form-vertical" method="post" action="{{ route('admin.humanresource.staff.update', $staff->id) }}" id="formGroup" enctype="multipart/form-data" autocomplete="off">
    @method('PATCH')
     @csrf
    <div class="block" id="my-block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Ubah Staff</h3>
        </div>
        <div class="block-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="mega-employee_id">NIK</label>
                            <input type="text" class="form-control" id="employee_id" name="employee_id" value="{{ $staff->employee_id ?? old('employee_id') }}" readonly="readonly">
                        </div>
                        <div class="col-6">
                            <label for="mega-role_id">Role</label>
                            <select class="form-control" id="role_id" name="role_id">
                                <option selected="selected" value="">Pilih</option>
                                @foreach ($roles as $item)
                                    <option value="{{ $item->id }}" {{ ( $item->id == ($staff->user->roles->first()->id ?? old('role_id'))) ? 'selected' : '' }}> {{ $item->title }} </option>
                                @endforeach    
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="mega-designation_id">Jabatan/Posisi</label>
                            <select class="form-control" id="designation_id" name="designation_id">
                                <option selected="selected" value="">Pilih</option>
                                @foreach ($designations as $item)
                                    <option value="{{ $item->id }}" {{ ( $item->id == ($staff->designation_id ?? old('designation_id'))) ? 'selected' : '' }}> {{ $item->name }} </option>
                                @endforeach    
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="mega-lastname">Departemen</label>
                            <select class="form-control" id="department_id" name="department_id">
                                <option selected="selected" value="">Pilih</option>
                                @foreach ($departments as $item)
                                    <option value="{{ $item->id }}" {{ ( $item->id == ($staff->department_id ?? old('department_id'))) ? 'selected' : '' }}> {{ $item->department_name }} </option>
                                @endforeach    
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="mega-firstname">Nama Lengkap</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ $staff->user->full_name ?? old('full_name') }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="mega-lastname">Tempat & Tanggal Lahir</label>
                            <div class="row">
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" value="{{ $staff->place_of_birth ?? old('place_of_birth') }}">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control datepicker" id="date_of_birth" name="date_of_birth" value="{{ $staff->date_of_birth_formated ?? old('date_of_birth') }}">
                                    </div>
                                </div>
                        </div>
                        <div class="col-6">
                            <label for="mega-religion_id">Agama</label>
                            <select class="form-control" id="religion_id" name="religion_id">
                                <option selected="selected" value="">Pilih</option>
                                @foreach ($religion as $item)
                                    <option value="{{ $item->id }}" {{ ( $item->id == ($staff->religion_id ?? old('religion_id'))) ? 'selected' : '' }}> {{ $item->name }} </option>
                                @endforeach    
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="mega-gender">Jenis Kelamin</label>
                            {!! Form::select('gender', ['M'=>'Laki - Laki','F'=>'Perempuan'], ($staff->gender ?? old('gender')), ['class' => 'form-control', 'placeholder' => 'Pilih Jenis Kelamin', 'required' => 'true']) !!}
                        </div>
                        <div class="col-6">
                            <label for="mega-blood_id">Golongan Darah</label>
                            <select class="form-control" name="blood_id" id="blood_id" class="form-control">
                                <option selected="selected" value="">Pilih</option>
                                @foreach ($bloodbank as $item)
                                    <option value="{{ $item->id }}" {{ ( $item->id == ($staff->blood_id ?? old('blood_id'))) ? 'selected' : '' }}> {{ $item->blood_name }} </option>
                                @endforeach  
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="mega-maritalstatus">Status Kawin</label>
                            <select class="form-control" id="marital_status" name="marital_status">
                                <option selected="selected" value="">Pilih</option>
                                @foreach ($maritalstatus as $item)
                                    <option value="{{ $item->id }}" {{ ( $item->id == ($staff->marital_status ?? old('marital_status'))) ? 'selected' : '' }}> {{ $item->name }} </option>
                                @endforeach  
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="mega-phone_number">Nomor Telepon</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $staff->phone_number ?? old('phone_number') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-12">
                            <label for="mega-address">Alamat</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ $staff->address ?? old('address') }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="mega-date_of_joining">Tanggal Join</label>
                            <input type="text" class="form-control datepicker" id="date_of_joining" name="date_of_joining" value="{{ $staff->date_of_joining_formated ?? old('date_of_joining') }}">
                        </div>
                        <div class="col-6">
                            <label for="mega-email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $staff->user->email ?? old('email') }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="mega-notes">Catatan</label>
                            <input type="text" class="form-control" id="note" name="note" value="{{ $staff->note ?? old('note') }}">
                        </div>
                        <div class="col-6 d-none">
                            <label for="mega-firstname">Foto</label>
                            <input type="file" id="avatar_location" name="avatar_location" class="dropify form-control" data-height="20" data-max-file-size="1M" data-allowed-file-extensions="jpg png jpeg" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('admin.humanresource.staff.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
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
                jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            success: function(e) {
                jQuery(e).closest('.form-group').removeClass('is-invalid');
                jQuery(e).remove();
            },
            rules: {
                'employee_id': {
                    required: true
                },
                'role_id': {
                    required: true
                },
                'designation_id': {
                    required: true
                },
                'department_id': {
                    required: true
                },
                'first_name': {
                    required: true
                },
                'date_of_joining': {
                    required: true,
                },
                'address': {
                    required: true,
                },
                'username': {
                    required: true,
                },
                'password': {
                    required: true,
                },
                'email': {
                    required: true,
                    email: true
                },
                'phone_number': {
                    minlength: 9,
                    maxlength: 12,
                    number: true
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
</script>
@endsection
