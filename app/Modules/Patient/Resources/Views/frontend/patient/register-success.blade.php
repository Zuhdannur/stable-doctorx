@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.frontend.auth.register_box_title'))

@section('content')
@if(!empty($successMsg))
  <div class="alert alert-success"> {{ $successMsg }}</div>
@endif
<div class="row justify-content-center align-items-center">
    <div class="col-6 col-md-4 col-xl-12">
        <a class="block block-link-pop" href="javascript:void(0)">
            <div class="block-content block-content-full">
                <div class="text-right">
                    <i class="si si-wallet fa-2x text-success"></i>
                </div>
                <div class="row py-20 text-center">
                    <div class="col-6 border-r">
                        <div class="font-size-h3 font-w600">{{ $data['patient']['patient_name'] }} / {{ $data['patient']['patient_unique_id'] }}</div>
                        <div class="font-size-sm font-w600 text-uppercase text-muted">ID / NAMA PASIEN</div>
                    </div>
                    <div class="col-6">
                        <div class="font-size-h3 font-w600">{{ $data['admission']['admission_no'] }}</div>
                        <div class="font-size-sm font-w600 text-uppercase text-muted">NO ANTRIAN</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="row items-push text-center">
        <button type="button" class="btn btn-outline-primary mr-5 mb-5 d-none">
            <i class="fa fa-print mr-5"></i>Cetak
        </button>
        <a href="{{ route('patient.register') }}" class="btn btn-outline-primary mr-5 mb-5">
            <i class="fa fa-arrow-left mr-5"></i>Kembali
        </a>
    </div>
</div>
@endsection
