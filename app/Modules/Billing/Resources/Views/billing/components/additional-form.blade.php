<div class="row px-5 pt-5">
    <div class="col-md-4">
        <div class="form-group row">
            <label for="acc_cash" class="form-control-label">Bayar Ke :</label>
            <div class="col-md-8">
                <select name="acc_cash" id="acc_cash" class="form-control select2 required" width="100%">
                    {!! $cashAccountList !!}
                </select>
            </div>
        </div>
    </div>
    @if ($billing->status == config('billing.invoice_unpaid'))
        <div class="col-md-4">
            <div class="form-group row">
                <label for="marketing" class="form-control-label">Marketing Activity</label>
                <div class="col-md-8">
                    <select name="marketing" id="marketing" class="form-control select2" width="100%">
                        {!! $marketingList !!}
                    </select>
                </div>
            </div>
        </div>        
    @endif
    <div class="col-md-4">
        <div class="form-group row">
            <label for="isPayAll" class="form-control-label col-md-4">Bayar Semua</label>
            <div class="col-md-8">
                <select name="isPayAll" id="isPayAll" class="form-control select2" style="width: 100%">
                    <option value="1" selected>Ya</option>
                    <option value="2">Tidak</option>
                </select>
            </div>
        </div>
    </div>
</div>