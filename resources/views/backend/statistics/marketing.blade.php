@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.dashboard.title'))

@section('content')
    @include('backend.statistics.partial.full-filters')

    <!-- Pasien Statistics -->
    @widget('\App\Modules\Patient\Widgets\Statistics\Media', [ 'filter' => $filter, 'start_date' => $dateWidget['start_date'], 'end_date' => $dateWidget['end_date'] ]) 
    <!-- END Patients -->
@endsection