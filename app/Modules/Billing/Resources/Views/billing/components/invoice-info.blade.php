<div class="row px-5 pt-5">
    <div class="col-3 contact-details">
        <h5>Pasien: {{ $billing->patient->patient_name }}</h5>
        <h6><em>ID: {{ $billing->patient->patient_unique_id }}</em></h6>
        <h6><em>Dokter: {{ isset($billing->appointmentInvoice->appointment->staff->user->full_name) ? $billing->appointmentInvoice->appointment->staff->user->full_name : '-' }}</em></h6>
        <h6><em>Terapis: {{ isset($billing->treatmentInvoice->treatment->staff->user->full_name) ? $billing->treatmentInvoice->treatment->staff->user->full_name : '-' }}</em></h6>
        <p><em>Alamat: {{ isset($billing->patient->address) ? ucwords(strtolower($billing->patient->address)) : '-' }}, {{ isset($billing->patient->city->name) ? ucwords(strtolower($billing->patient->city->name)) : '-' }} {{ $billing->patient->zip_code }}</em></p>
    </div>
    <div class="col-1 offset-2 logo">
        <img width="125px" height="125px" src="{{ asset(setting()->get('logo_square')) }}">
    </div>
    <div class="invoice-details col-3 offset-3 text-right">
        <h6>Invoice No. #{{ $billing->invoice_no }}</h6>
        <h6>Dibuat Pada: {{ $billing->created_at }}</h6>
        <h6>Jatuh Tempo: {{ $billing->date }}</h6>
    </div>
</div>