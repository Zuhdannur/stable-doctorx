@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')
    <!-- Info -->
    <div class="block">
        <div class="block-content">
            <div class="row nice-copy">
                <div class="col-md-3 py-30">
                    <h3 class="h4 font-w700 text-uppercase pb-10 border-b border-3x">Facial <span class="text-primary">Treatment</span>.</h3>
                    <p class="mb-0">Facial adalah perawatan wajah bagi kulit normal atau bermasalah, adapun tujuannya adalah untuk memanjakan kulit, melancarkan sirkulasi darah, mencegah penuaan dini pada kulit wajah.</p>
                </div>
                <div class="col-md-3 py-30">
                    <h3 class="h4 font-w700 text-uppercase pb-10 border-b border-3x">What We <span class="text-primary">Do</span>.</h3>
                    <p class="mb-0">Laser Nd Yog adalah salah satu jenis teknologi dalam bidang kecantikan dengan menggunakan dua gelombang yaitu gelombang 532 nm dan 1064 nm kata dr Petrina , CID, SP, AK dari Endrea Spa & Clinic.</p>
                </div>
                <div class="col-md-3 py-30">
                    <h3 class="h4 font-w700 text-uppercase pb-10 border-b border-3x">Slimming.</h3>
                    <p class="mb-0">Slimming master menggunakan bantalan elektrode panas (teori panas infrared) dan memiliki tekanan udara serta lokasi elektrode yang di atur berdasarkan titik-titik akupuntur pada abdomen.</p>
                </div>
                <div class="col-md-3 py-30">
                    <h3 class="h4 font-w700 text-uppercase pb-10 border-b border-3x">Whitening <span class="text-primary">Injection</span>.</h3>
                    <p class="mb-0">Whitening Injection (Suntik Putih) adalah treatment untuk mencerahkan warna kulit dengan pemberian obat tertentu melalui suntikan / infusan .</p>
                </div>
                <!--<div class="col-md-12 py-30">
                    <h3 class="h4 font-w700 text-uppercase text-center pb-10 border-b border-3x mb-0">Product <span class="text-primary">Statistics</span>.</h3>

                    <div class="row text-center">
                        <div class="col-sm-6 col-md-3 py-30">
                            <div class="mb-20">
                                <i class="si si-briefcase fa-3x text-primary"></i>
                            </div>
                            <div class="font-size-h1 font-w700 text-black mb-5" data-toggle="countTo" data-to="9600" data-after="+">0</div>
                            <div class="font-w600 text-muted text-uppercase">Facial Wash</div>
                        </div>
                        <div class="col-sm-6 col-md-3 py-30">
                            <div class="mb-20">
                                <i class="si si-users fa-3x text-primary"></i>
                            </div>
                            <div class="font-size-h1 font-w700 text-black mb-5" data-toggle="countTo" data-to="760" data-after="+">0</div>
                            <div class="font-w600 text-muted text-uppercase">Serum Anti Aging</div>
                        </div>
                        <div class="col-sm-6 col-md-3 py-30">
                            <div class="mb-20">
                                <i class="si si-clock fa-3x text-primary"></i>
                            </div>
                            <div class="font-size-h1 font-w700 text-black mb-5" data-toggle="countTo" data-to="88600" data-after="+">0</div>
                            <div class="font-w600 text-muted text-uppercase">Loose Powder</div>
                        </div>
                        <div class="col-sm-6 col-md-3 py-30">
                            <div class="mb-20">
                                <i class="si si-badge fa-3x text-primary"></i>
                            </div>
                            <div class="font-size-h1 font-w700 text-black mb-5" data-toggle="countTo" data-to="60" data-after="+">0</div>
                            <div class="font-w600 text-muted text-uppercase">Paket Acne</div>
                        </div>
                    </div>
                </div>-->
            </div>
        </div>
    </div>
    <!-- END Info -->

    <!-- Testimonial -->
    <div class="bg-image" style="background-image: url({{ asset('media/photos/photo30@2x.jpg') }});">
        <div class="block-content block-content-full bg-primary-dark-op text-center">
            <div class="py-30 invisible" data-toggle="appear">
                <div class="py-10">
                    <img class="img-avatar img-avatar96 img-avatar-thumb" src="{{ asset('media/avatars/avatar.jpg') }}" alt="">
                </div>
                <div class="row justify-content-center py-10">
                    <div class="col-md-8">
                        <div class="mb-10">
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-star text-warning"></i>
                        </div>
                        <h3 class="font-w700 text-white mb-10">{{ app_name() }} <i class="fa fa-thumbs-up"></i></h3>
                        <p class="font-size-lg text-body-bg-dark">{{ setting()->get('address') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Testimonial -->
@endsection
