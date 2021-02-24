@extends('backend.layouts.app')

@section('title', app_name() . ' | Laporan Data Pasien')

@section('content')
<style type="text/css">
tfoot {
    display: table-header-group;
}
</style>
<div class="row gutters-tiny">
    <!-- Row #3 -->
    <div class="col-md-4">
        <a class="block block-link-shadow" href="javascript:void(0)" onclick="getData('n');">
            <div class="block-content block-content-full">
                <div class="font-size-sm font-w600 text-uppercase text-muted text-right">Pasien Baru</div>
                <div class="py-50 text-center">
                    <div class="font-size-h1 font-w700 mb-0 text-primary">{{ App\Modules\Patient\Models\Patient::where('id_klinik',Auth()->user()->klinik->id_klinik)->whereOldPatient('n')->count() }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a class="block block-link-shadow" href="javascript:void(0)" onclick="getData('y');">
            <div class="block-content block-content-full">
                <div class="font-size-sm font-w600 text-uppercase text-muted text-right text-success">Pasien Lama</div>
                <div class="py-50 text-center">
                    <div class="font-size-h1 font-w700 mb-0 text-success">{{ App\Modules\Patient\Models\Patient::where('id_klinik',Auth()->user()->klinik->id_klinik)->whereOldPatient('y')->count() }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a class="block block-link-shadow" href="javascript:void(0)" onclick="getData(null);">
            <div class="block-content block-content-full">
                <div class="font-size-sm font-w600 text-uppercase text-muted text-right text-primary">Total Pasien</div>
                <div class="py-50 text-center">
                    <div class="font-size-h1 font-w700 mb-0 text-primary">{{ App\Modules\Patient\Models\Patient::where('id_klinik',Auth()->user()->klinik->id_klinik)->count() }}</div>
                </div>
            </div>
        </a>
    </div>
    <!-- END Row #3 -->
</div>

<div class="block" id="my-block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Laporan Data Pasien</h3>
    </div>
    <div class="block-content block-content-full">
        <table id="table" class="table table-sm table-hover table-striped table-vcenter" width="100%">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Nomor Pasien</th>
                    <th>Nama Lengkap</th>
                    <th>J.Kelamin</th>
                    <th>Usia</th>
                    <th>Kota</th>
                    <th>Kel/Desa</th>
                    <th>Kecamatan</th>
                    <th>Pasien Lama/Baru?</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
    jQuery(function(){
        Codebase.layout('sidebar_mini_on');

        var oldPatient = null;

        var dt = $('#table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            pageLength: 25,
            ajax: {
                url: '{!! route('admin.reporting.reportingpatient') !!}',
                data: function ( data ) {
                    data.oldPatient =  oldPatient;
                },
                type: "GET"
            },
            language: {
                url: "{!! URL::asset('js/plugins/datatables/i18n/'.str_replace('_', '-', app()->getLocale()).'.json') !!}"
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    width: "5%",
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'patient_unique_id',
                },
                {
                    data: 'patient_name',
                    className: 'font-w600'
                },
                {
                    data: 'gender',
                },
                {
                    data: 'age',
                    name: 'dob'
                },
                {
                    data: 'city',
                    name: 'city.name'
                },
                {
                    data: 'district',
                    name: 'district.name'
                },
                {
                    data: 'village',
                    name: 'village.name'
                },
                {
                    data: 'old_patient_string',
                    name: 'old_patient',
                    className: 'text-center'
                }
            ],
            initComplete: function () {
                this.api().columns([3,5,6,7,8]).every( function () {
                    var column = this;
                    var select = $('<select class="form-control"><option value="">Semua</option></select>')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        } );

                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                } );

                this.api().columns([1,2]).every( function () {
                    var column = this;
                    var input = $('<input type="text" class="form-control" placeholder="Cari.." />')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'keyup change', function () {
                            if ( column.search() !== this.value ) {
                                column
                                    .search( this.value )
                                    .draw();
                            }
                        } );
                } );
            }
        }).on('processing.dt', function (e, settings, processing) {
            if (processing) {
                Codebase.blocks('#my-block', 'state_loading');
            } else {
                Codebase.blocks('#my-block', 'state_normal');
            }
        }).on('draw.dt', function () {
            $('[data-toggle="tooltip"]').tooltip();
            addDeleteForms();
        } );

        getData = function(old){
            oldPatient = old;
            dt.ajax.reload();
        }

    });
</script>
@endsection
