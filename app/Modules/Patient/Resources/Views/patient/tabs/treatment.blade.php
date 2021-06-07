<div class="block-content block-content-full">
    <ul class="list list-timeline list-timeline-modern pull-t">
        @foreach($patient->treatment as $key => $treatment)
        <li>
            <div class="list-timeline-time">{{ @$treatment->treatment_no }}</div>
            <i class="list-timeline-icon fa fa-medkit bg-success"></i>
            <div class="list-timeline-content">
                <p class="font-w600">{{ timezone()->convertToLocal(@$treatment->date, 'day') }}, {{ timezone()->convertToLocal(@$treatment->date, 'date') }} | {{ timezone()->convertToLocal(@$treatment->date, 'time') }}</p>
                <p>Terapis/Dokter: {{ @$treatment->staff->user->full_name }}<br>
                Ruangan: {{ isset($treatment->room->name) ? @$treatment->room->name : '-' }}<br>
                Status : <span class="badge badge-{{ @$treatment->status->class_style }}">{{ @$treatment->status->name }}</span>
                 </p>
                @php
                    $detail = @$treatment->getDetail();
                    // dd(sizeof($detail));
                @endphp
                @if(sizeof($detail) > 0 )
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-xl-8">
                            <table class="table table-borderless table-striped">
                                <thead>
                                    <th>#</th>
                                    <th>Tindakan</th>
                                    <th>Deskripsi</th>
                                </thead>
                                <tbody>
                                    @foreach ($detail as $key => $item)
                                    <tr>
                                        <td class="text-left">
                                            <span class="text-muted">{{ ($key+1) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted font-w600">{{ @$item->name }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted font-w600">{{ @$item->description }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </li>
        @endforeach
    </ul>
</div>
