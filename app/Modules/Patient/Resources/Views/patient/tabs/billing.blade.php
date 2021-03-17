<div class="block-content block-content-full">
    <ul class="list list-timeline list-timeline-modern pull-t">
        @foreach($invoice as $item)
            <li>
                <div class="list-timeline-time">{{ $item->date }}</div>
                <i class="list-timeline-icon fa fa fa-money bg-info"></i>
                <div class="list-timeline-content">
                    {{--                    <p class="font-w600">{{ @timezone()->convertToLocal($item->date, 'day') }}, {{ @timezone()->convertToLocal($item->date, 'date') }} | {{ @timezone()->convertToLocal($item->date, 'time') }}</p>--}}
                    <p>Nomor Invoce: {{ @$item->invoice_no }}<br>
                        Total Biaya: {{ currency()->rupiah(@$item->total_ammount, setting()->get('currency_symbol')) }}
                        <br>
                        Status :
                        @php
                        $url = 'admin/billing/'.$item->id.'/show';
                            $status = '';
                   switch ($item->status) {
                       case 0 :
                           $status = '<span class="badge badge-danger">'.config('billing.unpaid').'</span>' ;
                           break;
                       case 1 :
                           $status = '<span class="badge badge-success">'.config('billing.paid').'</span>' ;
                           break;
                       case 3 :
                           $status = '<span class="badge badge-warning">'.config('billing.partial_paid').'</span>' ;
                           break;
                   }
                        @endphp
                       {!!  @$status !!}
                        <br><br>
                        <a href="{{ url($url) }}" class="btn btn-sm btn-success"> Detail Invoice </a>
                    </p>
                </div>
            </li>
        @endforeach
    </ul>
</div>
