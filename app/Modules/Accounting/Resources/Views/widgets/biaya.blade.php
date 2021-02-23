<div class="row">
    <div class="col-md-4">
        <div class="block block-rounded block-bordered block-link-shadow">
            <div class="block-header block-header-default bg-warning">
                <i class="fa fa-credit-card fa-2x text-secondary"></i><h3 class="block-title">&nbsp;&nbsp;Biaya Belum Dibayar</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="mb-10">
                    <div class="font-size-h2 font-w600">{{  currency()->rupiah($unpaid, setting()->get('currency_symbol' ))}}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="block block-rounded block-bordered block-link-shadow">
            <div class="block-header block-header-default bg-danger">
                <i class="fa fa-info-circle fa-2x text-white"></i><h3 class="block-title text-white">&nbsp;&nbsp;Biaya Jatuh Tempo</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="mb-10">
                    <div class="font-size-h2 font-w600">{{  currency()->rupiah($due_date, setting()->get('currency_symbol' ))}}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="block block-rounded block-bordered block-link-shadow">
            <div class="block-header block-header-default bg-success">
                <i class="fa fa-exchange fa-2x text-white"></i><h3 class="block-title text-white">&nbsp;&nbsp;Pelunasan Biaya Dalam 30 Hari</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="mb-10">
                    <div class="font-size-h2 font-w600">{{  currency()->rupiah($paid_last_30_days, setting()->get('currency_symbol' ))}}</div>
                </div>
            </div>
        </div>
    </div>
</div