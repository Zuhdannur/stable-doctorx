@extends('backend.layouts.app')

@section('title', app_name().' | '.__('crm::menus.membership'))

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="block text-center" id="my-block">
            <div class="block-content block-content-full bg-image" style="background-image: url({{ asset('media/photos/photo28.jpg') }});">

                <img class="img-avatar img-avatar-thumb" src="{{ $logged_in_user->picture }}" alt="">
            </div>
            <div class="block-content block-content-full block-content-sm bg-flat-dark">
                <div class="font-w600 text-white mb-5">{{ $membership->patient->patient_name }}</div>
                <div class="font-size-sm text-white-op">{{ $membership->patient->patient_unique_id }}</div>
                <div class="font-size-sm text-white-op">{{ isset($membership->patient->membership->membership->name) ? $membership->patient->membership->membership->name." Membership" : '' }}</div>
            </div>
            <div class="block-content block-content-full">
            </div>
            <div class="block-content block-content-full block-content-sm bg-body-light">
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">No. Telepon</td>
                            <td class="font-size-sm text-muted text-right">{{ $membership->patient->phone_number }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">No. WhatsApp</td>
                            <td class="font-size-sm text-muted text-right">{{ $membership->patient->wa_number }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Email</td>
                            <td class="font-size-sm text-muted text-right">{{ $membership->patient->email }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Jenis Kelamin</td>
                            <td class="font-size-sm text-muted text-right">{{ $membership->patient->gender }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Tempat Lahir</td>
                            <td class="font-size-sm text-muted text-right">{{ $membership->patient->birth_place }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Tanggal Lahir</td>
                            <td class="font-size-sm text-muted text-right">{{ $membership->patient->dobstring }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Usia</td>
                            <td class="font-size-sm text-muted text-right">{{ $membership->patient->age }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Agama</td>
                            <td class="font-size-sm text-muted text-right">{{ isset($membership->patient->religion->name) ? $membership->patient->religion->name : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Gol. Darah</td>
                            <td class="font-size-sm text-muted text-right">{{ $membership->patient->blood->blood_name }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Kota</td>
                            <td class="font-size-sm text-muted text-right">{{ isset($membership->patient->city->name) ? ucwords(strtolower($membership->patient->city->name)) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Kecamatan</td>
                            <td class="font-size-sm text-muted text-right">{{ isset($membership->patient->district->name) ? ucwords(strtolower($membership->patient->district->name)) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Desa/Kelurahan</td>
                            <td class="font-size-sm text-muted text-right">{{ isset($membership->patient->village->name) ? ucwords(strtolower($membership->patient->village->name)) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Alamat</td>
                            <td class="font-size-sm text-muted text-right">{{ $membership->patient->address }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Sumber Info</td>
                            <td class="font-size-sm text-muted text-right">{{ $membership->patient->info->name }}</td>
                        </tr>
                        <tr>
                            <td class="font-w600 font-size-sm text-left">Media</td>
                            <td class="font-size-sm text-muted text-right">
                                @if(isset($membership->patient->media))
                                    {{ implode(", ", array_column($membership->patient->media->toArray(), "media_name")) }}
                                @endif
                            </td>
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
                            <i class="fa fa-tags text-primary fa-5x d-none d-xl-inline-block"></i>
                        </p>
                        <p class="font-w600 font-size-sm text-uppercase ">Jumlah Point</p>
                        <p style="font-size: 30pt">{{ $membership->total_point}}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-6 col-xl-6">
                <div class="block block-rounded text-center">
                    <div class="block-content">
                        <p class="mt-5 mb-10">
                            <i class="fa fa-exchange text-success fa-5x d-none d-xl-inline-block"></i>
                        </p>
                        <p class="font-w600 font-size-sm text-uppercase ">Jenis Membership</p>
                        <p style="font-size: 30pt">{{ isset($membership->ms_membership->name) ? $membership->ms_membership->name : '' }}</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="block block-content block-content-full" id="my-block-table">
                <h6>Riwayat Radeem Point</h6>
                <table id="table" class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination" width="100%">
                    <thead class="thead-light">
                        <tr>
                            <th>@lang('crm::labels.radeem.table.date')</th>
                            <th>@lang('crm::labels.radeem.table.items_code')</th>
                            <th>@lang('crm::labels.radeem.table.point_item')</th>
                            <th>@lang('crm::labels.radeem.table.ammount')</th>
                            <th>@lang('crm::labels.radeem.table.point_total')</th>
                            <th>@lang('crm::labels.radeem.table.nominal_total')</th>
                            <th>@lang('crm::labels.radeem.table.user_app')</th>
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
            ajax: '{!! route('admin.crm.membership.show', $membership->id) !!}',
            language: {
                url: "{!! URL::asset('js/plugins/datatables/i18n/'.str_replace('_', '-', app()->getLocale()).'.json') !!}"
            },
            columns: [
                {
                    data: 'created_at',
                    name: 'created_at',
                },
                {
                    data: 'item_code',
                    name: 'item_code',
                    className: 'font-w600'
                },
                {
                    data: 'point',
                    name: 'point',
                },
                {
                    data: 'ammount',
                    name: 'ammount',
                },
                {
                    data: 'point_total',
                    name: 'point_total',
                },
                {
                    data: 'nominal_total',
                    name: 'nominal_total',
                },
                {
                    data: 'user_app',
                    name: 'user_app',
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