@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.dashboard.title'))

@section('content')

{{-- @include('accounting::reports.partial.range-date') --}}
<div class="block font-size-sm" id="my-block">
    <form class="form-vertical" method="post" action="" id="myForm" name="myForm" autocomplete="off" data-parsley-validate>
        <div class="block-content">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="filters" class="forom-label">Filter</label>
                            <select name="filters" id="filters" class="form-control" width="100%">
                                <option value="1">All</option>
                                <option value="2">Hari Ini</option>
                                <option value="3">Kemarin</option>
                                <option value="4">Kemarin</option>
                                <option value="5">Pekan Ini</option>
                                <option value="6">Pekan Lalu</option>
                                <option value="7">Pekan Lalu</option>
                                <option value="8">Bulan Ini</option>
                                <option value="9">Bulan Lalu</option>
                                <option value="10">Tahun Ini</option>
                                <option value="11">Tahun Lalu</option>
                                <option value="12">Rentang Tanggal</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            @method('POST')
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row justify-content-center">
                        <div class="col-6 text-center">
                            <label for="startdate">Tanggal</label>
                            <div class="input-daterange input-group" data-date-format="{{ setting()->get('date_format_js') }}" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                <input type="text" class="form-control datepicker" id="date_1" name="date_1" placeholder="Dari" data-week-start="1" data-autoclose="true" data-today-highlight="true" autocomplete="off" value="{{ isset($date['date_1']) ? $date['date_1'] : null }}">
                                <div class="input-group-prepend input-group-append">
                                    <span class="input-group-text font-w600">s/d</span>
                                </div>
                                <input type="text" class="form-control datepicker" id="date_2" name="date_2" placeholder="Hingga" data-week-start="1" data-autoclose="true" data-today-highlight="true" autocomplete="off" value="{{ isset($date['date_2']) ? $date['date_2'] : null }}">
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

<script>
    jQuery(function(){ 
        Codebase.helpers(['datepicker', 'notify', 'select2']);

        $(document).on("focusout", ".datepicker", function() {
            $(this).prop('readonly', false);
        });

        $(document).on("focusin", ".datepicker", function() {
            $(this).prop('readonly', true);
        });
    });
</script>

{{-- peak time widget --}}
@widget('\App\Widgets\PeakTimeWidgets', ['type' => 5,'start_date' => $dateWidget['start_date'], 'end_date' => $dateWidget['end_date']])
{{-- peak time widget --}}

{{-- appointment peak time --}}
@widget('\App\Modules\Patient\Widgets\PatientPeakTime', [ 'start_date' => $dateWidget['start_date'], 'end_date' => $dateWidget['end_date'] ]) 
{{-- End OF appointment peak time --}}

<!-- Produk Statistics -->
@widget('\App\Modules\Product\Widgets\Statistics', [ 'start_date' => $dateWidget['start_date'], 'end_date' => $dateWidget['end_date'] ]) 
<!-- END Patients -->

<!-- Pasien Statistics -->
@widget('\App\Modules\Patient\Widgets\Statistics', [ 'start_date' => $dateWidget['start_date'], 'end_date' => $dateWidget['end_date'] ]) 
<!-- END Patients -->

<script type="text/javascript">
jQuery(function(){ 
    Codebase.layout('sidebar_mini_on');
});
</script>
@endsection
