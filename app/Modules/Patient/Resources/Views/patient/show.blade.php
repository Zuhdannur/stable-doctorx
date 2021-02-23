@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('patient::labels.patient.management'))

@section('content')

<div class="row">
    <div class="col-md-3">
        <div class="block text-center" id="my-block">
            <div class="block-content block-content-full bg-image" style="background-image: url({{ asset('media/photos/photo28.jpg') }});">

                <img class="img-avatar img-avatar-thumb" src="{{ $logged_in_user->picture }}" alt="">
            </div>
            <div class="block-content block-content-full block-content-sm bg-flat-dark">
                <div class="font-w600 text-white mb-5">{{ $patient->patient_name }}</div>
                <div class="font-size-sm text-white-op">{{ $patient->patient_unique_id }}</div>
                <div class="font-size-sm text-white-op">{{ isset($patient->membership->membership->name) ? $patient->membership->membership->name." Membership" : '' }}</div>
            </div>
            <div class="block-content block-content-full">
            <a class="btn btn-sm btn-rounded btn-alt-success" href="{{ route('admin.patient.edit', ['patient' => $patient]) }}">
                    <i class="fa fa-edit mr-5"></i>Edit
                </a>
                <a class="btn btn-sm btn-rounded btn-alt-secondary d-none" href="javascript:void(0)">
                    <i class="fa fa-trash mr-5"></i>Hapus
                </a>
            </div>
            <div class="block-content block-content-full block-content-sm bg-body-light">
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">No. Telepon</td>
                            <td class="font-size-sm text-muted text-right">{{ $patient->phone_number }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">No. WhatsApp</td>
                            <td class="font-size-sm text-muted text-right">{{ $patient->wa_number }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Email</td>
                            <td class="font-size-sm text-muted text-right">{{ $patient->email }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Jenis Kelamin</td>
                            <td class="font-size-sm text-muted text-right">{{ $patient->gender }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Tempat Lahir</td>
                            <td class="font-size-sm text-muted text-right">{{ $patient->birth_place }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Tanggal Lahir</td>
                            <td class="font-size-sm text-muted text-right">{{ $patient->dobstring }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Usia</td>
                            <td class="font-size-sm text-muted text-right">{{ $patient->age }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Agama</td>
                            <td class="font-size-sm text-muted text-right">{{ isset($patient->religion->name) ? $patient->religion->name : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Gol. Darah</td>
                            <td class="font-size-sm text-muted text-right">{{ $patient->blood->blood_name }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Kota</td>
                            <td class="font-size-sm text-muted text-right">{{ isset($patient->city->name) ? ucwords(strtolower($patient->city->name)) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Kecamatan</td>
                            <td class="font-size-sm text-muted text-right">{{ isset($patient->district->name) ? ucwords(strtolower($patient->district->name)) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Desa/Kelurahan</td>
                            <td class="font-size-sm text-muted text-right">{{ isset($patient->village->name) ? ucwords(strtolower($patient->village->name)) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Alamat</td>
                            <td class="font-size-sm text-muted text-right">{{ $patient->address }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Sumber Info</td>
                            <td class="font-size-sm text-muted text-right">{{ $patient->info->name }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Media</td>
                            <td class="font-size-sm text-muted text-right">
                                @if(isset($patient->media))
                                    {{ implode(", ", array_column($patient->media->toArray(), "media_name")) }}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="block">
            <ul class="nav nav-tabs nav-tabs-block align-items-center" data-toggle="tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#tab-konsultasi" name="konsultasi">Konsultasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab-treatment" name="treatment">Treatment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab-timeline" name="timeline">Timeline</a>
                </li>
                <li class="nav-item ml-auto">
                    <div class="btn-group btn-group-sm mr-15" id="actionTab">
                        
                    </div>
                </li>
            </ul>
            <div class="block-content block-content-full tab-content overflow-hidden ribbon ribbon-primary">
                <div class="tab-pane fade fade-right show active" id="tab-konsultasi" role="tabpanel">
                    @include('patient::patient.tabs.konsultasi')
                </div>
                <div class="tab-pane fade fade-right show" id="tab-treatment" role="tabpanel">
                    @include('patient::patient.tabs.treatment')
                </div>
                <div class="tab-pane fade fade-right show" id="tab-timeline" role="tabpanel">
                    @include('patient::patient.tabs.timeline')
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(function(){ 
        $('ul[data-toggle="tabs"]').on('shown.bs.tab', function (e) {
            var inTab = $(e.target).attr("name");

            if(inTab == 'timeline'){
                $('#actionTab').html('<button type="button" data-modal-id="modal-timeline" data-href="{{ route('admin.patient.timeline', $patient->patient_unique_id) }}" class="btn btn-info loadModal"><i class="fa fa-plus"></i> Tambah Timeline</button>');
            }else{
                $('#actionTab').html('');
            }
        });
    });
</script>
@endsection