@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('booking::menus.calendar.jadwal-treatment'))

@section('content')

    @include('accounting::reports.partial.range-date')

    <div class="clearfix"></div>
    <div class="clearfix"></div>

    <div class="block" id="my-block-2">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('booking::menus.calendar.jadwal-treatment')</h3>
        </div>

        <div class="block-content block-content-full">
            <table id="table" class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>PID</th>
                        <th class="text-center">@lang('booking::labels.tables.patient_name')</th>
                        <th class="text-center">@lang('booking::labels.tables.date')</th>
                        <th class="text-center">@lang('booking::labels.tables.time')</th>
                        <th class="text-center">@lang('booking::labels.tables.room')</th>
                        <th class="text-center">@lang('booking::labels.tables.person')</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Reminder</th>
                        <th class="text-center" style="width: 100px;">@lang('labels.general.actions')</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <script>
         jQuery(function(){
            var dt = $('#table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ordering: false,
                pageLength: 25,
                ajax: {
                        url : '{!! route('admin.calendar.jadwal-treatment') !!}',
                        data : function(data){
                            data.date_1 =  "{{ $date['date_1'] }}";
                            data.date_2 =  "{{ $date['date_2'] }}";
                        }
                    },
                language: {
                    url: "{!! URL::asset('js/plugins/datatables/i18n/'.str_replace('_', '-', app()->getLocale()).'.json') !!}"
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'id',
                        width: "5%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'treatment_no',
                        name: 'treatment_no',
                    },
                    {
                        data: 'pid',
                        name: 'pid',
                        searchable: false
                    },
                    {
                        data: 'patient_name',
                        name: 'patient.patient_name',
                    },
                    {
                        data: 'date',
                        name: 'date',
                        searchable: false
                    },
                    {
                        data: 'time',
                        name: 'time',
                        searchable: false
                    },
                    {
                        data: 'room',
                        name: 'room.name',
                    },
                    {
                        data: 'staff',
                        name: 'staff',
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status.name',
                    },
                    {
                        data: 'reminder',
                        name: 'reminder',
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        width: "8%",
                        className: 'text-right',
                        orderable: false,
                        searchable: false
                    },
                ]
            }).on('processing.dt', function (e, settings, processing) {
                if (processing) {
                    Codebase.blocks('#my-block-2', 'state_loading');
                } else {
                    Codebase.blocks('#my-block-2', 'state_normal');
                }
            }).on('draw.dt', function () {
                $('[data-toggle="tooltip"]').tooltip();
                addDeleteForms();
            });

            sendWa = function(patient, date){
            Swal.fire({
                    title: 'Kirim Reminder Via Whatsapp ?',
                    type: 'question',
                    showCancelButton: true,
                    showConfirmButton: true,
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('admin.calendar.sendWa') }}",
                            data: {
                                patient : patient,
                                date: date
                            },
                            beforeSend: function(xhr){
                                Codebase.blocks('#my-block2', 'state_loading');
                            },
                            error: function(x, status, error){
                                if(x.status === 422 ){
                                    var errors = x.responseJSON;
                                    var errorsHtml = '';

                                    $.each(errors['errors'], function(index, value){
                                        errorsHtml += '<ul><li class="text-danger">' + value + '</li></ul>';
                                    });

                                        $.alert({
                                            title: "Error " + x.status + ': ' + error,
                                            icon: 'fa fa-warning',
                                            type: 'red',
                                            content: errorsHtml,
                                            columnClass: 'col-md-5 col-md-offset-3',
                                            typeAnimated: true,
                                            draggable: false,
                                        });
                                    }else{
                                        var errors = x.responseJSON;
                                        $.alert({
                                            title: x.status + ': ' + error,
                                            icon: 'fa fa-warning',
                                            type: 'red',
                                            content: errors.messages,
                                        });
                                    }

                                    Codebase.blocks('#my-block2', 'state_normal');
                                },
                            success: function(result){
                                if(result.status){
                                    $.notify({
                                        message: result.message,
                                        type: 'success'
                                    });

                                    dt.ajax.reload();
                                    // setTimeout(function(){
                                    //     window.location = '{{ url('admin/crm/membership/grade/semua') }}';
                                    // }, 2000);
                                }else{
                                    $.alert({
                                        title: 'Error',
                                        icon: 'fa fa-warning',
                                        type: 'orange',
                                        content: result.message,
                                    });
                                }
                                Codebase.blocks('#my-block2', 'state_normal');
                            }
                        });
                    }
                })
            }

        })
    </script>
@endsection
