<div class="table-responsive push">
    <table class="table table-bordered table-hover" id="table_logic">
        <thead>
            <tr>
                <th class="text-center" style="width: 60px;"></th>
                <th>Product</th>
                <th class="text-center" style="width: 90px;">Qty</th>
                <th class="text-right" style="width: 120px;">Unit</th>
                <th class="text-right" style="width: 120px;">Pajak</th>
                <th class="text-right" style="width: 120px;">Discount</th>
                <th class="text-right" style="width: 300px;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $point = 0;
            @endphp
            @foreach($billing->invDetail as $key => $item)
                @php
                    $point = $point + $item->point;
                    $tax_ammount = intVal($item->qty * $item->price * $item->tax_value / 100);
                    $amount = $item->qty * $item->price;
                @endphp
                <tr class="billing_detail">
                    <td class="text-center">{{ ($key+1) }}</td>
                    <td>
                        <p class="font-w600 mb-5">{{ $item->productname }}</p>
                        <div class="text-muted">{{ $item->productdesc }}</div>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-pill badge-primary">{{ $item->qty }}</span>
                    </td>
                    <td class="text-right">{{ currency()->rupiah($item->price, setting()->get('currency_symbol')) }}</td>
                    <td>
                        <p class="font-w600 mb-5">{{ $item->tax_label != '' ? $item->tax_label : '-' }}</p>
                        <div class="text-muted tax_ammount">{{ currency()->rupiah($tax_ammount, setting()->get('currency_symbol')) }}</div>
                    </td>
                    <td class="text-right ammount">{{ currency()->rupiah($item->discount_item, setting()->get('currency_symbol')) }}</td>
                    <td class="text-right">{{ currency()->rupiah($item->price, setting()->get('currency_symbol')) }}</td>
                </tr>
            @endforeach
            {{-- Subtotal --}}
            <tr>
                <td colspan="5" class="font-w600 text-right">Subtotal</td>
                <td colspan="2" class="text-right" id="sub_total">{{ currency()->rupiah($billing->sub_total, setting()->get('currency_symbol')) }}</td>
            </tr>
            {{-- Total tax --}}
            <tr>
                <td colspan="5" class="font-w600 text-right">Total Tax</td>
                <td colspan="2" class="text-right" id="tax">{{ currency()->rupiah($billing->total_tax, setting()->get('currency_symbol')) }}</td>
            </tr>
            @switch($billing->status)
                @case(config('billing.invoice_unpaid'))
                    {{-- Diskon --}}
                    <tr>
                        <td colspan="5" class="font-w600 text-right">Diskon <span id="discountPercent" data-discount="{{ $billing->discount_percent }}">{{ $billing->discount_percent }}</span></td>
                        <td colspan="2" class="text-right" id="discount">{{ currency()->rupiah($billing->discount_price, setting()->get('currency_symbol')) }}</td>
                    </tr>
                @break
                @case(config('billing.invoice_partial_paid'))
                    <tr>
                        <td colspan="5" class="font-w600 text-right">Marketing Activity</td>
                        <td colspan="2" class="text-right font-weight600">
                            {{ isset($billing->marketing->name) ? $billing->marketing->name : '-'}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="font-w600 text-right">Diskon {{ $billing->discount_percent }}</td>
                        <td colspan="2" class="text-right" id="discount">{{ currency()->rupiah($billing->discount_price, setting()->get('currency_symbol')) }}</td>
                    </tr>
                    @if ($billing->patient->membership)
                        <tr>
                            <td colspan="5" class="font-w600 text-right">Potongan Radeem Sebelumnya</td>
                            <td colspan="2" class="text-right">{{ currency()->rupiah($billing->radeem_point, setting()->get('currency_symbol')) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="5" class="font-w600 text-right">Sudah Bayar</td>
                        <td colspan="2" class="text-right">{{ currency()->rupiah($billing->total_pay, setting()->get('currency_symbol')) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="font-w600 text-right">Sisa Pembayaran</td>
                        <td colspan="2" class="text-right" id="remaining_payment">{{ currency()->rupiah($billing->remaining_payment, setting()->get('currency_symbol')) }}</td>
                    </tr>
                @break
            @endswitch

            {{-- if membership --}}
            @if ($billing->patient->membership)
                @if ($billing->status == config('billing.invoice_unpaid'))
                    @php
                        $pointMembership = ($billing->total_price / $billing->patient->membership->ms_membership->min_trx) * $billing->patient->membership->ms_membership->point;
                        $pointMembership = intVal($pointMembership) + $point;
                    @endphp
                    <tr>
                        <td colspan="5" class="font-w600 text-right">Point Yang di dapatkan</td>
                        <td colspan="2" class="text-right" id="point" data-point="{{$pointMembership}}">{{ $pointMembership }}</td>
                    </tr>
                @endif
                <tr class="table-secondary">
                    <td class="font-w600 text-center" colspan="7">Radeem Point (Jumlah Point :&nbsp;
                        <span id="totalPoint">
                            @if ($billing->status == config('billing.invoice_unpaid'))
                                {{ $billing->patient->membership->total_point ." + ". $pointMembership}}
                            @else
                                {{ $billing->patient->membership->total_point}}
                            @endif
                        </span>)
                    </td>
                </tr>
                <tr>
                    <td>#</td>
                    <td colspan="2" class="text-center">Item Radeems</td>
                    <td>Qty</td>
                    <td class="text-center">Total Point</td>
                    <td colspan="2" class="text-center">Total Nominal</td>
                </tr>
                <tr class="radeem">
                    <td>
                        <button type="button" class="btn btn-sm btn-circle btn-outline-success add" title="Tambah Item"><i class="fa fa-plus"></i></button>
                    </td>
                    <td colspan="2">
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
                    <td colspan="5" class="font-w600 text-right">Potongan Point</td>
                    <td colspan="2" class="font-w600 text-right" id='total_potongan_point'></td>
                </tr>
            @endif

            {{-- Total  --}}
            <tr class="table-warning">
                <td colspan="5" class="font-w700 text-uppercase text-right">Total</td>
                <td colspan="2" class="font-w700 text-right" id='total'>{{ currency()->rupiah($billing->remaining_payment, setting()->get('currency_symbol')) }}</td>
            </tr>
            {{-- Total Pay --}}
            <tr class="table-info d-none" id="totalPayGroup">
                <td colspan="5" class="font-weight-bold text-right">Jumlah Yang Akan Dibayarkan</td>
                <td colspan="2">
                    <input type="number" name='totalPay' id="totalPay" placeholder='0' class="form-control font-w700 text-right required"/>
                </td>
            </tr>
            {{-- Jumlah Diterima --}}
            <tr class="table-info">
                <td colspan="5" class="font-weight-bold text-right">Jumlah Diterima</td>
                <td colspan="2">
                    <input type="number" name='total_receive' id="total_receive" placeholder='0' class="form-control font-w700 text-right required"/>
                </td>
            </tr>
            {{-- Kembalian --}}
            <tr id="kembalianTr">
                <td colspan="5" class="font-weight-bold text-right">Jumlah Kembalian</td>
                <td colspan="2">
                    <input type="text" name='total_kembalian' id="total_kembalian" placeholder='0' class="form-control font-w700 tanpa-rupiah text-right" readonly/>
                </td>
            </tr>
        </tbody>
    </table>
</div>
