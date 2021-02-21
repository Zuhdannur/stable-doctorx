<table class="table table-sm">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px;">#</th>
            <th>AP ID</th>
            <th>Tanggal</th>
            <th>Oleh</th>
            <th>Room/Area</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patient->appointment as $key => $appointment)
        <tr>
            <th class="text-center" scope="row">{{ ($key+1) }}</th>
            <td>
				<span class="font-w600 mb-10">{{ $appointment->appointment_no }}</span>
            </td>
            <td>
				<p class="font-w600 mb-10">{{ timezone()->convertToLocal($appointment->date, 'date') }}</p>
                <p class="font-size-sm text-muted mb-0">{{ timezone()->convertToLocal($appointment->date, 'day') }}, {{ timezone()->convertToLocal($appointment->date, 'time') }}</p>
            </td>
            <td>
				<span class="font-w600 mb-10">{{ $appointment->staff->user->full_name }}</span>
            </td>
            <td>
				<span class="font-w600 mb-10">{{ $appointment->room->name }}</span>
            </td>
            <td>
            	<span class="badge badge-{{ $appointment->status->class_style }}">{{ $appointment->status->name }}</span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>