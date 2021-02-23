
@extends('backend.layouts.app')

@section('title', app_name().' | '.__('accounting::menus.biaya.title'))

@section('content')
<!-- Biaya -->
<h2 class="content-heading d-print-none">
    <a href="{{ route('admin.accounting.biaya') }}" class="btn btn-sm btn-rounded btn-success float-right">Daftar Biaya</a>
    Detail Pembayaran Biaya
</h2>
<div class="block" id="my-block2">
    <div class="block-header block-header-default">
        <h3 class="block-title">#{{$trx->transaction_code}}</h3>
        <div class="block-options">
            @if ($trx->attachment_file)
                <a class='btn btn-sm btn-primary'href="{{ route('account.journal.download', $trx->id)}}" title="Lampiran"><i class="fa fa-paperclip"></i> Download Lampiran</a>
            @else
                <span>Tidak ada lampiran&nbsp;</span>
            @endif
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
            <a href="{{ url()->previous() }}" class="btn-block-option">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="block-content">

        <!-- Payment Info -->
        <div class="row px-5 pt-5">
            <div class="col-3 contact-details">
                @switch($trx->biayaPayment->biayaTrx->status)
                    @case(1)
                        <h2 class='text-success'>Lunas</h2>
                        @break
                    @default
                        @php
                            $due_date = date_create(date('Y-m-d' ,strtotime($trx->biayaPayment->biayaTrx->due_date)));
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
                <h5>Penerima: {{ $trx->person }}</h5>
                <span><em>diperbarui oleh : {{ isset($trx->user->first_name) ? $trx->user->first_name." ".$trx->user->last_name : '' }}</em></span><br>
                <span><em>pada : {{ $trx->updated_at }}</em></span>
            </div>
            <div class="col-1 offset-2 logo">
                <img width="125px" height="125px" src="{{ asset(setting()->get('logo_square')) }}">
            </div>
            <div class="invoice-details col-3 offset-3 text-right">
                <h6>{{ $trx->transaction_code }}</h6>
                <h6>Dibuat Pada: {{ $trx->created_at }}</h6>
                <h6>Jatuh Tempo: {{ strtotime($trx->biayaPayment->biayaTrx->due_date) > 0 ? date('d/m/y', strtotime($trx->biayaPayment->biayaTrx->due_date)) : ' - ' }}</h6>
            </div>
        </div>
        <!-- END Biaya Info -->

        <!-- Table -->
        <div class="table-responsive push">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Biaya</th>
                        <th class="text-right" style="width: 170px;">Total Pembayaran</th>
                        <th class="text-right" style="width: 170px;">Total Tagihan</th>
                        <th class="text-right" style="width: 170px;">Sisa Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $biaya = isset($trx->biayaPayment->biayaTrx->financeTrx->transaction_code) ? $trx->biayaPayment->biayaTrx->financeTrx->transaction_code : '';
                        $tmp_pembayaran = $trx->journal->where('tags', 'is_cash')->first();
                        $pembayaran = isset($tmp_pembayaran->value) ? $tmp_pembayaran->value : 0;
                        $total = isset($trx->biayaPayment->biayaTrx->total) ? $trx->biayaPayment->biayaTrx->total : 0;
                        $sisa_tagihan = isset($trx->biayaPayment->biayaTrx->remain_payment) ? $trx->biayaPayment->biayaTrx->remain_payment : 0;
                    @endphp
                    <tr>                                    
                        <td>
                            <a href="{{ route('admin.accounting.account.journal.detail', $trx->biayaPayment->biayaTrx->financeTrx )}}">
                                <p class="font-w600 mb-5">{{ $biaya }}</p>
                            </a>
                        </td>
                        <td class="text-right">{{ currency()->rupiah($pembayaran, setting()->get('currency_symbol')) }}</td>                              
                        <td class="text-right">{{ currency()->rupiah($total, setting()->get('currency_symbol')) }}</td>                              
                        <td class="text-right">{{ currency()->rupiah($sisa_tagihan, setting()->get('currency_symbol')) }}</td>                              
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- END Table -->

        <!-- Footer -->
        <p class="text-center">Ringkasan dan Catatan : {{ $trx->memo ? $trx->memo : '-' }}</p>
        <!-- END Footer -->
      
    </div>
</div>
<!-- END Biaya -->

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
@endsection