@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('patient::labels.patient.management'))

@section('content')
<div class="row row-deck">
    <div class="col-lg-4">
        <div class="block block-rounded block-link-shadow text-center">
            <div class="block-content bg-gd-dusk">
                <div class="push">
                    <img class="img-avatar img-avatar-thumb" src="assets/media/avatars/avatar15.jpg" alt="">
                </div>
                <div class="pull-r-l pull-b py-10 bg-black-op-25">
                    <div class="font-w600 mb-5 text-white">
                        {{ $appointment->staff->user->full_name }}
                    </div>
                    <div class="font-size-sm text-white-op">{{ $appointment->staff->designation->name }}</div>
                </div>
            </div>
            <div class="block-content">
                <div class="row items-push text-center">
                    <div class="col-6">
                        <div class="mb-5"><i class="si si-bag fa-2x"></i></div>
                        <div class="font-size-sm text-muted">List Konsultasi</div>
                    </div>
                    <div class="col-6">
                        <div class="mb-5"><i class="si si-briefcase fa-2x"></i></div>
                        <div class="font-size-sm text-muted">Total Pasien</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Data Pasien</h3>
                <div class="block-options">
                    <a href="{{ route('admin.patient.appointment.index') }}" class="btn btn-sm btn-secondary mr-5 mb-5">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="block-content">
                <table class="table table-borderless table-sm table-striped">
                    <tbody>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">APP ID</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->appointment_no }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Pasien ID</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->patient_unique_id }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Pasien</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->patient_name }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Usia</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->age }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Hobi</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->hobby }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Telepon</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->phone_number }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Tgl Lahir</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->dob }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Jenis Kelamin</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->gender }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Gol. Darah</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->blood->blood_name }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Flag</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->flag['name'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-left">
                                <span class="text-muted">Catatan</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->notes }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection