<div class="block" id="my-block2">
    <div class="block-header block-header-default">
        <h3 class="block-title">Resep</h3>
    </div>
    <div class="block-content block-content-full">
        <!-- Table -->
        <form method="post" id="invoice_form" class="js-validation-bootstrap" autocomplete="off">
            <div class="row clearfix">
                <div class="col-md-12">
                    <span id="error"></span>
                    <table class="table table-bordered table-hover table-striped table-vcenter" id="tab_logic">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;"> </th>
                                <th style="width: 50px;"> # </th>
                                <th> Kategori </th>
                                <th> Obat </th>
                                <th> Dosis </th>
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
                                        {!! $categoryoption !!}
                                    </select>
                                </td>
                                <td>
                                    <select name="product[1]" id="1productselect2" class="form-control product required" data-placeholder="Pilih" style="width: 100%"><option></option></select>
                                </td>
                                <td>
                                    <input type="text" name='dosigne[1]' class="form-control dosigne required" />
                                </td>
                                <td>
                                    <input type="text" name='instruction[1]' class="form-control text-right instruction required" />
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
        </form>
        <!-- END Table -->
    </div>
</div>

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
            url: "{{ route('admin.patient.perscription.store') }}",
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
            $(this).find('.dosigne').attr('name', 'dosigne['+(i+1)+']');
            $(this).find('.instruction').attr('name', 'instruction['+(i+1)+']');
        }
    });

    validator.resetForm();
}
updateIds();

$(document).ready(function() {

    $(document).on('click', '.add', function() {
        var htmlDropdown = '<select class="form-control categoryProduct required" id="'+(i+1)+'select2" name="category_id['+(i+1)+']" data-placeholder="Pilih" style="width: 100%">{!! $categoryoption !!}</select>';
        var html = '';
        html += '<tr>';
        html += '<td><button type="button" name="remove" class="btn btn-sm btn-circle btn-outline-danger remove" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
        html += '<td class="nomor">'+(i+1)+'</td>';
        html += '<td>'+htmlDropdown+'</td>';
        html += '<td><select id="'+(i+1)+'productselect2" name="product['+(i+1)+']" class="form-control product required" data-placeholder="Pilih" style="width: 100%"><option></option></select></td>';
        html += '<td><input type="text" name="dosigne['+(i+1)+']" class="form-control dosigne required" /></td>';
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
});
</script>