@extends('backend.layouts.app')

@section('title', app_name().' | '.__('crm::menus.radeem_point'))

@section('content')
@if (isset($error))
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    {{  $error['msg'] }}
    </div>
@endif
    <div class="block" id="my-block">
        {{-- content header --}}
        <div class="block-header block-header-default">
            <h3 class="block-title">
                @lang('crm::labels.radeem.main')
            </h3>
        </div>

        {{-- content body --}}
        <div class="block-content">
            <form method="post" class="js-validation-bootstrap" id="formGeneral" autocomplete="off">
                @csrf
                <div class="row-mt-4">
                    <div class="col">
                        <div class="form-group row">
                            <label for="pid" class="col-md-2 form-control-label">Patient Unique ID</label>
                            <div class="col-md-4">
                            <input type="text" name="pid" id="pid" class="form-control" value="{{ isset($error['data']) ? $error['data'] : '' }}">
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari Pasien</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($patient))
        @include('crm::radeem_point.form-radeem')
    @endif
@endsection