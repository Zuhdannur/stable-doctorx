@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('accounting::menus.biaya.title'))

@section('content')
<!-- Biaya -->
<h2 class="content-heading d-print-none">
    <a href="{{ route('admin.accounting.biaya.create') }}" class="btn btn-sm btn-rounded btn-success float-right">Buat Biaya Baru</a>
    Detail Biaya
</h2>
<div class="block" id="my-block2">
    <div class="block-header block-header-default">
        <h3 class="block-title">#{{$biaya->financeTrx->transaction_code}}</h3>
        <div class="block-options">
            @if ($biaya->financeTrx->attachment_file)
                <a class='btn btn-sm btn-primary'href="{{ route('admin.accounting.biaya.download', $biaya->id)}}" title="Lampiran"><i class="fa fa-paperclip"></i> Download Lampiran</a>
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
                    <a class="dropdown-item" href="{{ route('admin.accounting.biaya.pdf', $biaya->id) }}" target="_blank">
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

        <!-- Biaya Info -->
        <div class="row px-5 pt-5">
            <div class="col-3 contact-details">
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
                <span><em>diperbarui oleh : {{ isset($biaya->user->first_name) ? $biaya->user->first_name." ".$biaya->user->last_name : '' }}</em></span><br>
                <span><em>pada : {{ $biaya->updated_at }}</em></span>
            </div>
            <div class="col-1 offset-2 logo">
                <img width="125px" height="125px" src="{{ asset(setting()->get('logo_square')) }}">
            </div>
            <div class="invoice-details col-3 offset-3 text-right">
                <h6>Biaya Pengeluaran No. #{{ $biaya->financeTrx->transaction_code }}</h6>
                <h6>Dibuat Pada: {{ $biaya->created_at }}</h6>
                <h6>Jatuh Tempo: {{ strtotime($biaya->due_date) > 0 ? date('d/m/y', strtotime($biaya->due_date)) : ' - ' }}</h6>
            </div>
        </div>
        <!-- END Biaya Info -->

        <!-- Table -->
        <div class="table-responsive push">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Jenis Biaya</th>
                        <th class="text-center" style="width: 90px;">Deskripsi</th>
                        <th class="text-right" style="width: 120px;">Pajak</th>
                        <th class="text-right" style="width: 120px;">Total</th>
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
        <!-- END Table -->

        <!-- Footer -->
        <p class="text-center">Ringkasan dan Catatan : {{ $biaya->financeTrx->memo ? $biaya->financeTrx->memo : '-' }}</p>
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

                    @foreach($biaya->financeTrx->journal as $key => $item)
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
            url: "{{ route('admin.accounting.biaya.print', $biaya->id) }}",
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