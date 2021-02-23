<div class="row row-deck">
    <div class="col-lg-12">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Data Pasien</h3>
                <div class="block-options d-none">
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
                                <span class="text-muted text-primary font-w600">{{ $prescription->appointment->appointment_no }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Pasien ID</span>
                            </td>
                            <td class="text-right">
                                <span class="text-muted text-primary font-w600">{{ $prescription->appointment->patient->patient_unique_id }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Pasien</span>
                            </td>
                            <td class="text-right">
                                <span class="text-muted text-primary font-w600">{{ $prescription->appointment->patient->patient_name }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Usia</span>
                            </td>
                            <td class="text-right">
                                <span class="text-muted text-primary font-w600">{{ $prescription->appointment->patient->age }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Hobi</span>
                            </td>
                            <td class="text-right">
                                <span class="text-muted text-primary font-w600">{{ $prescription->appointment->patient->hobby }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Telepon</span>
                            </td>
                            <td class="text-right">
                                <span class="text-muted text-primary font-w600">{{ $prescription->appointment->patient->phone_number }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Tgl Lahir</span>
                            </td>
                            <td class="text-right">
                                <span class="text-muted text-primary font-w600">{{ $prescription->appointment->patient->dob }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Jenis Kelamin</span>
                            </td>
                            <td class="text-right">
                                <span class="text-muted text-primary font-w600">{{ $prescription->appointment->patient->gender }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Gol. Darah</span>
                            </td>
                            <td class="text-right">
                                <span class="text-muted text-primary font-w600">{{ $prescription->appointment->patient->blood->blood_name }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Flag</span>
                            </td>
                            <td class="text-right">
                                <span class="text-muted text-primary font-w600">{{ $prescription->appointment->patient->flag['name'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left" colspan="6">
                                <span class="text-muted">Problem: {{ $prescription->appointment->notes }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Catatan</h3>
    </div>
    <div class="block-content">
        <div class="row items-push">
            <div class="col-md-6">
                <h6>Keluhan</h6>
                <p>@markdown($prescription->complaint)</p>
            </div>
            <div class="col-md-6">
                <h6>Riwayat Perawatan dan Alergi</h6>
                <p>@markdown($prescription->treatment_history)</p>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck">
    <div class="col-lg-12">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Resep/Obat</h3>
            </div>
            <div class="block-content">
                <table class="table table-borderless table-sm table-striped">
                    <thead>
                        <th>#</th>
                        <th>Resep</th>
                        <th>Instruksi</th>
                    </thead>
                    <tbody>
                        @foreach ($prescription->detail as $key => $item)
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">{{ ($key+1) }}</span>
                            </td>
                            <td>
                                <span class="text-muted font-w600">{{ $item->name }}</span>
                            </td>
                            <td>
                                <span class="text-muted font-w600">{{ $item->instruction }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck">
    <div class="col-lg-12">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Tindakan</h3>
            </div>
            <div class="block-content">
                <table class="table table-borderless table-sm table-striped">
                    <thead>
                        <th>#</th>
                        <th>Tindakan</th>
                        <th>Keterangan</th>
                    </thead>
                    <tbody>
                        @foreach ($prescription->treatment as $k => $treatment)
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">{{ ($k+1) }}</span>
                            </td>
                            <td>
                                <span class="text-muted font-w600">{{ $treatment->name }}</span>
                            </td>
                            <td>
                                <span class="text-muted font-w600">{{ $treatment->description }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck">
    <div class="col-lg-12">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Diagnosa</h3>
            </div>
            <div class="block-content">
                <table class="table table-borderless table-sm table-striped">
                    <thead>
                        <th>#</th>
                        <th>Diagnosa</th>
                        <th>Instruksi</th>
                    </thead>
                    <tbody>
                        @foreach ($prescription->appointment->diagnoses as $key => $item)
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">{{ ($key+1) }}</span>
                            </td>
                            <td>
                                <span class="text-muted font-w600">{{ $item->name }}</span>
                            </td>
                            <td>
                                <span class="text-muted font-w600">{{ $item->instruction }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('patient::patient.getbeforeafter')
<div class="block d-none">
    <div class="block-header block-header-default">
        <h3 class="block-title">Before After</h3>
    </div>
    <div class="block-content">
        <div class="row items-push">
            <div class="col-md-12">
                @include('patient::prescription.partials.timeline')
            </div>
            <div class="col-md-12 d-none">
                <h6>Foto</h6>
                <div class="row items-push js-gallery img-fluid-100">
                    @if($prescription->timeline)
                        @if($prescription->timeline->files)
                            @foreach($prescription->timeline->files as $file)
                                <div class="col-md-6 col-lg-4 col-xl-3 animated fadeIn">
                                    <a class="img-link img-link-zoom-in img-lightbox" href="{{ url($file->document ?? '#') }}">
                                        <img class="img-fluid" src="{{ url($file->document ?? '#') }}" alt="">
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck">
    <div class="col-lg-12">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Inform Concern</h3>
            </div>
            <div class="block-content">
                <div class="row items-push">
                    <div class="col-md-12 text-center">
                        @if ($prescription->inform_concern !== null)
                            <a class="btn btn-info btn-sm" href="{{ URL::to('/'.$prescription->inform_concern) }}" target="__blank"><i class="fa fa-file mr-1"></i>Lihat Inform Concern</a>
                        @else
                            <h5 class="text-muted">Inform Concern Tidak Ditemukan</h5>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function() {
    Codebase.helpers('magnific-popup');

})
</script>