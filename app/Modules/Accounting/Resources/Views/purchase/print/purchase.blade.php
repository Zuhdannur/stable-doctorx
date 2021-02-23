<!doctype html>
<html lang="en">
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css'>
<head>
    <meta charset="UTF-8">
    <title>Print - #{{ $purchase->financeTrx->transaction_code }} - @yield('title', app_name())</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }
        
        body {
            margin: 15px;
        }
        
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        
        a {
            color: #fff;
            text-decoration: none;
        }
        
        table {
            font-size: x-small;
        }
        
        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }
        
        .invoice table {
            margin: auto;
        }
        
        .invoice h3 {
            margin-left: 15px;
        }
        
        .information {
            background-color: #FFF;
            color: #000;
        }
        
        .information .logo {
            margin: 5px;
        }
        
        .information table {
            padding: 10px;
        }
    </style>

</head>

<body>

    <div class="information">
        <table width="100%">
            <tr>
                <td align="left" style="width: 40%;">
                    @switch($purchase->status)
                    @case(1)
                        <h2 class='text-success'>Lunas</h2>
                        @break
                    @default
                        @php
                            $due_date = date_create(date('Y-m-d' ,strtotime($purchase->due_date)));
                            $now = date_create(date('Y-m-d'));
                            $diff = date_diff($now, $due_date);
                            $diff = $diff->format('%R%a');
                        @endphp
                        @if ($diff > 0)
                            <h2 class='text-warning'>Belum Lunas</h2>
                        @else
                            <h2 class='text-danger'>Jatuh Tempo</h2>
                        @endif
                @endswitch
                    <h5>Penerima: {{ $purchase->financeTrx->person }}</h5>
                    <h6>Dibuat Pada: {{ $purchase->created_at }}</h6>
                    <h6>Jatuh Tempo: {{ strtotime($purchase->due_date) > 0 ? date('d/m/y', strtotime($purchase->due_date)) : ' - ' }}</h6>
                </td>
                <td align="center">
                    <img src="{{ public_path().'/'.setting()->get('logo_square') }}" alt="Logo" width="128" class="logo" />
                </td>
                <td align="right" style="width: 40%;">
                    <h3>{{ setting()->get('app_name') }}</h3>
                    <pre>
                        Telepon: {{ setting()->get('phone') }}
                        Email: {{ setting()->get('email') }}
                        Website: {{ url('/') }}
                        <br /><br /><br /><br />
                    </pre>
                </td>
            </tr>

        </table>
    </div>

    <br/>

    <div class="invoice">
        <h3>Pembelian No.{{ $purchase->financeTrx->transaction_code  }}</h3>
        <table class="table table-hover" width="100%">
            <thead class="thead splitForPrint">
                <tr>
                    <th scope="col gray-ish" width="40px">#</th>
                    <th scope="col gray-ish" width="200px">Items</th>
                    <th scope="col gray-ish" width="200px">Deskripsi</th>
                    <th class="text-right" scope="col gray-ish" width="60px">Qty</th>
                    <th class="text-right" scope="col gray-ish" width="120px">Harga Satuan</th>
                    <th class="text-right" scope="col gray-ish" width="100px">Pajak</th>
                    <th class="text-right" scope="col gray-ish" width="120px">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $sub_total = 0;
                    $totalTax = 0;
                    $potonganValue = '0';
                    $potongan_account = '';
                    $no = 1;
                @endphp

                @foreach($purchase->detail as $key => $item)
                        <tr>                                    
                            <th scope="row">{{ ($no++) }}</th>
                            @php
                                $product = explode('#',$item->items, 2);
                                $product_detail = explode('-', $product[1]);
                            @endphp
                            <td class="item">{{ (isset($product_detail[0]) ? $product_detail[0] : '' ).( isset($product_detail[1]) ? '-'.$product_detail[1] : '')}}</td>
                            <td class="text-center">{{ $item->desc }}</td>
                            <td class="text-right">{{ $item->qty }}</td>
                            <td class="text-right">{{ currency()->rupiah($item->price, setting()->get('currency_symbol')) }}</td>
                            <td class="text-right">{{ $item->tax_label }}</td>
                            <td class="text-right">{{ currency()->rupiah($item->price_total, setting()->get('currency_symbol')) }}</td>
                            @php
                                $sub_total = $sub_total + $item->price_total;
                                $sum_tax = $item->price_total * $item->tax_value / 100;
                                $totalTax = $totalTax + $sum_tax;
                            @endphp
                        </tr>
                @endforeach

                <tr>
                    <td colspan="6" class="font-w600 text-right">Subtotal</td>
                    <td class="text-right">{{ currency()->rupiah($sub_total, setting()->get('currency_symbol')) }}</td>
                </tr>
                <tr>
                    <td colspan="6" class="font-w600 text-right">Total Tax</td>
                    <td class="text-right">{{ currency()->rupiah($totalTax, setting()->get('currency_symbol')) }}</td>
                </tr>
                <tr>
                    @if ($purchase->financeTrx->potongan)
                        {{-- get potongan --}}
                         @php
                            $potongan = $purchase->getPotongan();
                            if($potongan){
                                $potonganValue = $potongan->value;
                                $potongan_account = $potongan->account->account_name ? $potongan->account->account_name : '';
                            }else{
                                $potonganValue = 0;
                            }
                        @endphp 

                        <td colspan="6" class="font-w600 text-right">Potongan ( {{ $potongan_account}}) : {{ $purchase->financeTrx->potongan." %" }}</td>
                        <td class="text-right">{{ currency()->rupiah($potonganValue, setting()->get('currency_symbol')) }}</td>
                    @else
                        <td colspan="6" class="font-w600 text-right">Potongan</td>
                        <td class="text-right">{{ currency()->rupiah(0, setting()->get('currency_symbol')) }}</td>
                    @endif
                </tr>
                <tr>
                    @if ($purchase->getBiaya())
                        {{-- get potongan --}}
                        @php
                            $biaya = $purchase->getBiaya();
                            if($biaya){
                                $biayaValue = $biaya->value;
                                $biaya_account = $biaya->account->account_name ? $biaya->account->account_name : '';
                            }else{
                                $biayaValue = 0;
                            }
                        @endphp 
                        <td colspan="6" class="font-w600 text-right">Biaya Tambahan ( {{ $biaya_account}}) : {{ $purchase->financeTrx->biaya }}</td>
                        <td class="text-right">{{ currency()->rupiah($biayaValue, setting()->get('currency_symbol')) }}</td>
                    @else
                        <td colspan="6" class="font-w600 text-right">Biaya Tambahan</td>
                        <td class="text-right">{{ currency()->rupiah(0, setting()->get('currency_symbol')) }}</td>
                    @endif
                </tr>
                <tr class="table-warning">
                    <td colspan="6" class="font-w700 text-uppercase text-right">Total</td>
                    <td class="font-w700 text-right">{{ currency()->rupiah($purchase->total, setting()->get('currency_symbol')) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <p class="text-center text-muted pb-3"><em> Faktur dibuat dan valid tanpa tanda tangan dan meterai.</em></p>
    <div class="information" style="position: absolute; bottom: 0;">
        <table width="100%">
            <tr>
                <td align="left" style="width: 50%;">
                    &copy; {{ date('Y') }} {{ config('app.url') }} - All rights reserved.
                </td>
                <td align="right" style="width: 50%;">
                    {{ setting()->get('address') }}
                </td>
            </tr>

        </table>
    </div>
</body>

</html>