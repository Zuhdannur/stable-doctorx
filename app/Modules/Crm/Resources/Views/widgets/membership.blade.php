<div class="row"> 
    <div class="col-sm-6">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title">Statistik Membership</h3>
                <div class="block-options">
                	<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
            	</div>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    {!! $membership->container() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                @php
                    \Carbon\Carbon::setLocale('id');

                    $now = Carbon\Carbon::now()->locale('id');
                @endphp
                <h3 class="block-title">Total Point Yang Ditukarkan Pada Bulan {{  $now->monthName }}</h3>
                <div class="block-options">
                	<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
            	</div>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    {!! $radeem_point->container() !!}
                </div>
            </div>
        </div>
    </div>
</div>

{!! $membership->script() !!}
{!! $radeem_point->script() !!}