@extends('backend.layouts.app')

@section('title', app_name().' | '.__('accounting::menus.journal.title'))

@section('content')
     {{-- content title --}}
     <h2 class="content-heading d-print-none">
        <a href="{{ route('admin.accounting.account.journal') }}" class="btn btn-sm btn-rounded btn-success float-right">Buat Jurnal Umum</a>
        Detail Jurnal Umum
    </h2>

    <!-- Transaksi -->
    <div class="block" id="my-block2">
        
        <div class="block-header block-header-default">
            <h3 class="block-title">{{$trx->transaction_code}}</h3>
            <div class="block-options">
                @if ($trx->attachment_file)
                    <a class='btn btn-sm btn-primary'href="{{ route('admin.accounting.account.journal.download', $purchase->id)}}" title="Lampiran"><i class="fa fa-paperclip"></i> Download Lampiran</a>
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

            <div class="row px-5 pt-5">
                <div class="col-3 contact-details">
                   
                </div>
                <div class="col-1 offset-2 logo">
                    <img width="125px" height="125px" src="{{ asset(setting()->get('logo_square')) }}">
                </div>
                <div class="invoice-details col-3 offset-3 text-right">
                    <h6>Dibuat Pada: {{ $trx->created_at }}</h6>
                </div>
            </div>
            <br>
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

            <!-- Footer -->
            <p class="text-center">Ringkasan dan Catatan : {{ $trx->memo ? $trx->memo : '-' }}</p>
            <!-- END Footer -->

        </div>
    </div>
    <!-- END Transaksi -->
    
@endsection