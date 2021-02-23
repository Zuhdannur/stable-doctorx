@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.access.roles.management'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('labels.backend.access.roles.management')</h3>
        <div class="block-options">
            @include('backend.auth.role.includes.header-buttons')
        </div>
    </div>
    <div class="block-content">
        <div class="table-responsive">
            <table class="table table-striped table-vcenter">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 100px;">#</th>
                        <th>@lang('labels.backend.access.roles.table.role')</th>
                        <th>@lang('labels.backend.access.roles.table.abilities')</th>
                        <th>@lang('labels.backend.access.roles.table.number_of_users')</th>
                        <th class="text-center" style="width: 100px;">@lang('labels.general.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $key => $role)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td class="font-w600">{{ ucwords($role->name) }}</td>
                            <td>
                                
                                @if($role->name == 'superadmin')
                                    <span class="badge badge-primary">
                                        @lang('labels.general.all')
                                    </span>
                                @else
                                    @if($role->abilities->count())
                                        @foreach($role->abilities as $ability)
                                            <span class="badge badge-success">{{ ucwords($ability->name) }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge badge-warning">@lang('labels.general.none')</span>
                                    @endif
                                @endif
                                
                            </td>
                            <td>{{ $role->users->count() }}</td>
                            <td>{!! $role->action_buttons !!}</td>
                        </tr>
                    @endforeach
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
                    {!! $roles->total() !!} {{ trans_choice('labels.backend.access.roles.table.total', $roles->total()) }}
                </div>
            </div><!--col-->

            <div class="col-5">
                <div class="float-right">
                    {!! $roles->render() !!}
                </div>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->
@endsection
