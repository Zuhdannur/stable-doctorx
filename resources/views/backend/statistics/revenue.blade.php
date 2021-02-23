@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.dashboard.title'))

@section('content')
    @include('backend.statistics.partial.full-filters')
    <!-- Produk Statistics -->
    @widget('\App\Modules\Product\Widgets\Statistics\Terlaris', [ 'filter' => $filter, 'start_date' => $dateWidget['start_date'], 'end_date' => $dateWidget['end_date'] ]) 
    <!-- END product -->

    <!-- Pemasukan Statistics -->
    @widget('\App\Modules\Accounting\Widgets\PemasukanStatistics') 
    <!-- END pemasukan -->

    <!-- Purchase Statistics -->
    {{-- @widget('\App\Modules\Accounting\Widgets\PurchaseStatistics')  --}}
    <!-- END Purchase -->

    <!-- Biaya Statistics -->
    @widget('\App\Modules\Accounting\Widgets\BiayaStatistics') 
    <!-- END Biaya -->
@endsection