<!doctype html>
<html lang="en">
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css'>
<head>
    <meta charset="UTF-8">
    <title>Print - #{{ $biaya->financeTrx->transaction_code }} - @yield('title', app_name())</title>

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
                    @switch($biaya->status)
                    @case(1)
                        <h2 class='text-success'>Lunas</h2>
                        @break
                    @default
                        @php
                            $due_date = date_create(date('Y-m-d' ,strtotime($biaya->due_date)));
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
                    <h5>Penerima: {{ $biaya->person }}</h5>
                    <h6>Dibuat Pada: {{ $biaya->created_at }}</h6>
                    <h6>Jatuh Tempo: {{ strtotime($biaya->due_date) > 0 ? date('d/m/y', strtotime($biaya->due_date)) : ' - ' }}</h6>
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
        <h3>{{ $biaya->financeTrx->transaction_code }}</h3>
        <table class="table table-hover" width="100%">
            <thead class="thead splitForPrint">
                <tr>
                    <th scope="col gray-ish">NO.</th>
                    <th scope="col gray-ish">Jenis Biaya</th>
                    <th scope="col gray-ish">Deskripsi</th>
                    <th class="text-right" scope="col gray-ish">Pajak</th>
                    <th class="text-right" scope="col gray-ish">Amount</th>
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

                @foreach($biaya->financeTrx->journal as $key => $item)

                    @if ($item->tags == 'is_biaya')
                        @php
                            $sub_total = $sub_total + $item->value;
                        @endphp
                        <tr>                                    
                            <th scope="row">{{ ($no++) }}</th>
                            <td class="item">{{ $item->account['account_code'] ." - ". $item->account['account_name'] }}</td>
                            <td class="text-center">{{ $item->decription }}</td>
                            <td class="text-right">{{ $item->tax }}</td>
                            <td class="text-right">{{ currency()->rupiah($item->value, setting()->get('currency_symbol')) }}</td>
                        </tr>
                    @elseif($item->tags == 'is_tax')
                        @php
                            $totalTax = intVal($totalTax + $item->value);
                        @endphp
                    @elseif($item->tags == 'is_potongan')
                        @php
                            $potongan_account = $item->account['account_name'];
                            $potonganValue = $item->value;
                        @endphp
                    @endif
                @endforeach

                <tr>
                    <td colspan="4" class="font-w600 text-right">Subtotal</td>
                    <td class="text-right">{{ currency()->rupiah($sub_total, setting()->get('currency_symbol')) }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="font-w600 text-right">Total Tax</td>
                    <td class="text-right">{{ currency()->rupiah($totalTax, setting()->get('currency_symbol')) }}</td>
                </tr>
                <tr>
                    @if ($biaya->financeTrx->potongan)
                        <td colspan="4" class="font-w600 text-right">Potongan ( {{ $potongan_account}}) : {{ $biaya->financeTrx->potongan.' %' }}</td>
                        <td class="text-right">{{ currency()->rupiah($potonganValue, setting()->get('currency_symbol')) }}</td>
                    @else
                        <td colspan="4" class="font-w600 text-right">Potongan 0%</td>
                        <td class="text-right">{{ currency()->rupiah(0, setting()->get('currency_symbol')) }}</td>
                    @endif
                </tr>
                <tr class="table-warning">
                    <td colspan="4" class="font-w700 text-uppercase text-right">Total</td>
                    <td class="font-w700 text-right">{{ currency()->rupiah($biaya->total, setting()->get('currency_symbol')) }}</td>
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