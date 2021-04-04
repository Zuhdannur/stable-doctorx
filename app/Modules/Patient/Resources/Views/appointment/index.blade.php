@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('patient::labels.patient.management'))

@section('content')
<div class="block" id="my-block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('patient::labels.patient.management')</h3>
        <div class="block-options">
            <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                <a href="{{ route('admin.patient.appointment.create', [0, 0]) }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
                    <i class="fa fa-random"></i> @lang('labels.general.create_new')
                </a>
            </div>
        </div>
    </div>
    <div class="block-content block-content-full">
        <div class="row">
                <div class="col-lg-4 offset-lg-4">
                    <div class="form-group row">
                        <div class="col-12">
                            <label for="startdate">Tanggal</label>
                            <div class="input-daterange input-group" data-date-format="{{ setting()->get('date_format_js') }}" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                <input type="text" class="form-control" id="start_date" name="start_date" placeholder="Dari" data-week-start="1" data-autoclose="true" data-today-highlight="true" autocomplete="off" value="{{ isset($input['start_date']) ? $input['start_date'] : null }}" readonly>
                                <div class="input-group-prepend input-group-append">
                                    <span class="input-group-text font-w600">s/d</span>
                                </div>
                                <input type="text" class="form-control" id="end_date" name="end_date" placeholder="Hingga" data-week-start="1" data-autoclose="true" data-today-highlight="true" autocomplete="off" value="{{ isset($input['end_date']) ? $input['end_date'] : null }}" readonly>&nbsp;
                                <button type="button" class="btn btn-alt-success" onclick="getData();" id="showData">
                                    <i class="fa fa-plus mr-5"></i> Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <table id="table" class="table table-sm table-hover table-striped table-vcenter" width="100%">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>@lang('patient::labels.appointment.table.app_no')</th>
                    <th>@lang('patient::labels.appointment.table.unique_id')</th>
                    <th>@lang('patient::labels.appointment.table.name')</th>
                    <th>Flag</th>
                    <th>@lang('patient::labels.appointment.table.date')</th>
                    <th>Jam</th>
                    <th>@lang('patient::labels.appointment.table.room')</th>
                    <th>@lang('patient::labels.appointment.table.floor')</th>
                    <th>@lang('patient::labels.appointment.table.pic')</th>
                    <th>@lang('patient::labels.appointment.table.status')</th>
                    <th>@lang('patient::labels.appointment.table.notes')</th>
                    <th class="text-center" style="width: 100px;"></th>
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
                </tr>
            </tfoot>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="view_consent" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">SURAT PERSETUJUAN/PENOLAKAN MEDIS KHUSUS</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
{{--                    <table class="table">--}}
{{--                        <tr>--}}
{{--                            <td>Nama</td>--}}
{{--                            <td>CANDA</td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <td>Jenis Kelamin (L/P)</td>--}}
{{--                            <td></td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <td>Umur</td>--}}
{{--                            <td></td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <td>Alamat</td>--}}
{{--                            <td></td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <td>Telp</td>--}}
{{--                            <td></td>--}}
{{--                        </tr>--}}
{{--                    </table>--}}
{{--                    <p>Menyatakan dengan sesungguhnya dari saya sendiri/*sebagai orang tua/*suami/*istri/*anak/*wali dari:</p>--}}
{{--                    <table class="table">--}}
{{--                        <tr>--}}
{{--                            <td>Nama</td>--}}
{{--                            <td>CANDA</td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <td>Jenis Kelamin (L/P)</td>--}}
{{--                            <td></td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <td>Umur</td>--}}
{{--                            <td></td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <td>Alamat</td>--}}
{{--                            <td></td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <td>Telp</td>--}}
{{--                            <td></td>--}}
{{--                        </tr>--}}
{{--                    </table>--}}

{{--                    <p>Dengan ini menyatakan SETUJU/MENOLAK untuk dilakukan Tindakan Medis berupa</p>--}}
{{--                    <strong><p>SETUJU</p></strong>--}}
{{--                    <p>Dari penjelasan yang diberikan, telah saya mengerti segala hal yang berhubungan dengan penyakit tersebut, serta tindakan medis yang akan dilakukan dan kemungkinana pasca tindakan yang dapat terjadi sesuai penjelasan yang diberikan</p>--}}
                    <div role="main" id="viewport"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">

        </div>
    </div>
</div>

<script src="{{ URL::asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ URL::asset('js/pdf.js') }}"></script>
<script>
    jQuery(function(){
        Codebase.layout('sidebar_mini_on');
        var formatDate = "{{ setting()->get('date_format_js') }}";
        // Codebase.helpers(['datepicker']);
        $('.input-daterange').datepicker({
            todayHighlight: true,
            format: "{{ setting()->get('date_format_js') }}",
            weekStart: 1,
            language: "{!! str_replace('_', '-', app()->getLocale()) !!}",
            daysOfWeekHighlighted: "0,6",
            autoclose: true,
        });

        var startdate, enddate;

        var dt = $('#table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            pageLength: 25,
            ajax: {
                url: '{!! route('admin.patient.appointment.index') !!}',
                data: function ( data ) {
                    data.startDate =  startdate;
                    data.endDate =  enddate;
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
                    data: 'appointment_no',
                },
                {
                    data: 'patient_unique_id',
                    name: 'patient.patient_unique_id'
                },
                {
                    data: 'patient_name',
                    name: 'patient.patient_name',
                    className: 'font-w600'
                },
                {
                    data: 'flag_color',
                    width: "5%",
                },
                {
                    data: 'date',
                    className: 'text-left'
                },
                {
                    data: 'hour',
                    className: 'text-left'
                },
                {
                    data: 'room_name',
                },
                {
                    data: 'floor_name',
                },
                {
                    data: 'staff_name',
                },
                {
                    data: 'status',
                },
                {
                    data: 'notes',
                    visible: false
                },
                {
                    data: 'action',
                    width: "8%",
                    className: 'text-right',
                    orderable: false,
                    searchable: false
                }
            ]
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

        getData = function(){
            startdate = $('#start_date').val();
            enddate = $('#end_date').val();

            dt.ajax.reload();
        }

    });

    $("body").on('click','.btnConsent',function (){
        let pdfInstance = null
        var url = "{!! asset("inform_concern") !!}/"+$(this).val().split("/")[1]
        var viewport = $('#viewport');

        pdfjsLib.GlobalWorkerOptions.workerSrc = '{!! asset('js/pdf.worker.js') !!}'
        pdfjsLib.getDocument(url).promise.then(pdf => {
            pdfInstance = pdf
            viewport.innerHTML = '<div><canvas></canvas></div>'

            var pdfViewport = pdf.getViewport(180);

            var container = viewport.children[0];

            // Render at the page size scale.
            pdfViewport = pdf.getViewport(container.offsetWidth / pdfViewport.width);
            var canvas = container.children[0];
            var context = canvas.getContext("2d");
            canvas.height = pdfViewport.height;
            canvas.width = pdfViewport.width;

            pdf.render({
                canvasContext: context,
                viewport: pdfViewport
            });
        })


        $("#view_consent").modal('show')
    })
</script>
@endsection
