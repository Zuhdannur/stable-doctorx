@extends('backend.layouts.app')

@section('title', __('patient::labels.prescription.management') . ' | ' . __('patient::labels.prescription.create'))

@section('content')
<div class="row row-deck">
    <div class="col-lg-12">
        <div class="block block-rounded my-block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Data Pasien</h3>
                <div class="block-options">
                    <a href="{{ route('admin.patient.appointment.index') }}" class="btn btn-sm btn-secondary mr-5 mb-5">
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
                                <span class="text-black text-primary font-w600">{{ $appointment->appointment_no }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Pasien ID</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->patient_unique_id }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Pasien</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->patient_name }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Usia</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->age }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Hobi</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->hobby }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Telepon</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->phone_number }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Tgl Lahir</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->dob }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Jenis Kelamin</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->gender }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Gol. Darah</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->blood->blood_name }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Flag</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->flag['name'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left" colspan="6">
                                <span class="text-muted">Problem: {{ $appointment->notes }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<form method="post" id="prescription_form" class="js-validation-bootstrap" autocomplete="off" enctype="multipart/form-data">
    <input type="hidden" name="appid" value="{{ $appointment->id }}">
    <div class="block my-block" id="my-block2">
        <div class="block-header block-header-default">
            <h3 class="block-title">Catatan</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {{ html()->label('Keluhan')
                            ->class('col-md-2 form-control-label')
                            ->for('code') }}
                        <div class="col-md-10">
                            <textarea class="form-control js-simplemde" id="complaint" name="complaint" required="required" value=""></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {{ html()->label('Riwayat Perawatan dan Alergi')
                            ->class('col-md-2 form-control-label')
                            ->for('code') }}
                        <div class="col-md-10">
                            <textarea class="form-control js-simplemde" id="treatment_history" name="treatment_history" required></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="block my-block" id="my-block2">
        <div class="block-header block-header-default">
            <h3 class="block-title">Foto</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <input type="file" name="file_foto" id="file_foto" class="dropify form-control" data-max-file-size="1M" data-allowed-file-extensions="jpg png jpeg" />
            </div>
        </div>
    </div>

    <div class="block my-block" id="my-block2">
        <div class="block-header block-header-default">
            <h3 class="block-title">Resep/Obat</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row clearfix">
                <div class="col-md-12">
                    <span id="error"></span>
                    <table class="table table-bordered table-hover table-striped table-vcenter" id="tab_logic">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;"> </th>
                                <th style="width: 50px;"> # </th>
                                <th> Resep/Obat </th>
                                <th> Instruksi </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><button type="button" class="btn btn-sm btn-circle btn-outline-success add" title="Tambah Item">
                                    <i class="fa fa-plus"></i>
                                </button></td>
                                <td class="nomor">
                                    1
                                </td>
                                <td>
                                    <input type="text" id="product1" name='product[1]' class="form-control product js-autocomplete required" />
                                </td>
                                <td>
                                    <input type="text" name='instruction[1]' class="form-control instruction" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="block my-block" id="my-block3">
        <div class="block-header block-header-default">
            <h3 class="block-title">Tindakan & Catatan</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row clearfix">
                <div class="col-md-12">
                    <span id="error"></span>
                    <table class="table table-bordered table-hover table-striped table-vcenter" id="tab_logic3">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;"> </th>
                                <th style="width: 50px;"> # </th>
                                <th> Tindakan </th>
                                <th> Keterangan </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><button type="button" class="btn btn-sm btn-circle btn-outline-success add3" title="Tambah Tindakan">
                                    <i class="fa fa-plus"></i>
                                </button></td>
                                <td class="nomor3">
                                    1
                                </td>
                                <td>
                                    <input type="text" id="service1" name='service[1]' class="form-control service js-autocomplete required" />
                                </td>
                                <td>
                                    <input type="text" name='servicenotes[1]' class="form-control servicenotes" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="block my-block" id="my-block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Diagnosa</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row clearfix">
                <div class="col-md-12">
                    <span id="error"></span>
                    <table class="table table-bordered table-hover table-striped table-vcenter" id="tab_logic2">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;"> </th>
                                <th style="width: 50px;"> # </th>
                                <th> Diagnosa </th>
                                <th> Instruksi </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><button type="button" class="btn btn-sm btn-circle btn-outline-success add2" title="Tambah Item">
                                    <i class="fa fa-plus"></i>
                                </button></td>
                                <td class="nomor2">
                                    1
                                </td>
                                <td>
                                    <select name="diagnosis[1]" id="1diagnosis2" class="form-control diagnosis required" data-placeholder="Pilih" style="width: 100%">
                                        <option></option>
                                        {!! $datadiagnoseitem !!}
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name='diaginstruction[1]' class="form-control diaginstruction" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-success"><span class="fa fa-check"></span> Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
jQuery(function () {
    Codebase.layout('sidebar_mini_on');
    Codebase.helpers(['simplemde']);

    $('#1diagnosis2').select2({
        placeholder: "Pilih"
    });

    $('.datepicker').datepicker({
        todayHighlight: true,
        format: "{{ setting()->get('date_format_js') }}",
        weekStart: 1,
        language: "{!! str_replace('_', '-', app()->getLocale()) !!}",
        daysOfWeekHighlighted: "0,6",
        autoclose: true
    });

    var initAutoComplete = function(product1){
        // alert(product1);
        jQuery('#'+product1).autoComplete({
            minChars: 1,
            source: function(term, suggest){
                term = term.toLowerCase();

                var dataList  = {!! json_encode($product) !!};
                var suggestions    = [];

                for (i = 0; i < dataList.length; i++) {
                    if (~ dataList[i].toLowerCase().indexOf(term)) suggestions.push(dataList[i]);
                }

                suggest(suggestions);
            }
        });
    };

    var initAutoComplete2 = function(service1){
        // alert(service1);
        jQuery('#'+service1).autoComplete({
            minChars: 1,
            source: function(term, suggest){
                term = term.toLowerCase();

                var dataList  = {!! json_encode($service) !!};
                var suggestions    = [];

                for (i = 0; i < dataList.length; i++) {
                    if (~ dataList[i].toLowerCase().indexOf(term)) suggestions.push(dataList[i]);
                }

                suggest(suggestions);
            }
        });
    };

    initAutoComplete('product1');
    initAutoComplete2('service1');

    $('#service_id').select2({
        placeholder: "Pilih",
        allowClear: true
    });

    var i = 1;
    var j = 1;
    var k = 1;

    var validator = jQuery('.js-validation-bootstrap').validate({
        ignore: [],
        errorClass: 'invalid-feedback animated fadeInDown',
        errorElement: 'div',
        focusInvalid: true,
        /*errorPlacement: function(error, e) {
            jQuery(e).closest('td').append(error);
        },*/
        invalidHandler: function(form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {                    
                var firstInvalidElement = $(validator.errorList[0].element);
                $('html,body').scrollTop(firstInvalidElement.offset().top);
                //firstInvalidElement.focus();
            }
        },
        highlight: function(e) {
            jQuery(e).closest('td').removeClass('is-invalid').addClass('is-invalid');
        },
        success: function(e) {
            jQuery(e).closest('td').removeClass('is-invalid');
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
            $.ajax({
                type: "POST",
                url: "{{ route('admin.patient.prescription.store') }}",
                data: new FormData(form),
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function(xhr) {
                    Codebase.blocks('.my-block', 'state_loading');
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

                    Codebase.blocks('.my-block', 'state_normal');
                },
                success: function(result) {
                    if (result.status) {
                        $.notify({
                            message: result.message,
                            type: 'success'
                        });

                        setTimeout(function() {
                            window.location = '{{ route('admin.patient.appointment.index') }}';
                        }, 2000);
                    } else {
                        $.alert({
                            title: 'Error',
                            icon: 'fa fa-warning',
                            type: 'orange',
                            content: result.message,
                        });
                    }

                    Codebase.blocks('.my-block', 'state_normal');
                }
            });
            return false; // required to block normal submit since you used ajax
        }

    });

    function updateIds() {
        $('#tab_logic tbody tr').each(function(i, element) {
            var html = $(this).html();
            if (html != '') {
                $(this).find('.nomor').text((i+1));
                $(this).find('.product').attr('name', 'product['+(i+1)+']');
                $(this).find('.instruction').attr('name', 'instruction['+(i+1)+']');
            }
        });

        validator.resetForm();
    }
    updateIds();

    function updateIds2() {
        $('#tab_logic2 tbody tr').each(function(j, element) {
            var html2 = $(this).html();
            if (html2 != '') {
                $(this).find('.nomor2').text((j+1));
                $(this).find('.diagnosis').attr('name', 'diagnosis['+(j+1)+']');
                $(this).find('.diaginstruction').attr('name', 'diaginstruction['+(j+1)+']');
            }
        });

        validator.resetForm();
    }
    updateIds2();

    function updateIds3() {
        $('#tab_logic3 tbody tr').each(function(k, element) {
            var html3 = $(this).html();
            if (html3 != '') {
                $(this).find('.nomor3').text((k+1));
                $(this).find('.service').attr('name', 'service['+(k+1)+']');
                $(this).find('.servicenotes').attr('name', 'servicenotes['+(k+1)+']');
            }
        });

        validator.resetForm();
    }
    updateIds3();

    $(document).ready(function() {

        $(document).on('click', '.add', function() {
            var html = '';
            html += '<tr>';
            html += '<td><button type="button" name="remove" class="btn btn-sm btn-circle btn-outline-danger remove" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
            html += '<td class="nomor">'+(i+1)+'</td>';
            html += '<td><input type="text" id="product'+(i+1)+'" name="product['+(i+1)+']" class="form-control product js-autocomplete required" /></td>';
            html += '<td><input type="text" name="instruction['+(i+1)+']" class="form-control instruction" /></td>';

            html += '</tr>';
            
            $('#tab_logic > tbody').append(html);

            $('#'+(i+1)+'select2').select2({
                placeholder: "Pilih"
            });

            initAutoComplete('product'+(i+1));

            i++;
            updateIds();

        });

        $(document).on('click', '.remove', function() {
            $(this).closest('tr').remove();
            updateIds();
        });

        $("#tab_logic").on('change', '.categoryProduct',function () {
            var element = $(this).closest('tr');
        
            var catId = this.value;
            if (!catId) {
                return false;
            }

            var url = '{{ route("admin.product.getbycategory", ":id") }}';

            url = url.replace(':id', catId);

            $.ajax({
                url: url,
                beforeSend: function(xhr) {
                    Codebase.blocks('.my-block', 'state_loading');
                },
                error: function() {
                    alert('An error has occurred');
                    Codebase.blocks('.my-block', 'state_normal');
                },
                success: function(data) {
                    var result = data.data;
                    var optionSections = '<option></option>';
                    if (result.length > 0) {

                        $.each(result, function(key, value) {
                            optionSections += '<option value="' + value.id + '" data-price="' + value.price + '">' + value.name + '</option>';
                        });

                        optionSections += '</select></label>';

                        element.find("select.product").html(optionSections);
                    } else {
                        element.find("select.product").html(optionSections);

                        $.alert({
                            icon: 'fa fa-warning',
                            type: 'red',
                            content: data.message,
                        });
                    }

                    element.find("select.product").select2({
                        placeholder: "Pilih"
                    });

                    Codebase.blocks('.my-block', 'state_normal');
                },
                type: 'GET'
            });
        });

        $(document).on('click', '.add2', function() {
            var html2 = '';
            html2 += '<tr>';
            html2 += '<td><button type="button" name="remove2" class="btn btn-sm btn-circle btn-outline-danger remove2" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
            html2 += '<td class="nomor2">'+(j+1)+'</td>';
            html2 += '<td><select id="'+(j+1)+'diagnosis" name="diagnosis['+(j+1)+']" class="form-control diagnosis required" data-placeholder="Pilih" style="width: 100%"><option></option>{!! $datadiagnoseitem !!}</select></td>';
            html2 += '<td><input type="text" name="diaginstruction['+(j+1)+']" class="form-control diaginstruction" /></td>';

            html2 += '</tr>';
            
            $('#tab_logic2 > tbody').append(html2);

            $('#'+(j+1)+'diagnosis').select2({
                placeholder: "Pilih"
            });

            j++;
            updateIds2();
        });

        $(document).on('click', '.remove2', function() {
            $(this).closest('tr').remove();
            updateIds2();
        });

        $(document).on('click', '.add3', function() {
            var html3 = '';
            html3 += '<tr>';
            html3 += '<td><button type="button" name="remove3" class="btn btn-sm btn-circle btn-outline-danger remove3" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
            html3 += '<td class="nomor3">'+(k+1)+'</td>';
            html3 += '<td><input type="text" id="service'+(k+1)+'" name="service['+(k+1)+']" class="form-control service required" /></td>';
            html3 += '<td><input type="text" name="servicenotes['+(k+1)+']" class="form-control servicenotes" /></td>';

            html3 += '</tr>';
            
            $('#tab_logic3 > tbody').append(html3);

            initAutoComplete2('service'+(k+1));

            k++;
            updateIds3();
        });

        $(document).on('click', '.remove3', function() {
            $(this).closest('tr').remove();
            updateIds3();
        });
    });

    $('.dropify').dropify({
        messages: {
            'default': 'Seret dan taruh file di sini atau klik',
            'replace': 'Seret dan lepas atau klik untuk mengganti',
            'remove': 'Hapus',
            'error': 'Ups, terjadi kesalahan.'
        },
        error: {
            'fileSize': 'Ukuran file terlalu besar (@{{ value }} maksimal).',
            'minWidth': 'Lebar gambar terlalu kecil (@{{ value }}}px minimal).',
            'maxWidth': 'Lebar gambar terlalu besar (@{{ value }}}px maksimal).',
            'minHeight': 'Ketinggian gambar terlalu kecil (@{{ value }}}px minimal).',
            'maxHeight': 'Tinggi gambar terlalu besar (@{{ value }}px maksimal).',
            'imageFormat': 'Format gambar tidak diperbolehkan (@{{ value }} saja).',
            'fileExtension': 'Format file tidak diperbolehkan (@{{ value }} saja).'
        }
    });
});
</script>
@endsection