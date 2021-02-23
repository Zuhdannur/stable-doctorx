@extends('backend.layouts.app')

@section('title', app_name().' | '.__('product::menus.supplier.title'))

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="block text-center" id="my-block">
            {{-- <div class="block-content block-content-full bg-image" style="background-image: url({{ asset('media/photos/photo28.jpg') }});">

                <img class="img-avatar img-avatar-thumb" src="{{ $logged_in_user->picture }}" alt="">
            </div> --}}
            <div class="block-content block-content-full block-content-sm bg-flat-dark">
                <div class="font-w600 text-white mb-5">{{ $supplier->supplier_name }}</div>
                <div class="font-size-sm text-white-op">{{ $supplier->supplier_code }}</div>
            </div>
            <div class="block-content block-content-full">
                <a class="btn btn-sm btn-rounded btn-alt-success" href="{{ route('admin.product.supplier.edit', $supplier) }}">
                    <i class="fa fa-edit mr-5"></i>Edit
                </a>
                <a href="{{ route('admin.product.supplier.delete', $supplier ) }}" data-method="delete" data-trans-button-cancel="{{ __('buttons.general.cancel') }}" data-trans-button-confirm="{{ __('buttons.general.crud.delete') }}" data-trans-title="{{ __('strings.backend.general.are_you_sure') }}" class="btn btn-sm btn-rounded btn-alt-danger" data-toggle="tooltip" data-placement="top" title="{{ __('buttons.general.crud.delete') }}">
                    <i class="fa fa-trash mr-5"></i>Hapus
                </a>
            </div>
            <div class="block-content block-content-full block-content-sm bg-body-light">
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">No. Telepon</td>
                            <td class="font-size-sm text-muted text-right">{{ $supplier->phone_number }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Email</td>
                            <td class="font-size-sm text-muted text-right">{{ $supplier->email }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Jenis Kelamin</td>
                            <td class="font-size-sm text-muted text-right">{{ $supplier->gender }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Tempat Lahir</td>
                            <td class="font-size-sm text-muted text-right">{{ $supplier->birth_place }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Tanggal Lahir</td>
                            <td class="font-size-sm text-muted text-right">{{ $supplier->dobstring }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Usia</td>
                            <td class="font-size-sm text-muted text-right">{{ $supplier->age }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Kota</td>
                            <td class="font-size-sm text-muted text-right">{{ isset($supplier->city->name) ? ucwords(strtolower($supplier->city->name)) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Kecamatan</td>
                            <td class="font-size-sm text-muted text-right">{{ isset($supplier->district->name) ? ucwords(strtolower($supplier->district->name)) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Desa/Kelurahan</td>
                            <td class="font-size-sm text-muted text-right">{{ isset($supplier->village->name) ? ucwords(strtolower($supplier->village->name)) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Alamat</td>
                            <td class="font-size-sm text-muted text-right">{{ $supplier->company_address }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-6 col-md-6 col-xl-6">
                <div class="block block-rounded text-center">
                    <div class="block-content">
                        <p class="mt-5 mb-10">
                            <i class="fa fa-tags text-danger fa-5x d-none d-xl-inline-block"></i>
                        </p>
                        <p class="font-w600 font-size-sm text-uppercase ">Total Sisa Tagihan</p>
                        <p style="font-size: 30pt">{{ $tagihan }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-6 col-xl-6">
                <div class="block block-rounded text-center">
                    <div class="block-content">
                        <p class="mt-5 mb-10">
                            <i class="fa fa-credit-card text-success fa-5x d-none d-xl-inline-block"></i>
                        </p>
                        <p class="font-w600 font-size-sm text-uppercase ">Total Pembelian Dalam 30 Hari Terakhir</p>
                        <p style="font-size: 30pt">{{ $purchase30Days }}</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="clear-fix"></div>
        <div class="block" id="my-block-table">
            <div class="block block-content block-content-full">
                <h6>Riwayat Transaksi Pembelian</h6>
                <table id="table" class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination" width="100%">
                    <thead class="thead-light">
                        <tr>
                            <th>@lang('product::labels.supplier.table-detail.date')</th>
                            <th>@lang('product::labels.supplier.table-detail.trx_code')</th>
                            <th>@lang('product::labels.supplier.table-detail.remain_payment')</th>
                            <th>@lang('product::labels.supplier.table-detail.total')</th>
                        </tr>
                    </thead>
                       
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(function(){ 
        var dt = $('#table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ordering: false,
            pageLength: 10,
            ajax: '{!! route('admin.product.supplier.show', $supplier->id ) !!}',
            language: {
                url: "{!! URL::asset('js/plugins/datatables/i18n/'.str_replace('_', '-', app()->getLocale()).'.json') !!}"
            },
            columns: [
                {
                    data: 'trx_date',
                    name: 'trx_date',
                },
                {
                    data: 'transaction_code',
                    name: 'transaction_code',
                    className: 'font-w600'
                },
                {
                    data: 'purchase.remain_payment',
                    name: 'purchase.remain_payment',
                },
                {
                    data: 'purchase.total',
                    name: 'purchase.total',
                },
            ]
        }).on('processing.dt', function (e, settings, processing) {
            if (processing) {
                Codebase.blocks('#my-block-table', 'state_loading');
            } else {
                Codebase.blocks('#my-block-table', 'state_normal');
            }
        }).on('draw.dt', function () {
            $('[data-toggle="tooltip"]').tooltip();
            addDeleteForms();
        } );
    });
</script>
@endsection