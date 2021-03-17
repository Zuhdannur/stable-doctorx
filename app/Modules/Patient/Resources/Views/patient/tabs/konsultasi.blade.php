<div class="block-content block-content-full">
    <ul class="list list-timeline list-timeline-modern pull-t">
        @foreach($patient->appointment as $key => $appointment)
        <li>
            <div class="list-timeline-time">{{ $appointment->appointment_no }}</div>
            <i class="list-timeline-icon fa fa fa-stethoscope bg-info"></i>
            <div class="list-timeline-content">
                <p class="font-w600">{{ timezone()->convertToLocal($appointment->date, 'day') }}, {{ timezone()->convertToLocal($appointment->date, 'date') }} | {{ timezone()->convertToLocal($appointment->date, 'time') }}</p>
                <p>Dokter: {{ $appointment->staff->user->full_name }}<br>
                Ruangan: {{ isset($appointment->room->name) ? $appointment->room->name : '-' }}<br>
                Status : <span class="badge badge-{{ $appointment->status->class_style }}">{{ $appointment->status->name }}</span> <br>
                Keluhan : {{ $appointment->prescription->complaint }} <br>
                    Riwayat Perawatan dan Alergi : {{ $appointment->prescription->treatment_history }}
                 </p>
                @if(isset($appointment->prescription))
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-xl-8">
                        <table class="table table-borderless table-striped">
                            <thead>
                                <th>#</th>
                                <th>Resep</th>
                                <th>Instruksi</th>
                            </thead>
                            <tbody>
                                @foreach ($appointment->prescription->detail as $key => $item)
                                <tr>
                                    <td class="text-left">
                                        <span class="text-muted">{{ ($key+1) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted font-w600">{{ $item->name }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted font-w600">{{ $item->instruction }}</span>
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
