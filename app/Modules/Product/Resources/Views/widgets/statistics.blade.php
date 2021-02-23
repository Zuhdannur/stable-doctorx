@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('product::labels.category.management'))

@section('content')
<div class="row"> 
    <div class="col-sm-12">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title">Statistik Produk Terjual</h3>
                <div class="block-options">
                	<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
            	</div>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    {!! $chart->container() !!}
                </div>
            </div>
        </div>
    </div>
</div>

{!! $chart->script() !!}
@endsection