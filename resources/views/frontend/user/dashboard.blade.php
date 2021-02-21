@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <!-- User Info -->
    <div class="bg-image bg-image-bottom" style="background-image: url('media/photos/photo13@2x.jpg');">
        <div class="bg-primary-dark-op py-30">
            <div class="content content-full text-center">
                <!-- Avatar -->
                <div class="mb-15">
                    <a class="img-link" href="be_pages_generic_profile.html">
                        <img class="img-avatar img-avatar96 img-avatar-thumb" src="{{ $logged_in_user->picture }}" alt="">
                    </a>
                </div>
                <!-- END Avatar -->

                <!-- Personal -->
                <h1 class="h3 text-white font-w700 mb-10">{{ $logged_in_user->name }}</h1>
                <h2 class="h5 text-white-op">
                                    {{ $logged_in_user->email }} <a class="text-primary-light" href="javascript:void(0)">@lang('strings.frontend.general.joined') {{ timezone()->convertToLocal($logged_in_user->created_at, 'F jS, Y') }}</a>
                </h2>
                <!-- END Personal -->
                
                <!-- Actions -->
                <a href="{{ route('frontend.user.account')}}" class="btn btn-rounded btn-hero btn-sm btn-alt-success mb-5">
                    <i class="fa fa-user-circle mr-5"></i> @lang('navs.frontend.user.account')
                </a>
                @can('view backend')
                    <a href="{{ route('admin.dashboard')}}" class="btn btn-rounded btn-hero btn-sm btn-alt-primary mb-5">
                        <i class="fa fa-user-secret"></i> @lang('navs.frontend.user.administration')
                    </a>
                @endcan
                <!-- END Actions -->
            </div>
        </div>
    </div>
    <!-- END User Info -->
@endsection
