<div class="row"> 
    @foreach($types as $type)
        <div class="col-sm-4">
            <div class="block block-rounded block-bordered">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Antrian {{ $type->name }}</h3>
                </div>
                <div class="block-content block-content-full" style="min-height: 400px;">
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover table-striped table-vcenter mb-0 js-dataTable-simple">
                            <thead>
                                <tr>
                                    <th>Nomor Antrian</th>
                                    <th>Nama</th>
                                    <th>Usia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $arrayData = $today->groupBy('admission_type_id')->take(5);
                                @endphp
                                @if(isset($arrayData[$type->id]))
                                    @foreach($arrayData[$type->id] as $key => $data)
                                    @php
                                        if($key == 8) break;
                                        
                                        $route = $type->route ? route($type->route, [$data->patient->id, $data->id]) : 'javascript:void(0)';
                                    @endphp
                                    <tr class="{{ ($data->status_id == 1) ? 'table-info' : 'table-danger' }}">
                                        <td>
                                            <a class="font-w600" href="{{ ($data->status_id == 1) ? $route : 'javascript:void(0)' }}">{{ $data->admission_no }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.patient.show', $data->patient->id) }}">{{ $data->patient->patient_name }}</a>
                                        </td>
                                        <td>
                                            <strong>{{ $data->patient->age }}</strong>
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
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>