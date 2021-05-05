<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        @media print{

            *{
                box-sizing: border-box;
                margin: 1px;
                padding: 0;
                font-family: sans-serif,tahoma;
            }
            body{
                font-size: 10pt;
                letter-spacing: 0px;
                line-height: 15px;
                max-width: 66mm;
            }
            .row{
                font-size: 10pt;
                letter-spacing: 0px;
                line-height: 15px;
                max-width: 66mm;
                display: flex;
                min-height:0.5em;
            }

            .container{
                width: 66mm;
                min-height:8cm;
            }

            .content-center{
                justify-content: center;
            }

            .col{
                flex-wrap:wrap;
            }

            table{
                font-size: 8pt;
            }

            td{
                padding: 0px 1px;
            }

            .bil-detail{
                border-bottom: 1px solid #000000;
                border-top: 1px solid #000000;
            }
            .wrapword {
                white-space: -moz-pre-wrap !important;  /* Mozilla, since 1999 */
                white-space: -webkit-pre-wrap;          /* Chrome & Safari */
                white-space: -pre-wrap;                 /* Opera 4-6 */
                white-space: -o-pre-wrap;               /* Opera 7 */
                white-space: pre-wrap;                  /* CSS3 */
                word-wrap: break-word;                  /* Internet Explorer 5.5+ */
                word-break: break-all;
                white-space: normal;
                /* border-style: ; */
            }

            .border-solid{
                border:1px solid #000000
            }
        }
    </style>
</head>
{{-- <body> --}}
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col" style='margin-bottom:10px;margin-top:8px;text-align: center'>
                <img src="{{ asset(setting()->get('logo_header')) }}" width="40px" class="" alt="">
                <h5 class="wrapword">{{ strtoupper('Klinik '.$billing->klinik->nama_klinik) }}</h5>
                <p>{{ setting()->get('address') }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col" style='margin-bottom:10px;margin-top:8px'>
                <hr style='width:66mm;border-bottom : 1.5px dashed #2a2929;border-top: none;margin-bottom:3px;'>
                <h5 class="wrapword"><span style='width:18mm;display: inline-block;'>Nama Pasien&nbsp;</span>:&nbsp;{{ $billing->patient->patient_name }}</h5>
                <h5><em class="wrapword"><span style='width:18mm;display: inline-block;'>ID&nbsp;</span>:&nbsp;{{ $billing->patient->patient_unique_id }}</em></h5>
                <p><em class="wrapword"><span style='width:18mm;display: inline-block;'>Alamat&nbsp;</span>:&nbsp;{{ isset($billing->patient->address) ? ucwords(strtolower($billing->patient->address)) : '-' }}, {{ isset($billing->patient->city->name) ? ucwords(strtolower($billing->patient->city->name)) : '-' }} {{ $billing->patient->zip_code }}</em></p>
            </div>
        </div>
        <div class="row" style='margin-bottom:10px;'>
            <div class="col">
                <hr style='width:66mm;border-bottom : 1.5px dashed #2a2929;border-top: none;margin-bottom:3px;'>
                <h4>Invoice No. #{{ $billing->invoice_no }}</h4>
                <h5><span style='width:18mm;display: inline-block;'>Dibuat Pada&nbsp;</span>: {{ $billing->created_at }}</h5>
                <h5><span style='width:18mm;display: inline-block;'>Jatuh Tempo&nbsp;</span>: {{ $billing->date }}</h5>
{{--                <h5><span style='width:18mm;display: inline-block;'>Metode Pembayaran&nbsp;</span>: {{ @$billing->finance_transaction->journal }}</h5>--}}
                <h5><span style='width:18mm;display: inline-block;'>Metode Pembayaran&nbsp;</span>: {{ @\App\Modules\Billing\Models\PaymentHistory::where('invoice_id',$billing->id)->first()->account->account_name }} </h5>
                <h5><span style='width:18mm;display: inline-block;'>Catatan&nbsp;</span>: {{ $billing->note }}</h5>
                <hr style='width:66mm;border-bottom : 1.5px dashed #2a2929;border-top: none;margin-top:3px;'>
            </div>
        </div>

        <!-- table billing -->
        <div class="row">
            <div class="col">
                <table>
                    <thead style='text-align: center;'>
                        <th clss='bil-detail' style='width:5mm;'>No.</th>
                        <th clss='bil-detail' style='width:20mm'>Produk/Servis</th>
                        <th clss='bil-detail' style='width:8mm'>Qty</th>
                        <th clss='bil-detail' style='width:20mm'>Harga</th>
                        <th clss='bil-detail' style='width:15mm'>Discount</th>
                        <th clss='bil-detail' style='width:18mm'>Amount</th>
                    </thead>
                    <tbody>
                        @foreach($billing->invDetail as $key => $item)
                        @php
                            $amount = $item->qty * $item->price + ($item->qty * $item->price * $item->tax_value / 100);
                            $no = 1;
                        @endphp
                        @if ($item->payment_step == $billing->is_payment)
                            <tr>
                                <td class="wrapword">{{ ($no++) }}</td>
                                <td class="wrapword">
                                    <p>{{ $item->product }} </p>
                                    <em> {{  $item->tax_label }}</em>
                                </td>
                                <td class="wrapword">{{ $item->qty }}</td>
                                <td >{{ currency()->rupiah($item->product_price, setting()->get('currency_symbol')) }}</td>
                                <td>{{ currency()->rupiah($item->discount, setting()->get('currency_symbol')) }}</td>
                                <td ><strong>{{ currency()->rupiah($amount , setting()->get('currency_symbol')) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>Catatan :</strong></td>
                                <td colspan="4"><strong>{{ $item->notes }}</strong></td>
                            </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- end table billing -->

        <div class="row">
            <div class="col">
                <hr style='width:66mm;border-bottom : 1.5px dashed #2a2929;border-top: none;margin-bottom:3px;'>
                <h4 class="wrapword-ish">Ringkasan dan Catatan Faktur</h4>
               <p class="wrapword">{{ $billing->notes }}</p>
                <hr style='width:66mm;border-bottom : 1.5px dashed #2a2929;border-top: none;margin-top:3px;'>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <table style='border-collapse: collapse;'>
                    <tbody>
                        <tr>
                            <th style='width:35mm;text-align: right;padding-right: 10px;'>Subtotal</th>
                            <td class='wrapword' style="font-size:9pt"><strong>{{ currency()->rupiah($billing->subtotal, setting()->get('currency_symbol')) }}</strong></td>
                        </tr>
{{--                        <tr>--}}
{{--                            <th style='width:35mm;text-align: right;padding-right: 10px;'>Total Tax</th>--}}
{{--                            <td class='wrapword' style="font-size:9pt"><strong>{{ currency()->rupiah($billing->tax_total, setting()->get('currency_symbol')) }}</strong></td>--}}
{{--                        </tr>--}}
                        @php
                            $potongan_radeem = 0;
                        @endphp
                        @if ($billing->patient->membership)
                            <tr>
                                <th style='width:35mm;text-align: right;padding-right: 10px;'>Total Potongan Radeem</th>
                                <td class='wrapword' style="font-size:9pt"><strong>{{ currency()->rupiah($billing->radeem_point, setting()->get('currency_symbol')) }}</strong></td>
                            </tr>
                        @endif
                        @if(!empty($billing->tax_total))
                        <tr>
                            <th style='width:35mm;text-align: right;padding-right: 10px;'>Total Tax</th>
                            <td class='wrapword' style="font-size:9pt"><strong>{{ currency()->rupiah($billing->tax_total, setting()->get('currency_symbol')) }}</strong></td>
                        </tr>
                        @endif
                        @if(!empty($billing->discountprice))
                        <tr>
                            <th style='width:35mm;text-align: right;padding-right: 10px;'>Diskon {{ intval($billing->discount_percent).'%' }}</th>
                            <td class='wrapword' style="font-size:9pt"><strong>{{ currency()->rupiah($billing->discountprice, setting()->get('currency_symbol')) }}</strong></td>
                        </tr>
                        @endif
                        <tr>
                            <th style='width:35mm;text-align: right;padding-right: 10px;'>Total</th>
                            <td class='wrapword' style="font-size:9pt"><strong>{{ currency()->rupiah($billing->total_ammount -  $billing->discountprice, setting()->get('currency_symbol')) }}</strong></td>
                        </tr>
                        <tr>
                            <th style='width:35mm;text-align: right;padding-right: 10px;'>Jumlah yang telah dibayarkan</th>
                            <td class='wrapword' style="font-size:9pt"><strong>{{ currency()->rupiah($billing->total_pay -  $billing->discountprice, setting()->get('currency_symbol')) }}</strong></td>
                        </tr>
                        @if(!empty($billing->remaining_payment))
                        <tr>
                            <th style='width:35mm;text-align: right;padding-right: 10px;'>Sisa Pembayaran</th>
                            <td class='wrapword' style="font-size:9pt"><strong>{{ currency()->rupiah($billing->remaining_payment, setting()->get('currency_symbol')) }}</strong></td>
                        </tr>
                        @endif
                        <tr>
                            <th style='width:35mm;text-align: right;padding-right: 10px;'>Jumlah dibayarkan</th>
                            <td class='wrapword' style="font-size:9pt"><strong>{{ currency()->rupiah($billing->last_payment->total_pay - $billing->discountprice, setting()->get('currency_symbol')) }}</strong></td>
                        </tr>
                        <tr>
                            <th style='width:35mm;text-align: right;padding-right: 10px;'>Diterima</th>
                            <td class='wrapword' style="font-size:9pt"><strong>{{ currency()->rupiah($billing->last_payment->in_paid, setting()->get('currency_symbol')) }}</strong></td>
                        </tr>
                        <tr>
                            <th style='width:35mm;text-align: right;padding-right: 10px;'>Kembalian</th>
                            <td class='wrapword' style="font-size:9pt"><strong>{{ currency()->rupiah(($billing->last_payment->in_paid - $billing->last_payment->total_pay) + $billing->discountprice, setting()->get('currency_symbol')) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @if($billing->patient->membership)
            <div class="row">
                <div class="col">
                    <hr style='width:66mm;border-bottom : 1.5px dashed #2a2929;border-top: none;margin-bottom:3px;'>
                    <p class="wrapword">Point Membership Anda Saat ini adalah : {{ $billing->patient->membership->total_point }}</p>
                    <hr style='width:66mm;border-bottom : 1.5px dashed #2a2929;border-top: none;margin-top:3px;'>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col">
                <hr style='width:66mm;border-bottom : 1.5px dashed #2a2929;border-top: none;margin-bottom:3px;'>
                <p class="wrapword"><em>Faktur dibuat dan valid tanpa tanda tangan dan materai.</em></p>
                <hr style='width:66mm;border-bottom : 1.5px dashed #2a2929;border-top: none;margin-top:3px;'>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <hr style='width:66mm;border-bottom : 1.5px dashed #2a2929;border-top: none;margin-bottom:3px;'>
                <p class="wrapword">Facebook : {{ setting()->get('copyright') }} , Instagram : {{ setting()->get('copyright_link') }}</p>
                <hr style='width:66mm;border-bottom : 1.5px dashed #2a2929;border-top: none;margin-top:3px;'>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <hr style='width:66mm;border-bottom : 1.5px dashed #2a2929;border-top: none;margin-bottom:3px;'>
                <p class="wrapword">Powered By {{ app_name() }}</p>
                <p class="wrapword">BARANG YANG SUDAH DI BELI TIDAK BISA DI TUKAR/ DI KEMBALIKAN</p>
                <hr style='width:66mm;border-bottom : 1.5px dashed #2a2929;border-top: none;margin-top:3px;'>
            </div>
        </div>
    </div>
{{-- </body> --}}
</html>
