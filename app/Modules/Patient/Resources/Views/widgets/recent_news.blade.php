<style>
    .block-recent {
        min-height: 200px !important;
    }
</style>
<div class="row">
    <div class="col-md-2">
        <a class="block block-rounded block-bordered block-link-shadow" href="{{ route('admin.patient.index') }}">
            <div class="block-content block-content-full block-recent">
                <div class="py-20 text-center">
                    <div class="mb-10">
                        <i class="fa fa-user-circle-o fa-3x text-corporate"></i>
                    </div>
                    {{-- <div class="font-size-h4 font-w600">{{ $patient->total_patient }} Pasien</div> --}}
                    {{-- <div class="font-size-h4 font-w600">{{ $patient->total_patient_today }} pasien baru hari ini!</div> --}}
                    {{-- <div class="text-muted">{{ $patient->total_patient_today }} pasien baru hari ini!</div>  --}}
                    <div class="font-size-h4 font-w600">{{ $patient->pasienBaru }} kunjungan pasien baru</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-2">
        <a class="block block-rounded block-bordered block-link-shadow" href="{{ route('admin.patient.index') }}">
            <div class="block-content block-content-full block-recent">
                <div class="py-20 text-center">
                    <div class="mb-10">
                        <i class="fa fa-user-circle-o fa-3x text-corporate"></i>
                    </div>
                    {{-- <div class="font-size-h4 font-w600">{{ $patient->total_patient }} Pasien</div> --}}
                    {{-- <div class="font-size-h4 font-w600">{{ $patient->total_patient_today }} pasien baru hari ini!</div> --}}
                    {{-- <div class="text-muted">{{ $patient->total_patient_today }} pasien baru hari ini!</div>  --}}
                    <div class="font-size-h4 font-w600">{{ $patient->pasienLama }} kunjungan pasien lama</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a class="block block-rounded block-bordered block-link-shadow" href="{{ route('admin.patient.appointment.index') }}">
            <div class="block-content block-content-full block-recent">
                <div class="py-20 text-center">
                    <div class="mb-10">
                        <i class="fa fa-calendar fa-3x text-elegance"></i>
                    </div>
                    <!-- <div class="font-size-h4 font-w600">{{ $appointment->total_appointment }} Konsultasi</div> -->
                    <div class="font-size-h4 font-w600">{{ $appointment->total_appointment_today }} Konsultasi untuk hari ini!</div>
                    <!-- <div class="text-muted">{{ $appointment->total_appointment_today }} dijadwalkan untuk hari ini!</div> -->
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a class="block block-rounded block-bordered block-link-shadow" href="{{ route('admin.patient.treatment.index') }}">
            <div class="block-content block-content-full block-recent">
                <div class="py-20 text-center">
                    <div class="mb-10">
                        <i class="si si-briefcase fa-3x text-primary"></i>
                    </div>
                    <!-- <div class="font-size-h4 font-w600">{{ $treatment->total_treatment }} Treatment</div> -->
                    <div class="font-size-h4 font-w600">{{ $treatment->total_treatment_today }} Treatment untuk hari ini.</div>
                    <!-- <div class="text-muted">{{ $treatment->total_treatment_today }} dijadwalkan untuk hari ini.</div> -->
                </div>
            </div>
        </a>
    </div>
</div>
