@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('billing::labels.billing.management'))

@section('content')
<div class="block" id="my-block2">
    <div class="block-header block-header-default">
        <h3 class="block-title">#{{$billing->invoice_no}}</h3>
        <div class="block-options">
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
        </div>
    </div>
    <div class="block-content">

        {{-- Invoice Info --}}
        @include('billing::billing.components.invoice-info')
        {{-- END Invoice Info  --}}

        <form method="post" class="js-validation-bootstrap" autocomplete="off">
            {{-- additional form --}}
            @include('billing::billing.components.additional-form')
            {{-- end of additional lform --}}

            <!-- Table -->
            <table class="table table-bordered table-hover table-striped table-vcenter" id="tab_logic">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">
                            <button type="button" class="btn btn-sm btn-circle btn-outline-success add" title="Tambah Item">
                                <i class="fa fa-plus"></i>
                            </button>
                        </th>
                        <th style="width: 50px;"> # </th>
                        <th> Item </th>
                        <th class="text-center" style="width: 100px;"> Qty </th>
                        <th class="text-center" style="width: 100px;"> Harga Sebelum Discount </th>
                        <th class="text-center" style="width: 150px;"> Discount </th>
                        <th class="text-right" style="width: 200px;"> Harga Setelah Discount </th>
                        <th class="text-center" style="width: 150px;"> Pajak </th>
                        <th class="text-right" style="width: 200px;"> Total </th>
                    </tr>
                </thead>
                <tbody>
                    {!! $htmlProductDetail !!}
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8" class="font-weight-bold text-right">Sub Total</td>
                        <td>
                            <input type="text" name='sub_total' placeholder='0' class="form-control tanpa-rupiah text-right" id="sub_total" readonly >
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8" class="font-weight-bold text-right">Jumlah Tax</td>
                        <td>
                            <input type="text" name='tax_amount' id="tax_amount" placeholder='0' class="form-control tanpa-rupiah text-right" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8" class="font-weight-bold text-right">Diskon Marketing Activity (%)</td>
                        <td>
                            <input type="number" class="form-control text-right" name="discountMarketing" id="discountMarketing" placeholder="0" value="0" readonly >
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8" class="font-weight-bold text-right">Diskon Tambahan Dengan Nominal (Rp)</td>
                        <td>
                            <input type="number" class="form-control text-right" name="discount_nominal" id="discount_nominal" placeholder="0" value="0">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8" class="font-weight-bold text-right">Diskon Tambahan (%)</td>
                        <td>
                            <input type="number" class="form-control text-right" name="discount" id="discount" placeholder="0" value="0">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8" class="font-weight-bold text-right">Jumlah Diskon</td>
                        <td>
                            <input type="text" name='discount_amount' id="discount_amount" placeholder='0' class="form-control tanpa-rupiah text-right" readonly >
                        </td>
                    </tr>
                    {{-- if membership --}}
                    @if ($billing->patient->membership)
                        <tr>
                            <td colspan="8" class="font-weight-bold text-right">Total Point</td>
                            <td>
                                <input type="text" name='point_ammount' placeholder='0' class="form-control tanpa-rupiah text-right" id="point_ammount" readonly>
                            </td>
                        </tr>
                        <tr class="table-secondary">
                            <td class="font-w600 text-center" colspan="9">Radeem Point (Jumlah Point :&nbsp;
                                {{ $billing->patient->membership->total_point}}&nbsp;+&nbsp;
                                <span id="totalPoint" data-point="{{ $billing->patient->membership->total_point}}">0</span>)
                            </td>
                        </tr>
                        <tr>
                            <td>#</td>
                            <td colspan="4" class="text-center">Item Radeems</td>
                            <td>Qty</td>
                            <td class="text-center">Total Point</td>
                            <td colspan="2" class="text-center">Total Nominal</td>
                        </tr>
                        <tr class="radeem">
                            <td>
                                <button type="button" class="btn btn-sm btn-circle btn-outline-success addRadeem" title="Tambah Item"><i class="fa fa-plus"></i></button>
                            </td>
                            <td colspan="4">
                                <select name="item_radeem[1]" id="1item_radeem" class="form-control item_radeem select2" width="70%">{!! $radeem !!}</select>
                            </td>
                            <td>
                                <input type="number" name="qty_point[1]" id="1qty_point" class="form-control qty_point" value="0" min="0">
                            </td>
                            <td>
                                <input type="text" name="ammount_point[1]" id="1ammount_point" class="form-control ammount_point" value="0" readonly>
                            </td>
                            <td colspan="2">
                                <input type="text" name="nominal_point[1]" id="1nominal_point" class="form-control nominal_point" value="0" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="8" class="font-w600 text-right">Potongan Point</td>
                            <td class="font-w600 text-right" id='total_potongan_point'></td>
                        </tr>
                    @endif
                    <tr class="table-warning">
                        <td colspan="8" class="font-weight-bold text-right">Total</td>
                        <td>
                            <input type="text" name='total_amount' id="total_amount" placeholder='0' class="form-control font-w700 tanpa-rupiah text-right" readonly/>
                        </td>
                    </tr>
                    {{-- Total Pay --}}
                    <tr class="table-info d-none" id="totalPayGroup">
                        <td colspan="8" class="font-weight-bold text-right">Jumlah Yang Akan Dibayarkan</td>
                        <td>
                            <input type="number" name='totalPay' id="totalPay" placeholder='0' class="form-control font-w700 text-right"/>
                        </td>
                    </tr>
                    <tr class="table-info">
                        <td colspan="8" class="font-weight-bold text-right">Jumlah Diterima</td>
                        <td>
                            <input type="number" name='total_receive' id="total_receive" class="form-control font-w700 text-right required" value="0"/>
                        </td>
                    </tr>
                    <tr id="kembalianTr">
                        <td colspan="8" class="font-weight-bold text-right">Jumlah Kembalian</td>
                        <td>
                            <input type="text" name='total_kembalian' id="total_kembalian" placeholder='0' class="form-control font-w700 tanpa-rupiah text-right" readonly/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="9">
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
            <p class="text-muted text-center">
                <button type="submit" class="btn btn-success" id="buttonSubmit"><span class="fa fa-check"></span> Submit Transaksi & Cetak</button>
            </p>
            <p class="text-muted text-center">
                <button type="button" class="btn btn-success d-none" data-id="{{$billing->id}}" id="cetakUlang">
                    <span class="fa fa-print mr-1"></span>Cetak Ulang
                </button>
            </p>
            <p class="text-muted text-center">
                <a href="{{ route('admin.billing.index') }}" class="btn-block-option d-none" id="backButton">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </p>
        </form>
        <!-- END Table -->
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    jQuery(function () {
        Codebase.blocks('#my-block2', 'fullscreen_toggle');
        var numProduct = {!! $numproduct !!};
        var $point_total = 0
        $(document).ready(function(){
            $('.product, #acc_cash, #isPayAll').select2({
                placeholder: "Pilih"
            });

            $('.tax, #marketing, .item_radeem').select2({
                placeholder: "Pilih",
                allowClear : true
            });

            $(".product").each(function () {
                var element = $(this).closest('tr');
                var price = $(this).find(':selected').attr('data-price');
                var tax_product = $(this).find(':selected').attr('data-tax');

                element.find(".price").val(price).change();
                element.find('.tax').val(tax_product).trigger('change');

                calc();
            });

            updateIds();

            $('body').on('input','.discount-item', function () {

                var price = $(this).parent().parent().find(':selected').attr('data-price');
                var value = $(this).val()
                var parent = $(this).parent().parent().find('.price');

                var sum = toNumber(price) - toNumber(value);
                parent.val(sum)
                calc();
            })

            /** event registered */
            $(document).on('click', '.add', function() {
                var html = '';
                html += '<tr>';
                html += '<td><button type="button" name="remove" class="btn btn-sm btn-circle btn-outline-danger remove" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
                html += '<td class="nomor">'+( numProduct + 1 )+'</td>';
                html += '<td><select id="'+( numProduct + 1 )+'productselect2" name="product['+( numProduct + 1 )+']" class="form-control product required" data-placeholder="Pilih" style="width: 100%"><option></option>{!! $dataproducts !!}</select></td>';
                html += '<td><input type="number" name="qty['+( numProduct + 1 )+']" class="form-control qty required" min="1" placeholder="0" /></td>';
                html += '<td><input type="number" name="before_discount['+(i+1)+']" placeholder="0" class="form-control before-discount" min="0" /></td>';
                html += '<td><input type="number" name="discount_item['+(i+1)+']" class="form-control discount-item" min="0" placeholder="0" /></td>';
                html += '<td><input type="number" name="price['+( numProduct + 1 )+']" class="form-control text-right price required" placeholder="0" min="1" readonly/></td>';
                html += '<td><select id="'+( numProduct + 1 )+'taxselect2" name="tax['+( numProduct + 1 )+']" class="form-control tax" data-placeholder="Pilih" style="width: 100%">{!! $taxList !!}</select></td>';
                html += '<td><input type="text" name="total['+( numProduct + 1 )+']" placeholder="0" class="form-control text-right total " readonly/></td>';

                html += '</tr>';
                $('#tab_logic > tbody').append(html);

                $('#'+( numProduct + 1 )+'productselect2').select2({
                    placeholder: "Pilih"
                });

                $('.tax').select2({
                    placeholder: "Pilih"
                })

                numProduct++;
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
                var tax_product = $(this).find(':selected').attr('data-tax');

                element.find(".qty").val(1);
                element.find(".price").val(price).change();
                element.find('.tax').val(tax_product).trigger('change');
                element.find(".before-discount").val(price).change();

                calc();
            });

            $('#tab_logic tbody, #tab_logic tfoot').on('keydown change mouseup', function() {
                calc();
            });

            $('#total_receive').on('keyup keydown', function() {
                calc();
            })

            $('#discount').on('keyup keydown', function() {
                calc();
            })

            $('.qty_point').on('keydown change mouseup', function() {
                calc_nominal_point();
                calc()
            })

            $('#marketing').on('change', function(){
                _discount = $(this).find(':selected').attr('data-discount');

                if(_discount === undefined) _discount = 0

                $('#discountMarketing').val(_discount);
                calc();
            })

            $('#isPayAll').on('change', function (e) {
                if($(this).val() == '2') {
                    $('#totalPayGroup').removeClass('d-none')
                    $('#totalPay').on('keyup keydown', function() {
                        calc();
                    })
                }else {
                    $('#totalPayGroup').addClass('d-none')
                }
            })

            /* Table Radeem Logic */
            var i = 1;
            $(document).on('click', '.addRadeem', function() {
                var html = '';
                html += '<tr class="radeem">';
                html += '<td><button type="button" name="remove" class="btn btn-sm btn-circle btn-outline-danger removeRadeem" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
                html += '<td colspan="4">';
                html += '<select name="item_radeem['+(i+1)+']" id="'+(i+1)+'item_radeem" class="form-control item_radeem select2" width="70%">';
                html += '{!! $radeem !!}';
                html += '</select>';
                html += '</td>';
                html += '<td>';
                html += '<input type="number" name="qty_point['+(i+1)+']" id="'+(i+1)+'qty_point" class="form-control qty_point" value="0">';
                html += '</td>';
                html += '<td>';
                html += '<input type="text" name="ammount_point['+(i+1)+']" id="'+(i+1)+'ammount_point" class="form-control ammount_point" value="0" readonly>';
                html += '</td>';
                html += '<td colspan="2">';
                html += '<input type="text" name="nominal_point['+(i+1)+']" id="'+(i+1)+'nominal_point" class="form-control nominal_point" value="0" readonly>';
                html += '</td>';
                html += '</tr>';

                i++;

                row = $(this).closest("tr").closest('tr');
                $(row).after(html);

                $('.select2').select2({
                    placeholder: 'Silahkan Pilih',
                    allowClear : 'true'
                });
                updateIdsRadeem();
            });

            $(document).on('click', '.removeRadeem', function() {
                $(this).closest('tr').remove();
                updateIdsRadeem();
            });
            /** end of tabel radeem logic*/
        });

        // /** validation */
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
                try {
                    calc();
                    if($('#isPayAll').val() == '2'){
                        total_amount = toNumber($('#total_amount').val());
                        total_pay = parseInt($('#totalPay').val())

                        if(total_pay > total_amount) throw new Error('Nominal jumlah yang dibayarkan tidak boleh lebih besar dari jumlah total.')
                    }

                    let sum_point = parseInt($('#totalPoint').data('point')) + parseInt($('#point_ammount').val());
                    console.log($point_total)
                    if($point_total > sum_point) throw new Error('Point Pasien Tidak Mencukupi !!!')
                } catch (error) {
                    $.alert({
                        title: "Kesalahan",
                        icon: 'fa fa-warning',
                        type: 'red',
                        content: error,
                    });
                    return false;
                }

                $('#buttonSubmit').hide();
                $.ajax({
                    type: "PATCH",
                    url: "{{ route('admin.billing.update', $billing->id) }}",
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
                                content: x.responseJSON.message,
                            });
                        }

                        $('#buttonSubmit').show();
                        Codebase.blocks('#my-block2', 'state_normal');
                    },
                    success: function(result) {
                        if (result.status) {
                            $.notify({
                                message: result.message,
                                type: 'success'
                            });

                            $('#buttonSubmit').remove();
                            $('#cetakUlang').removeClass('d-none');
                            $('#backButton').removeClass('d-none');
                            $('#cetakUlang').on('click', function() {
                                printInvoice(result.data.id)
                            });
                            $('select').prop('disabled', true);
                            $('input').prop('disabled', true)

                            printInvoice(result.data.id);
                        } else {
                            $.alert({
                                title: 'Error',
                                icon: 'fa fa-warning',
                                type: 'orange',
                                content: result.message,
                            });
                            $('#buttonSubmit').show();
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
                    $(this).find('.nomor').text(( i + 1 ));
                    $(this).find('.product').attr('name', 'product['+( i + 1 )+']');
                    $(this).find('.qty').attr('name', 'qty['+( i + 1 )+']');
                    $(this).find('.price').attr('name', 'price['+( i + 1 )+']');
                    $(this).find('.tax').attr('name', 'tax['+( i + 1 )+']');
                    $(this).find('.total').attr('name', 'total['+( i + 1 )+']');
                }
            });
            validator.resetForm();
        }

        function calc() {
            $('#tab_logic tbody tr').each(function(i, element) {
                var html = $(this).html();
                if (html != '') {
                    var qty = $(this).find('.qty').val();
                    var price = $(this).find('.price').val();
                    var jumlah = qty * parseInt(price);
                    var tax = $(this).find('.tax > :selected').attr('data-tax');

                    /** sum tax */
                    if( isNaN(tax)){ tax = 0 }
                    else{ tax = tax / 100 }

                    jumlah = jumlah + (jumlah * tax);
                    $(this).find('.total').val(formatRupiah(jumlah, '{{ setting()->get('currency_symbol') }}'));
                }
            });
            calc_total();
            calc_point();
            calc_nominal_point();
        }

        function calc_total() {
            total = 0;
            $('.total').each(function() {
                total += parseInt(toNumber($(this).val()));
            });
            $('#sub_total').val(formatRupiah(total, '{{ setting()->get('currency_symbol') }}'));

            var discount = parseFloat($('#discount').val());
            var discountMarketing = parseInt($('#discountMarketing').val());
            var diskon = (discount + discountMarketing) / 100;
            var totalDiscount = parseInt(total * diskon);
            var potongan_point = calc_potongan_point();
            var totalValue = total - totalDiscount - potongan_point;
            $('#discount_amount').val(formatRupiah(totalDiscount, '{{ setting()->get('currency_symbol') }}'));
            $('#total_potongan_point').html(formatRupiah(potongan_point, '{{ setting()->get('currency_symbol') }}'));
            $('#total_amount').val(formatRupiah(totalValue, '{{ setting()->get('currency_symbol') }}'));
            calc_kembalian();
        }

        function calc_kembalian() {
            total_amount = toNumber($('#total_amount').val());

            if($('#isPayAll').val() == '2'){
                total_amount = parseInt($('#totalPay').val())
            }
            total_receive = $('#total_receive').val();
            totalKembalian = parseInt(total_receive) - total_amount;

            if(totalKembalian.toString().indexOf("-") > -1){
                $('#kembalianTr').addClass('table-danger').removeClass('table-success');
                $('#buttonSubmit').attr("disabled", true);
            }else{
                $('#kembalianTr').addClass('table-success').removeClass('table-danger');
                $('#buttonSubmit').attr("disabled", false);
            }

            $('#total_kembalian').val(formatRupiah(totalKembalian, '{{ setting()->get('currency_symbol') }}'));
        }

        function calc_point(){
            // reset point
            var total_point = 0;

            // point from membership
            var min_trx = "{{ $billing->patient->membership && $billing->patient->membership->ms_membership->min_trx ? $billing->patient->membership->ms_membership->min_trx : 0 }}";
            var total_transaction = toNumber($('#total_amount').val());
            var point_member = 0;

            if(min_trx < total_transaction){
                var point_member = "{{ $billing->patient->membership && $billing->patient->membership->ms_membership->point ? $billing->patient->membership->ms_membership->point : 0 }}";
                if(total_transaction >= 0) {
                    point_member = parseInt( ( total_transaction / min_trx) * point_member)
                }else{
                    point_member = 0;
                }
                if(isNaN(point_member)){
                    point_member = 0;
                }
            }

            // point from marketing activity
            var point_marketing = parseInt($('#marketing').find(':selected').attr('data-point'));
            if(isNaN(point_marketing)){
                point_marketing = 0;
            }

            // point product
            var point_product = 0;
            $('.product').each(function() {
                var _point_product = 0;
                _element = $(this).closest('tr');
                _min_qty = parseInt($(this).find(':selected').attr('data-min-qty'));

                qty = _element.find(".qty").val();
                if(qty >= _min_qty ){
                    _point_product = parseInt($(this).find(':selected').attr('data-point'));
                    if(isNaN(_point_product)){
                        _point_product = 0;
                    }
                }

                point_product += _point_product;
            });

            total_point = parseInt(total_point) + parseInt(point_product) + parseInt(point_member) + parseInt(point_marketing);

            $('#totalPoint').html(total_point);
            $('#point_ammount').val(total_point);
        }

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

            return window.frames['frame1'];
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
                    Popup(response).addEventListener('afterprint', function(e){
                        setInterval(function() {
                            window.location.href = '{{ route('admin.billing.index') }}';
                        }, 4000);
                    });
                    Codebase.blocks('#my-block2', 'state_normal');
                },
            });
        }

        function updateIdsRadeem() {
            $('#tab_logic tbody .radeem').each(function(i, element) {
                var html = $(this).html();
                if (html != '') {
                    $(this).find('.item_radeem').attr('name', 'item_radeem['+(i+1)+']');
                    $(this).find('.qty_point').attr('name', 'qty_point['+(i+1)+']');
                    $(this).find('.ammount_point').attr('name', 'ammount_point['+(i+1)+']');
                    $(this).find('.nominal_point').attr('name', 'nominal_point['+(i+1)+']');
                }
            });
        }

        function calc_potongan_point(){
            var potongan = 0;
            $('#tab_logic tfoot .radeem').each(function(i, element){
                var html = $(this).html();

                if(html != ''){
                    is_potongan = parseInt(toNumber($(this).find('.nominal_point').val()));

                    if(is_potongan == '' || isNaN(is_potongan) || is_potongan == null){
                        is_potongan = 0;
                    }

                    potongan = potongan + is_potongan;
                }
            });

            return potongan;
        }

        function calc_nominal_point(){
            $point_total = 0;
            $('#tab_logic tfoot .radeem').each(function(i, element){
                var html = $(this).html();
                if (html != '') {
                    let point = $(this).find('.item_radeem :selected').attr('data-point');
                    let gift = $(this).find('.item_radeem :selected').attr('data-gift');

                    if(point == '' || point == 'null'){
                        point = 0;
                    }

                    if(gift == '' || gift == 'null'){
                        gift = 0;
                    }

                    total_point = point * $(this).find('.qty_point').val();
                    total_gift = gift * $(this).find('.qty_point').val();

                    if(total_point == '' || total_point == 'null' || isNaN(total_point)){
                        total_point = 0;
                    }

                    if(total_gift == '' || total_gift == 'null' || isNaN(total_point)){
                        total_gift = 0;
                    }

                    // sum globat point
                    $point_total = parseInt(total_point) + parseInt($point_total);

                    $(this).find('.ammount_point').val(total_point);
                    $(this).find('.nominal_point').val(formatRupiah(total_gift, '{{ setting()->get('currency_symbol') }}'));
                }
            });
        }

        $("#discount_nominal").on('input',function () {
            total = 0;
            $('.total').each(function() {
                total += parseInt(toNumber($(this).val()));
            });

            var values = $(this).val()
            console.log(values)
            var bagi = values / total
            console.log(bagi)

            var discount = bagi * 100;

            $("#discount").val(discount);
            calc()
        })
    });
    </script>
@endpush
