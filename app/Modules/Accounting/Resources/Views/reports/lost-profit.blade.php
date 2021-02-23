@extends('backend.layouts.app')

@section('title', app_name().' | '.__('accounting::menus.reports.lost-profit'))

@section('content')
    @include('accounting::reports.partial.range-date')
    
    @isset($report)
        <div class="block block-themed font-size-sm">
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-md-12">
                        <table id="tableStudent" class="js-table-sections table table-sm table-striped table-bordered table-hover js-table-sections-enabled table-vcenter" width="100%">
                            <thead class="thead-light">
                                <tr>
                                    <th colspan="3" class="text-center">Laporan Laba Rugi</th>
                                </tr>
                            </thead>
                            @php
                                $type = 'init';
                                $parent = 'init';
                                $category = 'init';
                                $category_parent = '';
                                $category_value = 0;
                                $type_total = 0;
                                $type_name = '';
                                $sum_report = 0;
                            @endphp
                            @foreach ($report as $key => $item)
                                
                                @if ($type != $item['type'] && $key > 0)
                                    <tr class="bg-secondary text-white">
                                        <td colspan="2" style="padding-left:60px" class='text-right'>Total {{ $category_parent }}</td>
                                        <td class="text-right">{{ currency()->rupiah($category_value, setting()->get('currency_symbol')) }}</td>
                                    </tr>
                                    @php
                                        $category_value = 0;
                                    @endphp
                                    <tr class="bg-secondary text-white">
                                        <td colspan="2" style="padding-left:60px" class='text-center'>Total {{ $type_name }}</td>
                                        <td class="text-right">{{ currency()->rupiah($type_total, setting()->get('currency_symbol')) }}</td>
                                    </tr>
                                    @php
                                        $type_total = 0;
                                    @endphp
                                @endif

                                {{-- looping for type account --}}
                                @if ($type != $item['type'])
                                    <tr>
                                        <td colspan="3" class="bg-primary text-light">{{ config('finance_account.type_list.'.$item['type']) }}&nbsp;{{$item['type'] == 5 ? 'Dan Biaya' : ''}}&nbsp;Klinik Periode Ini</td>
                                    </tr>
                                @endif
                                
                                {{-- total for parent category --}}
                                @if ($parent != $item['category_parent_id'] && $key > 0 && $type == $item['type'])
                                    <tr class="bg-secondary text-white">
                                        <td colspan="2" style="padding-left:60px" class='text-right'>Total {{ $category_parent }}</td>
                                        <td class="text-right">{{ currency()->rupiah($category_value, setting()->get('currency_symbol')) }}</td>
                                    </tr>
                                    @php
                                        $category_value = 0;
                                    @endphp
                                @endif

                                {{-- looping for category_account parent--}}
                                @if ($parent != $item['category_parent_id'])
                                    <tr>
                                        <td colspan="3" style="padding-left:30px">{{ $item['category_parent_code'].' '.$item['category_parent'] }}</td>
                                    </tr>
                                @endif


                                {{-- looping category  --}}
                                @if ($category != $item['category_id'])
                                    <tr>
                                        <td colspan="3" style="padding-left:60px">{{ $item['category_code'].' '.$item['category'] }}</td>
                                    </tr>
                                @endif

                                {{-- looping account --}}
                                <tr>
                                    <td style="padding-left:90px">{{ $item['account_code']}}</td>
                                    <td>{{ $item['account_name']}}</td>
                                    <td class="text-right">{{ currency()->rupiah($item['value'], setting()->get('currency_symbol')) }}</td>
                                </tr>

                                @php
                                    $type = $item['type'];
                                    $parent = $item['category_parent_id'];
                                    $category = $item['category_id'];
                                    $category_parent = $item['category_parent'];
                                    $category_value = $category_value + $item['value'];
                                    $type_total = $type_total + $item['value'];
                                    if($item['type'] == 4 ){
                                        $sum_report = $sum_report + $item['value'];
                                    }
                                    $type_name = config('finance_account.type_list.'.$item['type']);
                                @endphp
                            @endforeach
                                <tr class="bg-secondary text-white">
                                    <td colspan="2" style="padding-left:60px" class='text-right'>Total {{ $category_parent }}</td>
                                    <td class="text-right">{{ currency()->rupiah($category_value, setting()->get('currency_symbol')) }}</td>
                                </tr>
                                <tr class="bg-secondary text-white">
                                    <td colspan="2" style="padding-left:60px" class='text-center'>Total Beban Dan Biaya Perusahaan</td>
                                    <td class="text-right">{{ currency()->rupiah($type_total, setting()->get('currency_symbol')) }}</td>
                                </tr>
                                <tr class="bg-secondary text-white">
                                    <td colspan="2" style="padding-left:60px" class='text-center'>Pendapatan Bersih Periode Ini</td>
                                    <td class="text-right">{{ currency()->rupiah($sum_report - $type_total, setting()->get('currency_symbol')) }}</td>
                                </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endisset
@endsection