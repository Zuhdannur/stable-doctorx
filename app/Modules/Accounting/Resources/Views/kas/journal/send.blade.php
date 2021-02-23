@extends('backend.layouts.app')

@section('title', app_name().' | '.__('accounting::menus.journal.title'))

@php
    // variabel default
    $no = 1;
    $sub_total = 0;
    $totalTax = 0;
    $total_amount = 0;

    // variabel for detailing
    $cash = $trx->journal->load('account')->where('tags', 'is_cash')->first();
    $acc_send = $trx->journal->load('account')->where('tags', 'is_purchase');
    $tax = $trx->journal->load('account')->where('tags', 'is_tax');
    $potongan = $trx->journal->load('account')->where('tags', 'is_potongan')->first();

    // sum total tax
    if(isset($tax)){
        foreach ($tax as $item ) {
            $totalTax = intVal($totalTax + $item->value);
        }
    }

@endphp

@section('content')

    <div class="block" id="my-block2">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{$trx->transaction_code}}</h3>
            <div class="block-options">
                @if ($trx->attachment_file)
                    <a class='btn btn-sm btn-primary'href="{{ route('admin.accounting.account.journal.download', $trx->id)}}" title="Lampiran"><i class="fa fa-paperclip"></i> Download Lampiran</a>
                @else
                    <span>Tidak ada lampiran&nbsp;</span>
                @endif
                <div class="btn-group" role="group" aria-label="Third group">
                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" id="toolbarDrop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="si si-printer"></i> Cetak</button>
                    <div class="dropdown-menu" aria-labelledby="toolbarDrop" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 34px, 0px);">
                        <h6 class="dropdown-header">Format</h6>
                        <a class="dropdown-item printInv" href="javascript:void(0)">
                            <i class="fa fa-fw fa-bell mr-5"></i>Print Preview
                        </a>
                        <a class="dropdown-item" href="{{ route('admin.accounting.cash.journal.send.pdf', $trx->id) }}" target="_blank">
                            <i class="fa fa-fw fa-envelope-o mr-5"></i>PDF Preview
                        </a>
                    </div>
                </div>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                <a href="{{ url()->previous() }}" class="btn-block-option">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        
        <div class="block-content">
            <!-- Send Info -->
            <div class="row px-5 pt-5">
                <div class="col-3 contact-details">
                    <h3 class="block-title">Penerima : {{ $trx->person }}</h3>
                    <h3 class="block-title">Kirim Dari : {{ isset($cash->account) ? $cash->account->account_code .' - '. $cash->account->account_name : ''}}</h3>
                    <span>Tanggal Transaksi : {{ isset($trx) ? date('d/m/Y',strtotime($trx->trx_date)) : '' }}</span>
                </div>
                <div class="col-1 offset-2 logo">
                    <img width="125px" height="125px" src="{{ asset(setting()->get('logo_square')) }}">
                </div>
                <div class="invoice-details col-3 offset-3 text-right">
                    <span><em>dibuat oleh : {{ isset($trx->user->first_name) ? $trx->user->first_name." ".$trx->user->last_name : '' }}</em></span><br>
                    <span><em>pada : {{ $trx->updated_at }}</em></span>
                </div>
            </div>
            <!-- END Send Info -->

            <!-- Table -->
            <div class="table-responsive push mt-4">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>Pembayaran Untuk</th>
                            <th class="text-right" style="width: 220px;">Deskripsi</th>
                            <th class="text-right" style="width: 140px;">Pajak</th>
                            <th class="text-right" style="width: 180px;">Jumlah</th>
                        </tr>
                    </thead>
                    @if (isset($acc_send))
                        @foreach ($acc_send as $item)
                            <tr>
                                <td class="text-center">{{ ($no++) }}</td>
                                <td>
                                    <p class="font-w600 mb-5">{{ $item->account['account_name'] }}</p>
                                    <div class="text-muted">{{ $item->account['account_code'] ." ". $item->account['account_name'] }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-pill badge-primary">{{ $item->decription }}</span>
                                </td>
                                <td class="text-right">{{ $item->tax }}</td>
                                <td class="text-right">{{ currency()->rupiah($item->value, setting()->get('currency_symbol')) }}</td>
                            </tr>

                            {{-- sum sub total --}}
                            @php
                                $sub_total = intval($sub_total + $item->value);
                            @endphp
                        @endforeach
                    @endif
                    <tr>
                        <td colspan="4" class="font-w600 text-right">Subtotal</td>
                        <td class="text-right">{{ currency()->rupiah($sub_total, setting()->get('currency_symbol')) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="font-w600 text-right">Total Tax</td>
                        <td class="text-right">{{ currency()->rupiah($totalTax, setting()->get('currency_symbol')) }}</td>
                    </tr>

                    <tr>
                        <td colspan="4" class="font-w600 text-right">Potongan ( {{ isset($potongan->account) ? $potongan->account->account_name : '' }}) : {{ ' %' }}</td>
                        <td class="text-right">{{ currency()->rupiah(isset($potongan->value) ? $potongan->value : 0, setting()->get('currency_symbol')) }}</td>
                    </tr>

                    {{-- sum total amount --}}
                    @php
                        $total_amount = $sub_total + $totalTax;
                        if(isset($potongan)){
                            $total_amount = $total_amount - $potongan->value;
                        }
                    @endphp
                    <tr class="table-warning">
                        <td colspan="4" class="font-w700 text-uppercase text-right">Total</td>
                        <td class="font-w700 text-right">{{ currency()->rupiah($total_amount, setting()->get('currency_symbol')) }}</td>
                    </tr>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <!-- END Table --> 

            <!-- Footer -->
            <p class="text-center">Ringkasan dan Catatan : {{ $trx->memo ?? '-' }}</p>
            <!-- END Footer -->

        </div>
    </div>

    <!-- Transaksi -->
    <div class="block" id="my-block2">

        <div class="block-header block-header-default">
            <h3 class="block-title">Jurnal Transaksi</h3>
        </div>

        <div class="block-content">
            <!-- Table -->
            <div class="table-responsive push">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>Nama Akun</th>
                            <th class="text-right" style="width: 120px;">Debit</th>
                            <th class="text-right" style="width: 120px;">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $kredit = 0;
                            $debit = 0;
                        @endphp

                        @foreach($trx->journal as $key => $item)
                        <tr>                                    
                            <td class="text-center">{{ ($key+1) }}</td>
                            <td>
                                <p class="font-w600 mb-5">{{ $item->account['account_code'] ." - ". $item->account['account_name'] }}</p>
                            </td>
                            @if ($item->type == config('finance_journal.types.debit'))
                                <td class="text-right">{{ currency()->rupiah($item->value, setting()->get('currency_symbol')) }}</td>
                                <td class="text-right"></td>
                                @php
                                    $debit = $debit + $item->value;
                                @endphp
                            @else
                                <td class="text-right"></td>
                                <td class="text-right">{{ currency()->rupiah($item->value, setting()->get('currency_symbol')) }}</td>
                                @php
                                    $kredit = $kredit + $item->value;
                                @endphp
                            @endif
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="font-w600 text-right"></td>
                            <td class="text-right">{{ currency()->rupiah($debit, setting()->get('currency_symbol')) }}</td>
                            <td class="text-right">{{ currency()->rupiah($kredit, setting()->get('currency_symbol')) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- END Table -->   
        </div>
    </div>
    <!-- END Transaksi -->

    <script type="text/javascript">
        jQuery(function () {
        
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
        
            $(document).on('click', '.printInv', function() {
                $.ajax({
                    url: "{{ route('admin.accounting.cash.journal.send.print', $trx->id) }}",
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
            });
        });
    </script>

@endsection