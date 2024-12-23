@extends('backend.layouts.app')

@section('title', __('labels.backend.access.users.management') . ' | ' . __('labels.backend.access.users.deactivated'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('labels.backend.access.users.management') <small class="text-muted">@lang('labels.backend.access.users.deactivated')</small></h3>
        <div class="block-options">
            @include('backend.auth.user.includes.breadcrumb-links')
        </div>
    </div>
    <div class="block-content">
        <div class="table-responsive">
            <table class="table table-striped table-vcenter">
                <thead>
                        <tr>
                            <th>@lang('labels.backend.access.users.table.last_name')</th>
                            <th>@lang('labels.backend.access.users.table.first_name')</th>
                            <th>@lang('labels.backend.access.users.table.email')</th>
                            <th>@lang('labels.backend.access.users.table.confirmed')</th>
                            <th>@lang('labels.backend.access.users.table.roles')</th>
                            <th>@lang('labels.backend.access.users.table.other_permissions')</th>
                            <th>@lang('labels.backend.access.users.table.social')</th>
                            <th>@lang('labels.backend.access.users.table.last_updated')</th>
                            <th>@lang('labels.general.actions')</th>
                        </tr>
                </thead>
                <tbody>
                        @if($users->count())
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->last_name }}</td>
                                    <td>{{ $user->first_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{!! $user->confirmed_label !!}</td>
                                    <td>{!! $user->roles_label !!}</td>
                                    <td>{!! $user->permissions_label !!}</td>
                                    <td>{!! $user->social_buttons !!}</td>
                                    <td>{{ $user->updated_at->diffForHumans() }}</td>
                                    <td>{!! $user->action_buttons !!}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="9"><p class="text-center">@lang('strings.backend.access.users.no_deactivated')</p></td></tr>
                        @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-7">
                <div class="float-left">
                    {!! $users->total() !!} {{ trans_choice('labels.backend.access.users.table.total', $users->total()) }}
                </div>
            </div><!--col-->

            <div class="col-5">
                <div class="float-right">
                    {!! $users->render() !!}
                </div>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->
@endsection
