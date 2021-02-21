<div class="row"> 
    <div class="col-sm-12">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title">Statistik Pemasukan dan Pembelian ({{ date('Y') }})</h3>
                <div class="block-options">
                	<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
            	</div>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    {!! $billing->container() !!}
                </div>
            </div>
        </div>
    </div>
</div>
{!! $billing->script() !!}