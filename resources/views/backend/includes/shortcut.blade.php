@if(Route::currentRouteName() == 'admin.dashboard')
<div class="bg-body-dark">
    <div class="content">
        <div class="row">
            <div class="col-6 col-md-4 col-xl-2">
                <a class="block block-rounded text-center" href="{{ route('admin.patient.index') }}">
                    <div class="block-content">
                        <p class="mt-5 mb-10">
                            <i class="fa fa-address-card-o text-primary fa-2x d-xl-none"></i>
                            <i class="fa fa-address-card-o text-primary fa-3x d-none d-xl-inline-block"></i>
                        </p>
                        <p class="font-w600 font-size-sm text-uppercase">Data Pasien</p>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <a class="block block-rounded text-center" href="{{ route('admin.patient.appointment.index') }}">
                    <div class="block-content">
                        <p class="mt-5 mb-10">
                            <i class="si si-users text-success fa-2x d-xl-none"></i>
                            <i class="si si-users text-success fa-3x d-none d-xl-inline-block"></i>
                        </p>
                        <p class="font-w600 font-size-sm text-uppercase">Data Konsultasi</p>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <a class="block block-rounded text-center" href="{{ route('admin.patient.treatment.index') }}">
                    <div class="block-content">
                        <p class="mt-5 mb-10">
                            <i class="fa fa-american-sign-language-interpreting text-warning fa-2x d-xl-none"></i>
                            <i class="fa fa-american-sign-language-interpreting text-warning fa-3x d-none d-xl-inline-block"></i>
                        </p>
                        <p class="font-w600 font-size-sm text-uppercase">Data Treatment</p>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <a class="block block-rounded text-center" href="{{ route('admin.billing.index') }}">
                    <div class="block-content">
                        <p class="mt-5 mb-10">
                            <i class="si si-list text-info fa-2x d-xl-none"></i>
                            <i class="si si-list text-info fa-3x d-none d-xl-inline-block"></i>
                        </p>
                        <p class="font-w600 font-size-sm text-uppercase">Data Transaksi</p>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <a class="block block-rounded text-center" href="{{ route('frontend.user.account') }}">
                    <div class="block-content">
                        <p class="mt-5 mb-10">
                            <i class="fa fa-pencil text-gray fa-2x d-xl-none"></i>
                            <i class="fa fa-pencil text-gray fa-3x d-none d-xl-inline-block"></i>
                        </p>
                        <p class="font-w600 font-size-sm text-uppercase">@lang('navs.frontend.user.account')</p>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <a class="block block-rounded text-center" href="javascript:void(0)">
                    <div class="block-content">
                        <p class="mt-5 mb-10">
                            <i class="fa fa-wrench text-gray fa-2x d-xl-none"></i>
                            <i class="fa fa-wrench text-gray fa-3x d-none d-xl-inline-block"></i>
                        </p>
                        <p class="font-w600 font-size-sm text-uppercase">Settings</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endif