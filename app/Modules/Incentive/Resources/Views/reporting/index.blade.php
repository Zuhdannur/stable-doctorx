@extends('backend.layouts.app')

@section('title', app_name() . ' | Laporan Insentif')

@section('content')
<style type="text/css">
tfoot {
    display: table-header-group;
}
.toolbar {
    float:left;
}
</style>
<div class="block font-size-sm" id="my-block">
    <form class="form-vertical" method="post" action="{{ route('admin.reporting.incentive.staff') }}" id="myForm" name="myForm" autocomplete="off" data-parsley-validate>
        <div class="block-header block-header-default">
            <h3 class="block-title">Laporan Komisi</h3>
        </div>
        <div class="block-content block-content-full">
            @method('POST')
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="register4-firstname">Employee</label>
                            <select class="js-select2 form-control" id="staff_id" name="staff_id" data-placeholder="Pilih" data-parsley-required="true">
                                <option></option><!-- js-select2  -->

                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->id }}" {{ ( $staff->id == old('staff_id')) ? 'selected' : '' }}> {{ $staff->user->full_name }} - {{ $staff->department->department_name }} - {{ $staff->designation->name }} </option>
                                @endforeach    
                            </select>
                        </div>
                        
                        <div class="col-6">
                            <label for="startdate">Tanggal</label>
                            <div class="input-daterange input-group" data-date-format="{{ setting()->get('date_format_js') }}" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                <input type="text" class="form-control" id="start_date" name="start_date" placeholder="Dari" data-week-start="1" data-autoclose="true" data-today-highlight="true" autocomplete="off" value="{{ isset($input['start_date']) ? $input['start_date'] : null }}">
                                <div class="input-group-prepend input-group-append">
                                    <span class="input-group-text font-w600">s/d</span>
                                </div>
                                <input type="text" class="form-control" id="end_date" name="end_date" placeholder="Hingga" data-week-start="1" data-autoclose="true" data-today-highlight="true" autocomplete="off" value="{{ isset($input['end_date']) ? $input['end_date'] : null }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block-content block-content-full block-content-sm bg-body-light text-center">
            <button type="submit" class="btn btn-sm btn-success">
                <i class="fa fa-search"></i> Tampilkan
            </button>
        </div>
    </form>
</div>

@if(!empty($data))
<div class="block block-themed font-size-sm">
        <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-md-12">
                        <table id="tableStudent" class="js-table-sections table table-sm table-striped table-bordered table-hover js-table-sections-enabled table-vcenter" width="100%">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="8%">Tanggal</th>
                                    <th>Pasien</th>
                                    <th>Diagnosa</th>
                                    <th>Tindakan</th>
                                    <th>Obat</th>
                                    <th>Type</th>
                                    <th width="15%" class="text-right">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grd_total = 0;
                                @endphp
                                @foreach($data as $key => $item)
                                @php
                                    if ($item->incenvite_value && ($item->value_type !== 'point')) {
                                        $grd_total = $grd_total + $item->incenvite_value;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ ($key + 1) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->date)->format('j F Y') }}</td>
                                    <td class="font-w600 text-muted">{{ $item->invoice->patient->patient_name }}</td>
                                    <td class="font-w600 text-muted">
                                        @if(isset($item->invoice->appointmentInvoice))
                                            {{ implode(", ", array_column($item->invoice->appointmentInvoice->appointment->diagnoses->toArray(), "name")) }}
                                        @endif
                                    </td>
                                    <td>{{ isset($item->service->service_name) ? $item->service->service_name : null }}</td>
                                    <td>{{ isset($item->product->product_name) ? $item->product->product_name : null }}</td>
                                    <td class="text text-right text-muted">{{ isset($item->value_type) ? $item->value_type : null }}</td>
                                    <td class="text text-right text-black" data-toggle="tooltip" data-placement="top" data-original-title="{{ ucwords(\Terbilang::make($item->incenvite_value)) }}">{{ currency()->rupiah($item->incenvite_value) }}</td>
                                </tr>
                                @endforeach
                                <tfoot>
                                    <tr>
                                        <th colspan="7" style="text-align:right">Total:</th>
                                        <th class="text-right">{{ currency()->rupiah($grd_total) }}</th>
                                    </tr>
                                </tfoot>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
</div>
@endif
<script>
jQuery(function(){ 
    Codebase.helpers(['datepicker', 'notify', 'select2']);

    $('#staff_id').val("{{ isset($input['staff_id']) ? $input['staff_id'] : 0 }}").trigger("change");
});
</script>
@endsection