@extends('backend.layouts.app')

@section('title', __('patient::labels.prescription.management') . ' | ' . __('patient::labels.prescription.create'))

@section('content')
<input type="text" class="js-autocomplete form-control" id="example-autocomplete1" name="example-autocomplete1" placeholder="Countries..">
<div class="row row-deck">
    <div class="col-lg-12">
        <div class="block block-rounded">
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<form method="post" id="invoice_form" class="js-validation-bootstrap" autocomplete="off">
    <div class="block" id="my-block2">
        <div class="block-header block-header-default">
            <h3 class="block-title">Resep</h3>
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
                                <th> Kategori Obat </th>
                                <th> Nama Obat </th>
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
                                    <select class="form-control categoryProduct required" id="1select2" name="category_id[1]" data-placeholder="Pilih" style="width: 100%">
                                        {!! $product !!}
                                    </select>
                                </td>
                                <td>
                                    <select name="product[1]" id="1productselect2" class="form-control product required" data-placeholder="Pilih" style="width: 100%"><option></option></select>
                                </td>
                                <td>
                                    <input type="text" name='instruction[1]' class="form-control instruction required" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="block" id="my-block">
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
                                    <input type="text" name='diagnosis[1]' class="form-control diagnosis required" />
                                </td>
                                <td>
                                    <input type="text" name='diaginstruction[1]' class="form-control diaginstruction required" />
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

    $('#1select2').select2({
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
});

var i = 1;
var j = 1;

var validator = jQuery('.js-validation-bootstrap').validate({
    ignore: [],
    errorClass: 'invalid-feedback animated fadeInDown',
    errorElement: 'div',
    errorPlacement: function(error, e) {
        jQuery(e).parents('td').append(error);
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

                Codebase.blocks('#my-block2', 'state_normal');
            },
            success: function(result) {
                if (result.status) {
                    $.notify({
                        message: result.message,
                        type: 'success'
                    });

                    setTimeout(function() { // wait for 5 secs(2)
                        location.reload(); // then reload the page.(3)
                    }, 2000);
                } else {
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

function updateIds() {
    $('#tab_logic tbody tr').each(function(i, element) {
        var html = $(this).html();
        if (html != '') {
            $(this).find('.nomor').text((i+1));
            $(this).find('.categoryProduct').attr('name', 'category['+(i+1)+']');
            $(this).find('.product').attr('name', 'product['+(i+1)+']');
            $(this).find('.instruction').attr('name', 'instruction['+(i+1)+']');
        }
    });

    validator.resetForm();
}
updateIds();

function updateIds2() {
    $('#tab_logic2 tbody tr').each(function(i, element) {
        var html = $(this).html();
        if (html != '') {
            $(this).find('.nomor2').text((i+1));
            $(this).find('.diagnosis').attr('name', 'diagnosis['+(i+1)+']');
            $(this).find('.diaginstruction').attr('name', 'diaginstruction['+(i+1)+']');
        }
    });

    validator.resetForm();
}
updateIds2();

$(document).ready(function() {

    $(document).on('click', '.add', function() {
        var htmlDropdown = '<select class="form-control categoryProduct required" id="'+(i+1)+'select2" name="category_id['+(i+1)+']" data-placeholder="Pilih" style="width: 100%">{!! $categoryoption !!}</select>';
        var html = '';
        html += '<tr>';
        html += '<td><button type="button" name="remove" class="btn btn-sm btn-circle btn-outline-danger remove" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
        html += '<td class="nomor">'+(i+1)+'</td>';
        html += '<td>'+htmlDropdown+'</td>';
        html += '<td><select id="'+(i+1)+'productselect2" name="product['+(i+1)+']" class="form-control product required" data-placeholder="Pilih" style="width: 100%"><option></option></select></td>';
        html += '<td><input type="text" name="instruction['+(i+1)+']" class="form-control instruction required" /></td>';

        html += '</tr>';
        
        $('#tab_logic > tbody').append(html);

        $('#'+(i+1)+'select2').select2({
            placeholder: "Pilih"
        });

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

                Codebase.blocks('#my-block2', 'state_normal');
            },
            type: 'GET'
        });
    });

    $(document).on('click', '.add2', function() {
        var html = '';
        html += '<tr>';
        html += '<td><button type="button" name="remove2" class="btn btn-sm btn-circle btn-outline-danger remove2" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
        html += '<td class="nomor2">'+(j+1)+'</td>';
        html += '<td><input type="text" name="diagnosis['+(j+1)+']" class="form-control diagnosis required" /></td>';
        html += '<td><input type="text" name="diaginstruction['+(j+1)+']" class="form-control diaginstruction required" /></td>';

        html += '</tr>';
        
        $('#tab_logic2 > tbody').append(html);

        i++;
        updateIds();
    });

    $(document).on('click', '.remove2', function() {
        $(this).closest('tr').remove();
        updateIds2();
    });
});

var BeFormPlugins = function() {
    // Init jQuery AutoComplete example, for more examples you can check out https://github.com/Pixabay/jQuery-autoComplete
    var initAutoComplete = function(){
        // Init autocomplete functionality
        jQuery('.js-autocomplete').autoComplete({
            minChars: 1,
            source: function(term, suggest){
                term = term.toLowerCase();

                var countriesList  = ['Afghanistan','Albania','Algeria','Andorra','Angola','Anguilla','Antigua &amp; Barbuda','Argentina','Armenia','Aruba','Australia','Austria','Azerbaijan','Bahamas','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bermuda','Bhutan','Bolivia','Bosnia &amp; Herzegovina','Botswana','Brazil','British Virgin Islands','Brunei','Bulgaria','Burkina Faso','Burundi','Cambodia','Cameroon','Cape Verde','Cayman Islands','Chad','Chile','China','Colombia','Congo','Cook Islands','Costa Rica','Cote D Ivoire','Croatia','Cruise Ship','Cuba','Cyprus','Czech Republic','Denmark','Djibouti','Dominica','Dominican Republic','Ecuador','Egypt','El Salvador','Equatorial Guinea','Estonia','Ethiopia','Falkland Islands','Faroe Islands','Fiji','Finland','France','French Polynesia','French West Indies','Gabon','Gambia','Georgia','Germany','Ghana','Gibraltar','Greece','Greenland','Grenada','Guam','Guatemala','Guernsey','Guinea','Guinea Bissau','Guyana','Haiti','Honduras','Hong Kong','Hungary','Iceland','India','Indonesia','Iran','Iraq','Ireland','Isle of Man','Israel','Italy','Jamaica','Japan','Jersey','Jordan','Kazakhstan','Kenya','Kuwait','Kyrgyz Republic','Laos','Latvia','Lebanon','Lesotho','Liberia','Libya','Liechtenstein','Lithuania','Luxembourg','Macau','Macedonia','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Mauritania','Mauritius','Mexico','Moldova','Monaco','Mongolia','Montenegro','Montserrat','Morocco','Mozambique','Namibia','Nepal','Netherlands','Netherlands Antilles','New Caledonia','New Zealand','Nicaragua','Niger','Nigeria','Norway','Oman','Pakistan','Palestine','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Poland','Portugal','Puerto Rico','Qatar','Reunion','Romania','Russia','Rwanda','Saint Pierre &amp; Miquelon','Samoa','San Marino','Satellite','Saudi Arabia','Senegal','Serbia','Seychelles','Sierra Leone','Singapore','Slovakia','Slovenia','South Africa','South Korea','Spain','Sri Lanka','St Kitts &amp; Nevis','St Lucia','St Vincent','St. Lucia','Sudan','Suriname','Swaziland','Sweden','Switzerland','Syria','Taiwan','Tajikistan','Tanzania','Thailand','Timor L\'Este','Togo','Tonga','Trinidad &amp; Tobago','Tunisia','Turkey','Turkmenistan','Turks &amp; Caicos','Uganda','Ukraine','United Arab Emirates','United Kingdom','United States','Uruguay','Uzbekistan','Venezuela','Vietnam','Virgin Islands (US)','Yemen','Zambia','Zimbabwe'];
                var suggestions    = [];

                for (i = 0; i < countriesList.length; i++) {
                    if (~ countriesList[i].toLowerCase().indexOf(term)) suggestions.push(countriesList[i]);
                }

                suggest(suggestions);
            }
        });
    };

    return {
        init: function () {
            // Init jQuery AutoComplete example
            initAutoComplete();
        }
    };
}();

// Initialize when page loads
jQuery(function(){ BeFormPlugins.init(); });
</script>
@endsection