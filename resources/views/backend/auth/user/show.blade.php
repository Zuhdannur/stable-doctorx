@extends('backend.layouts.app')

@section('title', __('labels.backend.access.users.management') . ' | ' . __('labels.backend.access.users.view'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('labels.backend.access.users.management') <small class="text-muted">@lang('labels.backend.access.users.view')</small></h3>
        <div class="block-options">
            @include('backend.auth.user.includes.breadcrumb-links')
        </div>
    </div>
    <div class="block-content">
        @include('backend.auth.user.show.tabs.overview')
    </div>
    <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
        <strong>@lang('labels.backend.access.users.tabs.content.overview.created_at'):</strong> {{ timezone()->convertToLocal($user->created_at) }} ({{ $user->created_at->diffForHumans() }}),
                    <strong>@lang('labels.backend.access.users.tabs.content.overview.last_updated'):</strong> {{ timezone()->convertToLocal($user->updated_at) }} ({{ $user->updated_at->diffForHumans() }})
                    @if($user->trashed())
                        <strong>@lang('labels.backend.access.users.tabs.content.overview.deleted_at'):</strong> {{ timezone()->convertToLocal($user->deleted_at) }} ({{ $user->deleted_at->diffForHumans() }})
                    @endif
    </div>
</div>
@endsection
