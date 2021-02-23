@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.access.abilities.management'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('labels.backend.access.abilities.management')</h3>
        <div class="block-options">
            @include('backend.auth.ability.includes.header-buttons')
        </div>
    </div>
    <div class="block-content">
        <div class="table-responsive">
            <table class="table table-striped table-vcenter">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 100px;">#</th>
                        <th>@lang('labels.backend.access.abilities.table.ability')</th>
                        <th>@lang('labels.backend.access.abilities.table.groups')</th>
                        <th>@lang('labels.backend.access.abilities.table.number_of_users')</th>
                        <th class="text-center" style="width: 100px;">@lang('labels.general.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($abilities as $key => $ability)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td class="font-w600">{{ ucwords($ability->name) }}</td>
                            <td class="font-w600">{{ ucwords($ability->group) }}</td>
                            <td>{{ $ability->users->count() }}</td>
                            <td>{!! $ability->action_buttons !!}</td>
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
                    {!! $abilities->total() !!} {{ trans_choice('labels.backend.access.abilities.table.total', $abilities->total()) }}
                </div>
            </div><!--col-->

            <div class="col-5">
                <div class="float-right">
                    {!! $abilities->render() !!}
                </div>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->
@endsection
