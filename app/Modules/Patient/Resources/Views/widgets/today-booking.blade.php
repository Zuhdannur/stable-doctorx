<div class="block" id="my-block2">
    <div class="block-content">
        <div class="row"> 
            <div class="col-sm-12">
                <div class="block block-rounded block-bordered">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Data Booking Konsultasi</h3>
                    </div>
                    <div class="block-content block-content-full">
                        @foreach($bookAppointment->groupBy('floor_name') as $key => $bookAppointment)
                        <div class="table-responsive">
                            <p class="p-10 bg-muted text-white">{{ $key }}</p>
                            <table class="table table-hover table-striped table-vcenter table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="8%">ID Pasien</th>
                                        <th width="18%">Nama</th>
                                        <th width="8%">Usia</th>
                                        <th width="8%">Pasien Lama?</th>
                                        <th width="18%">Dokter</th>
                                        <th width="13%">Ruangan</th>
                                        <th width="8%">Lantai</th>
                                        <th>Jam</th>
                                        <th class="text-right"></th>
                                        <th class="text-right"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach($bookAppointment as $key => $data)
                                        <tr class="table-{{ $data->patient->flag->color_code }}">
                                            <td>
                                                <a class="font-w600" href="{{ route('admin.patient.prescription.create', $data->appointment_no) }}">{{ $data->seq }}</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.patient.show', $data->patient->id) }}">{{ $data->patient->patient_unique_id }}</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.patient.show', $data->patient->id) }}">{{ $data->patient->patient_name }}</a>
                                            </td>
                                            <td>
                                                <strong>{{ $data->patient->age }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $data->patient->old_patient }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $data->staff->user->full_name }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $data->room->name }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $data->room->group->floor->name }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->date)->format('H:i') }}</strong>
                                            </td>
                                            <td class="text-right">
                                                <p class="font-size-sm text-muted mb-0">{{ $data->created_at->diffForHumans() }}</p>
                                            </td>
                                            <td class="text-right">
                                                <p class="font-size-sm text-muted mb-0"><button class="font-w600 btn btn-sm btn-success mb-5 mb-5" onclick="showModalBooking('{{$data->id.'##1'}}')">Update Status</button></p>
                                            </td>
                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>
                            <br>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="block" id="my-block2">
    <div class="block-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="block block-rounded block-bordered">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Data Booking Treatment</h3>
                    </div>
                    <div class="block-content block-content-full">
                        @foreach($bookTreatment->groupBy('floor_name') as $key => $bookTreatment)
                        <p class="p-10 bg-muted text-white">{{ $key }}</p>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-vcenter mb-0">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="8%">ID Pasien</th>
                                        <th width="18%">Nama</th>
                                        <th width="8%">Usia</th>
                                        <th width="8%">Pasien Lama?</th>
                                        <th width="18%">Dokter</th>
                                        <th width="13%">Ruangan</th>
                                        <th width="8%">Lantai</th>
                                        <th class="text-right"></th>
                                        <th class="text-right"></th>
                                        <th class="text-right"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach($bookTreatment as $key => $data)
                                        <tr class="table-{{ $data->patient->flag->color_code }}">
                                            <td>
                                                <a class="font-w600" href="{{ route('admin.patient.treatment.show', $data->id) }}">{{ $data->seq }}</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.patient.show', $data->patient->id) }}">{{ $data->patient->patient_unique_id }}</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.patient.show', $data->patient->id) }}">{{ $data->patient->patient_name }}</a>
                                            </td>
                                            <td>
                                                <strong>{{ $data->patient->age }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $data->patient->old_patient }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $data->staff->user->full_name ?? null }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $data->room->name }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $data->room->group->floor->name }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->date)->format('H:i') }}</strong>
                                            </td>
                                            <td class="text-right">
                                                <p class="font-size-sm text-muted mb-0">{{ $data->created_at->diffForHumans() }}</p>
                                            </td>
                                            <td class="text-right">
                                                <p class="font-size-sm text-muted mb-0"><button class="font-w600 btn btn-sm btn-success mb-5 mb-5" onclick="showModalBooking('{{$data->id.'##2'}}')">Update Status</button></p>
                                            </td>
                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>
                            <br>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>