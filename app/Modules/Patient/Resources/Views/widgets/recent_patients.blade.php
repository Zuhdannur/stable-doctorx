{{--    <div class="block block-rounded block-bordered">--}}
{{--        <div class="block-header block-header-default">--}}
{{--            <h3 class="block-title">Pendaftaran Pasien Terakhir</h3>--}}
{{--            <div class="block-options">--}}
{{--                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">--}}
{{--                    <i class="si si-refresh"></i>--}}
{{--                </button>--}}
{{--                <button type="button" class="btn-block-option">--}}
{{--                    <i class="si si-wrench"></i>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="block-content block-content-full">--}}
{{--            <div class="table-responsive">--}}
{{--                <table class="table table-borderless table-hover table-striped table-vcenter mb-0">--}}
{{--                    <thead>--}}
{{--                        <tr>--}}
{{--                            <th style="width: 100px;">ID</th>--}}
{{--                            <th>Nama</th>--}}
{{--                            <th>Jen.Kel.</th>--}}
{{--                            <th>Tgl.Lahir</th>--}}
{{--                            <th>Usia</th>--}}
{{--                            <th></th>--}}
{{--                            <th class="text-right">Ditambahkan</th>--}}
{{--                        </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                        @foreach($patients as $patient)--}}
{{--                        <tr>--}}
{{--                            <td>--}}
{{--                                <a class="font-w600" href="{{ route('admin.patient.show', $patient->id) }}">{{ $patient->patient_unique_id }}</a>--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                <strong>{{ $patient->patient_name }}</strong>--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                <strong>{{ $patient->gender }}</strong>--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                <strong>{{ $patient->dob }}</strong>--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                <span class="badge badge-success">{{ $patient->age }}</span>--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                <a class="btn btn-sm btn-secondary" href="{{ route('admin.patient.show', $patient->id) }}">--}}
{{--                                    <i class="fa fa-heartbeat text-danger mr-5"></i> Histori Medis--}}
{{--                                </a>--}}
{{--                            </td>--}}
{{--                            <td class="text-right">--}}
{{--                                <p class="font-w600 mb-10">{{ timezone()->convertToLocal($patient->created_at) }}</p>--}}
{{--                                <p class="text-muted mb-0">{{ $patient->created_at->diffForHumans() }}</p>--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                        @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
