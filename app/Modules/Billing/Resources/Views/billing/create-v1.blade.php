@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('billing::labels.billing.management'))

@section('content')
<div class="block" id="my-block2">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('billing::labels.billing.create')</h3>
        <div class="block-options">
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
        </div>
    </div>
    <div class="block-content block-content-full">
        <!-- Table -->
        <form method="post" id="invoice_form" class="js-validation-bootstrap" autocomplete="off">
            <!-- Invoice Info -->
            <input type="hidden" name="qId" id="qId" value="{{ $qid }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="mega-firstname">Pasien</label>
                            <select class="form-control required" id="patient_id" name="patient_id" data-parsley-required="true" data-placeholder="Pilih" style="width: 100%">
                                <option></option>
                                @foreach ($patients as $patient)
                                    @if ($pid == $patient->patient_unique_id)
                                          <option value="{{ $patient->id }}" selected> {{ $patient->patient_unique_id }} - {{ $patient->patient_name }} </option>
                                    @else
                                          <option value="{{ $patient->id }}"> {{ $patient->patient_unique_id }} - {{ $patient->patient_name }} </option>
                                    @endif
                                    
                                @endforeach    
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="mega-lastname">Tanggal</label>
                            <input type="text" class="form-control datepicker required" id="date" name="date" value="{{ old('date') }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-none">
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="mega-lastname">Actor Type</label>
                            
                        </div>
                        <div class="col-6">
                            <label for="mega-lastname">Actor Name</label>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Invoice Info -->
            <div class="row clearfix">
                <div class="col-md-12">
                    <span id="error"></span>
                    <table class="table table-bordered table-hover table-striped table-vcenter" id="tab_logic">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;"> </th>
                                <th style="width: 50px;"> # </th>
                                <th> Item </th>
                                <th class="text-center" style="width: 100px;"> Qty </th>
                                <th class="text-right" style="width: 200px;"> Harga </th>
                                <th class="text-right" style="width: 200px;"> Total </th>
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
                                    <select name="product[1]" id="1productselect2" class="form-control product required" data-placeholder="Pilih" style="width: 100%">
                                        <option></option>
                                        {!! $dataproducts !!}
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name='qty[1]' placeholder='0' class="form-control qty required" min="1" />
                                </td>
                                <td>
                                    <input type="number" name='price[1]' placeholder='0' class="form-control text-right price required" min="1"/>
                                </td>
                                <td>
                                    <input type="text" name='total[1]' placeholder='0' class="form-control tanpa-rupiah text-right total" readonly/>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="font-weight-bold text-right">Sub Total</td> 
                                <td>
                                    <input type="text" name='sub_total' placeholder='0' class="form-control tanpa-rupiah text-right" id="sub_total" readonly/>
                                </td>
                            </tr>
                            <tr class="d-none">
                                <td colspan="5" class="font-weight-bold text-right">Tax %</td> 
                                <td>
                                    <input type="text" class="form-control nomor-aja text-right" name="tax" id="tax" placeholder="0">
                                </td>
                            </tr>
                            <tr class="d-none">
                                <td colspan="5" class="font-weight-bold text-right">Jumlah Tax</td> 
                                <td>
                                    <input type="text" name='tax_amount' id="tax_amount" placeholder='0' class="form-control tanpa-rupiah text-right" readonly/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" class="font-weight-bold text-right">Diskon %</td> 
                                <td>
                                    <input type="number" class="form-control text-right" name="discount" id="discount" placeholder="0">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" class="font-weight-bold text-right">Jumlah Diskon</td> 
                                <td>
                                    <input type="text" name='discount_amount' id="discount_amount" placeholder='0' class="form-control tanpa-rupiah text-right" readonly/>
                                </td>
                            </tr>
                            <tr class="table-warning">
                                <td colspan="5" class="font-weight-bold text-right">Total</td> 
                                <td>
                                    <input type="text" name='total_amount' id="total_amount" placeholder='0' class="form-control font-w700 tanpa-rupiah text-right" readonly/>
                                </td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="5" class="font-weight-bold text-right">Jumlah Diterima</td> 
                                <td>
                                    <input type="number" name='total_receive' id="total_receive" placeholder='0' class="form-control font-w700 text-right required"/>
                                </td>
                            </tr>
                            <tr id="kembalianTr">
                                <td colspan="5" class="font-weight-bold text-right">Jumlah Kembalian</td> 
                                <td>
                                    <input type="text" name='total_kembalian' id="total_kembalian" placeholder='0' class="form-control font-w700 tanpa-rupiah text-right" readonly/>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6">
                                    <div class="form-group row">
                                        <label class="col-12" for="example-textarea-input">Catatan</label>
                                        <div class="col-12">
                                            <textarea class="form-control" id="notes" name="notes" rows="6" placeholder="Catatan.."></textarea>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-success"><span class="fa fa-check"></span> Buat Invoice & Print</button>
                </div>
            </div>
        </form>
        <!-- END Table -->
    </div>
</div>

<script type="text/javascript">
jQuery(function () {
    Codebase.blocks('#my-block2', 'fullscreen_toggle');

    $('.product').select2({
        placeholder: "Pilih"
    });

    $('#patient_id').select2({
        placeholder: "Pilih",
        minimumInputLength: 3,
        allowClear: true
    });

    $('.datepicker').datepicker({
        todayHighlight: true,
        format: "{{ setting()->get('date_format_js') }}",
        weekStart: 1,
        language: "{!! str_replace('_', '-', app()->getLocale()) !!}",
        daysOfWeekHighlighted: "0,6",
        autoclose: true
    });

    function Popup(data)
    {
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);


        return true;
    }

    printInvoice = function(id){
        var url = '{{ route("admin.billing.print.html", ":id") }}';
        url = url.replace(':id', id);

        $.ajax({
            url: url,
            type: 'GET',
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
            success: function(response) {
                Popup(response);
                Codebase.blocks('#my-block2', 'state_normal');
            },
        });
    }
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
            url: "{{ route('admin.billing.store') }}",
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

                    printInvoice(result.data.id);

                    setTimeout(function() { 
                        window.location = '{{ route('admin.billing.index') }}';
                    }, 1000);
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

$(document).ready(function() {
    $('#tab_logic tbody').on('keyup change mouseup', function() {
        calc();
    });
    $('#tax').on('keyup change mouseup', function() {
        calc_total();
    });
    $('#discount').on('keyup change mouseup', function() {
        calc_total();
    });
    $('#total_receive').on('keyup change mouseup', function() {
        calc_kembalian();
    });
});

function calc() {
    $('#tab_logic tbody tr').each(function(i, element) {
        var html = $(this).html();
        if (html != '') {
            var qty = $(this).find('.qty').val();
            var price = $(this).find('.price').val();

            var jumlah = qty * toNumber(price);
            $(this).find('.total').val(formatRupiah(jumlah, '{{ setting()->get('currency_symbol') }}'));

            calc_total();
        }
    });
}

function calc_total() {
    total = 0;
    $('.total').each(function() {
        total += parseInt(toNumber($(this).val()));
    });
    $('#sub_total').val(formatRupiah(total, '{{ setting()->get('currency_symbol') }}'));
    tax = $('#tax').val() / 100;
    tax_sum = parseInt((total * tax));
    $('#tax_amount').val(formatRupiah(tax_sum, '{{ setting()->get('currency_symbol') }}'));

    var discount = $('#discount').val();
    var diskon = discount / 100;
    var totalDiscount = ((total + tax_sum) * diskon)
    var totalValue = (total + tax_sum) - ((total + tax_sum) * diskon)

    $('#discount_amount').val(formatRupiah(totalDiscount, '{{ setting()->get('currency_symbol') }}'));
    $('#total_amount').val(formatRupiah(totalValue, '{{ setting()->get('currency_symbol') }}'));
}

function calc_kembalian() {
    total_amount = $('#total_amount').val();
    total_receive = $('#total_receive').val();
    totalKembalian = toNumber(total_receive) - toNumber(total_amount);

    if(totalKembalian.toString().indexOf("-") > -1){
        $('#kembalianTr').addClass('table-danger').removeClass('table-success');
        $('#invoice_form').find('button').attr("disabled", true);
    }else{
        $('#kembalianTr').addClass('table-success').removeClass('table-danger');
        $('#invoice_form').find('button').attr("disabled", false);
    }

    $('#total_kembalian').val(formatRupiah(totalKembalian, '{{ setting()->get('currency_symbol') }}'));
}

function updateIds() {
    $('#tab_logic tbody tr').each(function(i, element) {
        var html = $(this).html();
        if (html != '') {
            $(this).find('.nomor').text((i+1));
            $(this).find('.product').attr('name', 'product['+(i+1)+']');
            $(this).find('.qty').attr('name', 'qty['+(i+1)+']');
            $(this).find('.price').attr('name', 'price['+(i+1)+']');
            $(this).find('.total').attr('name', 'total['+(i+1)+']');
        }
    });

    validator.resetForm();
}
updateIds();

$(document).ready(function() {

    $(document).on('click', '.add', function() {
        var html = '';
        html += '<tr>';
        html += '<td><button type="button" name="remove" class="btn btn-sm btn-circle btn-outline-danger remove" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
        html += '<td class="nomor">'+(i+1)+'</td>';
        html += '<td><select id="'+(i+1)+'productselect2" name="product['+(i+1)+']" class="form-control product required" data-placeholder="Pilih" style="width: 100%"><option></option>{!! $dataproducts !!}</select></td>';
        html += '<td><input type="number" name="qty['+(i+1)+']" class="form-control qty required" min="1" placeholder="0" /></td>';
        html += '<td><input type="number" name="price['+(i+1)+']" class="form-control text-right price required" placeholder="0" min="1"/></td>';
        html += '<td><input type="text" name="total['+(i+1)+']" placeholder="0" class="form-control text-right total " readonly/></td>';

        html += '</tr>';
        
        $('#tab_logic > tbody').append(html);

        $('#'+(i+1)+'productselect2').select2({
            placeholder: "Pilih"
        });

        i++;
        updateIds();
    });

    $(document).on('click', '.remove', function() {
        $(this).closest('tr').remove();
        updateIds();
        calc();
    });

    $("#tab_logic").on('change', '.product',function () {
        var element = $(this).closest('tr');
        var price = $(this).find(':selected').attr('data-price');
        
        element.find(".qty").val(1);
        // element.find(".price").val(formatRupiah(price, '{{ setting()->get('currency_symbol') }}')).change();
        element.find(".price").val(price).change();

        calc();
    });
});
var lastDate = new Date();
lastDate.setDate(lastDate.getDate());//any date you want
$(".datepicker").datepicker('setDate', lastDate);
</script>
@endsection