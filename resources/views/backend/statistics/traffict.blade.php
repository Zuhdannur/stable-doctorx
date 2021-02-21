@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.dashboard.title'))

@section('content')

    @include('backend.statistics.partial.full-filters')

    {{-- appointment peak time --}}
    @widget('\App\Modules\Patient\Widgets\PatientPeakTime', [ 'filter' => $filter, 'start_date' => $dateWidget['start_date'], 'end_date' => $dateWidget['end_date'] ]) 
    {{-- End OF appointment peak time --}}

    {{-- peak time widget --}}
    {{-- @widget('\App\Widgets\PeakTimeWidgets', ['type' => 5, 'filter' => $filter, 'start_date' => $dateWidget['start_date'], 'end_date' => $dateWidget['end_date']]) --}}
    {{-- peak time widget --}}

@endsection