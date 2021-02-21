@extends('frontend.layouts.app')

@section('content')
<div class="row">
    <div class="col-xl-4">
        <div class="block text-center">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <i class="fa fa-fw fa-graduation-cap"></i>
                    @lang('navs.frontend.user.account')
                </h3>
            </div>
            <div class="block-content block-content-full bg-image" style="background-image: url({{ asset('media/photos/photo28.jpg') }});">
                <img class="img-avatar img-avatar-thumb" src="{{ $logged_in_user->picture }}" alt="">
            </div>
            <div class="block-content block-content-full block-content-sm bg-flat-dark">
                <div class="font-w600 text-white mb-5">{{ $logged_in_user->name }}</div>
                <div class="font-size-sm text-white-op">{{ $logged_in_user->email }}</div>
            </div>
            <div class="block-content block-content-full block-content-sm bg-body-light">
                <table class="table table-sm table-striped table-borderless text-left">
                    <tbody>
                        <tr>
                            <td class="font-w600 font-size-sm">@lang('labels.frontend.user.profile.created_at')</td>
                            <td class="font-size-sm text-muted">{{ timezone()->convertToLocal($logged_in_user->created_at) }} ({{ $logged_in_user->created_at->diffForHumans() }})</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm">@lang('labels.frontend.user.profile.last_updated')</td>
                            <td class="font-size-sm text-muted">{{ timezone()->convertToLocal($logged_in_user->updated_at) }} ({{ $logged_in_user->updated_at->diffForHumans() }})</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="block">
            <ul class="nav nav-tabs nav-tabs-block" data-toggle="tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#btabs-animated-slideright-information">@lang('labels.frontend.user.profile.update_information')</a>
                </li>
                @if($logged_in_user->canChangePassword())
                <li class="nav-item">
                    <a class="nav-link" href="#btabs-animated-slideright-password">@lang('navs.frontend.user.change_password')</a>
                </li>
                @endif
            </ul>
            <div class="block-content block-content-full tab-content overflow-hidden">
                <div class="tab-pane fade fade-right show active" id="btabs-animated-slideright-information" role="tabpanel">
                    @include('frontend.user.account.tabs.edit')
                </div>
                @if($logged_in_user->canChangePassword())
                <div class="tab-pane fade fade-right" id="btabs-animated-slideright-password" role="tabpanel">
                    @include('frontend.user.account.tabs.change-password')
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
