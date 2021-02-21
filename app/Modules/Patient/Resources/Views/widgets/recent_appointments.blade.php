<div class="row"> 
    <div class="col-sm-12">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title">Konsultasi Terakhir</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-borderless table-hover table-striped table-vcenter mb-0">
                        <thead>
                            <tr>
                                <th style="width: 100px;">No.</th>
                                <th>Nama Pasien</th>
                                <th>Pertama Kali ?</th>
                                <th class="text-right">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latest as $appointment)
                            <tr>
                                <td>
                                    <a class="font-w600" href="javascript:void(0)">{{ $appointment->appointment_no }}</a>
                                </td>
                                <td>
                                    <a href="javascript:void(0)">{{ $appointment->patient->patient_name }}</a>
                                </td>
                                <td>
                                    <i class="fa fa-check-circle text-success"></i>
                                </td>
                                <td class="text-right">
                                    <p class="font-w600 mb-10">{{ timezone()->convertToLocal($appointment->date) }}</p>
                                    <p class="font-size-sm text-muted mb-0">{{ $appointment->date->diffForHumans() }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>