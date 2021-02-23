<div class="table-responsive my-4">
    <table class="table table-bordered table-stripped table-hover">
        <h5>History Pembayaran</h5>
        <thead>
            <tr>
                <th class="text-center" style="width: 60px;"></th>
                <th class="text-center">Tgl. Bayar</th>
                <th>Jumlah Yang dibayarkan</th>
                <th class="text-right">Jumlah Diterima</th>
            </tr>
        </thead>
        <tbody>
            @foreach($billing->paymentHistory as $key => $item)
                <tr>
                    <td class="text-center">{{ ($key+1) }}</td>
                    <td>{{ $item->created_at }}</td>
                    <td class="text-right">{{ currency()->rupiah($item->total_pay, setting()->get('currency_symbol')) }}</td>
                    <td class="text-right">{{ currency()->rupiah($item->in_paid, setting()->get('currency_symbol')) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>