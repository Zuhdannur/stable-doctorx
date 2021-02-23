@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('product::menus.services-packages.title'))

@section('content')
    <!-- Detail -->
    <div class="block" id="my-block2">

        <div class="block-header block-header-default">
            <h3 class="block-title">Detail Paket Services</h3>
            <a href="{{ url()->previous() }}" class="btn-block-option">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
            
        <div class="block-content">
            <p>
                <h5>Nama Paket&nbsp;:&nbsp;{{ $servicesPackages->name}}</h5> 
                Deskripsi&nbsp;:&nbsp;{{ $servicesPackages->description}}
            </p>

            <!-- Table -->
            <div class="table-responsive push">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>Nama Service</th>
                            <th class="text-right" style="width: 120px;">Qty</th>
                            <th class="text-right" style="width: 200px;">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_price = 0;
                        @endphp
                        @foreach($servicesPackages->details as $key => $item)
                        <tr>                                    
                            <td class="text-center">{{ ($key+1) }}</td>
                            <td>
                                <p class="font-w600 mb-5">{{ $item->service['name'] }} </p>
                            </td>
                            <td class="text-right">{{ $item->qty }}</td>
                            <td class="text-right">{{ currency()->rupiah($item->service['price'], setting()->get('currency_symbol')) }}</td>
                            @php
                                $total_price = $total_price + ($item->qty * $item->service['price']);
                            @endphp
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="font-w600 text-right">Jumlah Harga</td>
                            <td class="text-right">{{ currency()->rupiah($total_price, setting()->get('currency_symbol')) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row mb-4">
                <div class="col text-right">
                    <a href="{{route('admin.product.services-packages.edit', ["ServicesPackage" => $servicesPackages ])}}" class="btn btn-primary btn-sm text-light"><i class="fa fa-edit"></i> Edit Data</a>
                </div>
            </div>
            <!-- END Table -->   
        </div>
    </div>
    <!-- END Transaksi -->
@endsection