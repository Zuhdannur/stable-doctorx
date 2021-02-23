@php
    // variabel default
    $no = 1;
    $sub_total = 0;
    $totalTax = 0;
    $total_amount = 0;

    // variabel for detailing
    $cash = $trx->journal->load('account')->where('tags', 'is_cash')->first();
    $acc_receipt = $trx->journal->load('account')->where('tags', 'is_receipt');
    $tax = $trx->journal->load('account')->where('tags', 'is_tax');

    // sum total tax
    if(isset($tax)){
        foreach ($tax as $item ) {
            $totalTax = intVal($totalTax + $item->value);
        }
    }

@endphp

<!doctype html>
<html lang="en">
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css'>
<head>
    <meta charset="UTF-8">
    <title>Print - #{{ $trx->transaction_code }} - @yield('title', app_name())</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }
        
        body {
            margin: 0px;
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
            margin: 15px;
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
                    <h3>Pembayar : {{ $trx->person }}</h3>
                    <p>Setor Ke : {{ isset($cash->account) ? $cash->account->account_code .' - '. $cash->account->account_name : ''}}</p>
                    <span><em>dibuat oleh : {{ isset($trx->user->first_name) ? $trx->user->first_name." ".$trx->user->last_name : '' }}</em></span><br>
                    <span><em>pada : {{ $trx->updated_at }}</em></span>
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

    <br>

    <div class="invoice">
        <h3>{{ $trx->transaction_code  }}</h3>
        <table class="table table-hover" width="100%">
            <thead class="thead splitForPrint">
                <tr>
                    <th scope="col gray-ish">#</th>
                    <th scope="col gray-ish">Terima Dari</th>
                    <th scope="col gray-ish">Deskripsi</th>
                    <th class="text-right" scope="col gray-ish">Deskripsi</th>
                    <th class="text-right" scope="col gray-ish">Pajak</th>
                    <th class="text-right" scope="col gray-ish">Total</th>
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

                @foreach($acc_receipt as $key => $item)
                    <tr>                                    
                        <th scope="row">{{ ($no++) }}</th>
                        <td class="item">{{ $item->account['account_code'] ." ". $item->account['account_name'] }}</td>
                        <td class="text-center">{{ $item->decription }}</td>
                        <td class="text-right">{{ $item->qty }}</td>
                        <td class="text-right">{{ $item->tax }}</td>
                        <td class="text-right">{{ currency()->rupiah($item->value, setting()->get('currency_symbol')) }}</td>
                    </tr>

                    {{-- sum sub total --}}
                    @php
                        $sub_total = intval($sub_total + $item->value);
                    @endphp
                @endforeach

                <tr>
                    <td colspan="5" class="font-w600 text-right">Subtotal</td>
                    <td class="text-right">{{ currency()->rupiah($sub_total, setting()->get('currency_symbol')) }}</td>
                </tr>
                <tr>
                    <td colspan="5" class="font-w600 text-right">Total Tax</td>
                    <td class="text-right">{{ currency()->rupiah($totalTax, setting()->get('currency_symbol')) }}</td>
                </tr>
                {{-- sum total amount --}}
                @php
                    $total_amount = $sub_total + $totalTax;
                @endphp
                <tr class="table-warning">
                    <td colspan="5" class="font-w700 text-uppercase text-right">Total</td>
                    <td class="font-w700 text-right">{{ currency()->rupiah($total_amount, setting()->get('currency_symbol')) }}</td>
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