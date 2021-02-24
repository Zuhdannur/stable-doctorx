@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.backend.access.users.management'))

@section('content')
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{ __('labels.backend.access.users.management') }} <small
                    class="text-muted">{{ __('labels.backend.access.users.active') }}</small></h3>
            <div class="block-options">
                @include('backend.auth.user.includes.breadcrumb-links')
            </div>
        </div>
        <div class="block-content">
            @if(Auth()->user()->klinik->status == "pusat")
            <form action="{{ url('admin/auth/user') }}" method="GET">

                <div class="d-flex col-lg-4">
                    <select class="js-select2 form-control" id="flag" name="id_cabang" data-placeholder="Pilih"
                            style="width: 100%">
                        @if($cabang == "all")
                        <option value="all" selected>Semua</option>
                        @else
                            <option value="all">Semua</option>
                        @endif
                        @foreach(\App\Klinik::all() as $item)
                            @if($cabang == $item->id_klinik)
                                <option value="{{ @$item->id_klinik }}" selected>{{ @$item->nama_klinik }}</option>
                            @else
                            <option value="{{ @$item->id_klinik }}">{{ @$item->nama_klinik }}</option>
                            @endif
                        @endforeach
                    </select>
                    &nbsp;
                    <button class="btn btn-success">Cari</button>
                </div>
            </form>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-vcenter">
                    <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>@lang('validation.attributes.backend.access.users.username')</th>
                        <th>@lang('labels.backend.access.users.table.email')</th>
                        <th>@lang('labels.backend.access.users.table.confirmed')</th>
                        <th>@lang('labels.backend.access.users.table.roles')</th>
                        <th>@lang('labels.backend.access.users.table.other_permissions')</th>
                        <th>@lang('labels.backend.access.users.table.social')</th>
                        <th>Klinik</th>
                        <th>@lang('labels.backend.access.users.table.last_updated')</th>
                        <th>@lang('labels.general.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{!! $user->confirmed_label !!}</td>
                            <td>{!! $user->roles_label !!}</td>
                            <td>{!! $user->permissions_label !!}</td>
                            <td>{!! $user->social_buttons !!}</td>
                            <td>{!! @$user->klinik->nama_klinik !!}</td>
                            <td>{{ $user->updated_at->diffForHumans() }}</td>
                            <td>{!! $user->action_buttons !!}</td>
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

@push('js')
    <script>
        jQuery(function () {
            Codebase.helpers(['select2']);
        });
    </script>
@endpush
