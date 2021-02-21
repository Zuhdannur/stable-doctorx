<div class="row"> 
        <div class="col-sm-6">
            <div class="block block-rounded block-bordered">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Konsultasi</h3>
                </div>
                <div class="block-content block-content-full">
                    @if(!$appointment->isEmpty())

                    @foreach($appointment->groupBy('floor_name') as $key => $appointment)
                    <div class="table-responsive">
                        <p class="p-10 bg-muted text-white">{{ $key }}</p>
                        <table class="table table-hover table-striped table-vcenter table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="8%">ID Pasien</th>
                                    <th width="18%">Nama</th>
                                    <th width="8%">Usia</th>
                                    <th width="18%">Dokter</th>
                                    <th width="13%">Ruangan</th>
                                    <th width="8%">Lantai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($appointment))
                                    @foreach($appointment as $key => $data)
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
                                            <strong>{{ $data->staff->user->full_name }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $data->room->name }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $data->room->group->floor->name }}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr class="table-danger text-center">
                                    <td colspan="10">
                                        <br /> 
                                        <br />
                                        <img src='{{ url("media/addnewitem.svg") }}' width='150'><br /><br />
                                        <i class='fa fa-list-ol'></i> No data available in table 
                                        <br />
                                        <br />
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <br>
                    </div>
                    @endforeach

                    @else
                    <table class="table table-hover table-striped table-vcenter table-bordered mb-0">
                        <tr class="table-danger text-center">
                            <td colspan="10">
                                <br />
                                <br />
                                <img src='{{ url("media/addnewitem.svg") }}' width='150'>
                                <br />
                                <br />
                                <i class='fa fa-list-ol'></i> No data available in table
                                <br />
                                <br />
                            </td>
                        </tr>
                    </table>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="block block-rounded block-bordered">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Treatment</h3>
                </div>
                <div class="block-content block-content-full">
                    @if(!$treatment->isEmpty())

                    @foreach($treatment->groupBy('floor_name') as $key => $treatment)
                    <p class="p-10 bg-muted text-white">{{ $key }}</p>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-vcenter table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="8%">ID Pasien</th>
                                    <th width="18%">Nama</th>
                                    <th width="8%">Usia</th>
                                    <th width="18%">Dokter</th>
                                    <th width="13%">Ruangan</th>
                                    <th width="8%">Lantai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($treatment)
                                    @foreach($treatment as $key => $data)
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
                                            <strong>{{ $data->staff->user->full_name ?? null }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $data->room->name }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $data->room->group->floor->name }}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr class="table-danger text-center">
                                    <td colspan="10">
                                        <br /> 
                                        <br />
                                        <img src='{{ url("media/addnewitem.svg") }}' width='150'><br /><br />
                                        <i class='fa fa-list-ol'></i> No data available in table 
                                        <br />
                                        <br />
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <br>
                    </div>
                    @endforeach

                    @else
                    <table class="table table-hover table-striped table-vcenter table-bordered mb-0">
                        <tr class="table-danger text-center">
                            <td colspan="10">
                                <br />
                                <br />
                                <img src='{{ url("media/addnewitem.svg") }}' width='150'>
                                <br />
                                <br />
                                <i class='fa fa-list-ol'></i> No data available in table
                                <br />
                                <br />
                            </td>
                        </tr>
                    </table>
                    @endif
                </div>
            </div>
        </div>
</div>