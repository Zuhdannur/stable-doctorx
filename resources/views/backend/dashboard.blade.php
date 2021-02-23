@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.dashboard.title'))

@section('content')
    <!-- Overview -->
    @asyncWidget('\App\Modules\Patient\Widgets\RecentNews')
    <!-- END Overview -->

    <!-- Data Antrian -->
    @asyncWidget('\App\Modules\Patient\Widgets\RecentQueues', ['id' => 0])

    @asyncWidget('\App\Modules\Patient\Widgets\RecentAssign', ['id' => 0])
    <!-- END Antrian -->

    {{-- booking --}}
    @asyncWidget('\App\Modules\Patient\Widgets\TodayBooking', ['id' => 0])
    {{-- End of booking --}}

    <!-- Data Appointment -->
    <!-- @asyncWidget('\App\Modules\Patient\Widgets\RecentAppointments', ['id' => 0]) -->
    <!-- END Appointment -->

    <!-- Patients -->
    @asyncWidget('\App\Modules\Patient\Widgets\RecentPatients')
    <!-- END Patients -->

    <!-- Payments -->
    <div class="block block-rounded block-bordered invisible d-none" data-toggle="appear">
        <div class="block-header block-header-default">
            <h3 class="block-title">Open Payments</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option">
                    <i class="si si-wrench"></i>
                </button>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table class="table table-borderless table-hover table-striped table-vcenter mb-0">
                    <thead>
                        <tr>
                            <th style="width: 100px;">ID</th>
                            <th>Patient</th>
                            <th>Invoice</th>
                            <th>Due</th>
                            <th>Actions</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <a class="font-w600" href="javascript:void(0)">158214</a>
                            </td>
                            <td>
                                <a href="javascript:void(0)">Lori Grant</a>
                            </td>
                            <td>
                                <a href="javascript:void(0)">
                                                        INV_158214.pdf
                                                    </a>
                            </td>
                            <td>
                                <span class="badge badge-primary">tomorrow</span>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-secondary m-5" href="javascript:void(0)">
                                    <i class="fa fa-pencil text-primary mr-5"></i> Edit
                                </a>
                                <a class="btn btn-sm btn-secondary m-5" href="javascript:void(0)">
                                    <i class="fa fa-times text-danger mr-5"></i> Cancel
                                </a>
                            </td>
                            <td class="text-right">
                                $3.500,00
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a class="font-w600" href="javascript:void(0)">158213</a>
                            </td>
                            <td>
                                <a href="javascript:void(0)">Amber Harvey</a>
                            </td>
                            <td>
                                <a href="javascript:void(0)">
                                                        INV_158213.pdf
                                                    </a>
                            </td>
                            <td>
                                <span class="badge badge-primary">tomorrow</span>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-secondary m-5" href="javascript:void(0)">
                                    <i class="fa fa-pencil text-primary mr-5"></i> Edit
                                </a>
                                <a class="btn btn-sm btn-secondary m-5" href="javascript:void(0)">
                                    <i class="fa fa-times text-danger mr-5"></i> Cancel
                                </a>
                            </td>
                            <td class="text-right">
                                $1.280,00
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right">
                                <strong class="text-uppercase">Total Due</strong>
                            </td>
                            <td class="text-right">
                                <strong>$4.780,00</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END Payments -->

    {{-- <div class="row gutters-tiny js-appear-enabled animated fadeIn d-none" data-toggle="appear">
        <!-- Row #1 -->
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block text-center" href="javascript:void(0)">
                <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left bg-gd-dusk">
                    <div class="ribbon-box">750</div>
                    <p class="mt-5">
                        <i class="si si-book-open fa-3x text-white-op"></i>
                    </p>
                    <p class="font-w600 text-white">Articles</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block text-center" href="javascript:void(0)">
                <div class="block-content bg-gd-primary">
                    <p class="mt-5">
                        <i class="si si-plus fa-3x text-white-op"></i>
                    </p>
                    <p class="font-w600 text-white">New Article</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block text-center" href="be_pages_forum_categories.html">
                <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left bg-gd-sea">
                    <div class="ribbon-box">16</div>
                    <p class="mt-5">
                        <i class="si si-bubbles fa-3x text-white-op"></i>
                    </p>
                    <p class="font-w600 text-white">Comments</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block text-center" href="be_pages_generic_search.html">
                <div class="block-content bg-gd-lake">
                    <p class="mt-5">
                        <i class="si si-magnifier fa-3x text-white-op"></i>
                    </p>
                    <p class="font-w600 text-white">Search</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block text-center" href="be_comp_charts.html">
                <div class="block-content bg-gd-emerald">
                    <p class="mt-5">
                        <i class="si si-bar-chart fa-3x text-white-op"></i>
                    </p>
                    <p class="font-w600 text-white">Statistics</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block text-center" href="javascript:void(0)">
                <div class="block-content bg-gd-corporate">
                    <p class="mt-5">
                        <i class="si si-settings fa-3x text-white-op"></i>
                    </p>
                    <p class="font-w600 text-white">Settings</p>
                </div>
            </a>
        </div>
        <!-- END Row #1 -->
    </div>
    <div class="row row-deck gutters-tiny js-appear-enabled animated fadeIn d-none" data-toggle="appear">
        <!-- Row #2 -->
        <div class="col-xl-4">
            <a class="block block-transparent bg-image d-flex align-items-stretch" href="javascript:void(0)" style="background-image: url('{{ URL::asset('media/photos/photo24@2x.jpg') }}');">
                <div class="block-content block-sticky-options pt-100 bg-black-op">
                    <div class="block-options block-options-left text-white">
                        <div class="block-options-item">
                            <i class="si si-book-open text-white-op"></i>
                        </div>
                    </div>
                    <div class="block-options text-white">
                        <div class="block-options-item">
                            <i class="si si-bubbles"></i> 15
                        </div>
                        <div class="block-options-item">
                            <i class="si si-eye"></i> 3800
                        </div>
                    </div>
                    <h2 class="h3 font-w700 text-white mb-5">Travel the world</h2>
                    <h3 class="h5 text-white-op">Explore and achieve great things</h3>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-4">
            <a class="block block-transparent bg-image d-flex align-items-stretch" href="javascript:void(0)" style="background-image: url('{{ URL::asset('media/photos/photo32@2x.jpg') }}');">
                <div class="block-content block-sticky-options pt-100 bg-primary-dark-op">
                    <div class="block-options block-options-left text-white">
                        <div class="block-options-item">
                            <i class="si si-book-open text-white-op"></i>
                        </div>
                    </div>
                    <div class="block-options text-white">
                        <div class="block-options-item">
                            <i class="si si-bubbles"></i> 4
                        </div>
                        <div class="block-options-item">
                            <i class="si si-eye"></i> 1680
                        </div>
                    </div>
                    <h2 class="h3 font-w700 text-white mb-5">Inspiring Solutions</h2>
                    <h3 class="h5 text-white-op">10 things you should do today</h3>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-4">
            <a class="block block-transparent bg-image d-flex align-items-stretch" href="javascript:void(0)" style="background-image: url('{{ URL::asset('media/photos/photo10@2x.jpg') }}')">
                <div class="block-content block-sticky-options pt-100 bg-primary-op">
                    <div class="block-options block-options-left text-white">
                        <div class="block-options-item">
                            <i class="si si-book-open text-white-op"></i>
                        </div>
                    </div>
                    <div class="block-options text-white">
                        <div class="block-options-item">
                            <i class="si si-bubbles"></i> 16
                        </div>
                        <div class="block-options-item">
                            <i class="si si-eye"></i> 4450
                        </div>
                    </div>
                    <h2 class="h3 font-w700 text-white mb-5">Alternative Road</h2>
                    <h3 class="h5 text-white-op">Don't let anything disorient you</h3>
                </div>
            </a>
        </div>
        <!-- END Row #2 -->
    </div>
    <div class="row gutters-tiny js-appear-enabled animated fadeIn d-none" data-toggle="appear">
        <!-- Row #3 -->
        <div class="col-xl-8 d-flex align-items-stretch">
            <div class="block block-themed block-mode-loading-inverse block-transparent bg-image w-100" style="background-image: url('{{ URL::asset('media/photos/photo34@2x.jpg') }}');">
                <div class="block-header bg-black-op">
                    <h3 class="block-title">
                                            Sales <small>This week</small>
                                        </h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-wrench"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content bg-black-op">
                    <div class="pull-r-l">
                        <div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                            <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                            </div>
                        </div>
                        <!-- Lines Chart Container -->
                        <canvas class="js-chartjs-dashboard-lines chartjs-render-monitor" width="1040" height="520" style="display: block; height: 416px; width: 832px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 d-flex align-items-stretch">
            <div class="block block-transparent bg-primary-dark d-flex align-items-center w-100">
                <div class="block-content block-content-full">
                    <div class="py-15 px-20 clearfix border-black-op-b">
                        <div class="float-right mt-15 d-none d-sm-block">
                            <i class="si si-book-open fa-2x text-success"></i>
                        </div>
                        <div class="font-size-h3 font-w600 text-success js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="750">750</div>
                        <div class="font-size-sm font-w600 text-uppercase text-success-light">Articles</div>
                    </div>
                    <div class="py-15 px-20 clearfix border-black-op-b">
                        <div class="float-right mt-15 d-none d-sm-block">
                            <i class="si si-wallet fa-2x text-danger"></i>
                        </div>
                        <div class="font-size-h3 font-w600 text-danger">$<span data-toggle="countTo" data-speed="1000" data-to="980" class="js-count-to-enabled">980</span></div>
                        <div class="font-size-sm font-w600 text-uppercase text-danger-light">Earnings</div>
                    </div>
                    <div class="py-15 px-20 clearfix border-black-op-b">
                        <div class="float-right mt-15 d-none d-sm-block">
                            <i class="si si-envelope-open fa-2x text-warning"></i>
                        </div>
                        <div class="font-size-h3 font-w600 text-warning js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="38">38</div>
                        <div class="font-size-sm font-w600 text-uppercase text-warning-light">Messages</div>
                    </div>
                    <div class="py-15 px-20 clearfix border-black-op-b">
                        <div class="float-right mt-15 d-none d-sm-block">
                            <i class="si si-users fa-2x text-info"></i>
                        </div>
                        <div class="font-size-h3 font-w600 text-info js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="260">260</div>
                        <div class="font-size-sm font-w600 text-uppercase text-info-light">Online</div>
                    </div>
                    <div class="py-15 px-20 clearfix">
                        <div class="float-right mt-15 d-none d-sm-block">
                            <i class="si si-drop fa-2x text-elegance"></i>
                        </div>
                        <div class="font-size-h3 font-w600 text-elegance js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="59">59</div>
                        <div class="font-size-sm font-w600 text-uppercase text-elegance-light">Themes</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Row #3 -->
    </div>
    <div class="row gutters-tiny js-appear-enabled animated fadeIn d-none" data-toggle="appear">
        <!-- Row #4 -->
        <div class="col-md-4">
            <div class="block block-transparent bg-default">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-20">
                            <i class="fa fa-envelope-open fa-4x text-default-light"></i>
                        </div>
                        <div class="font-size-h4 font-w600 text-white">19.5k Subscribers</div>
                        <div class="text-white-op">Your main list is growing!</div>
                        <div class="pt-20">
                            <a class="btn btn-rounded btn-alt-primary" href="javascript:void(0)">
                                <i class="fa fa-cog mr-5"></i> Manage list
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block block-transparent bg-info">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-20">
                            <i class="fa fa-twitter fa-4x text-info-light"></i>
                        </div>
                        <div class="font-size-h4 font-w600 text-white">+98 followers</div>
                        <div class="text-white-op">You are doing great!</div>
                        <div class="pt-20">
                            <a class="btn btn-rounded btn-alt-info" href="javascript:void(0)">
                                <i class="fa fa-users mr-5"></i> Check them out
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block block-transparent bg-success">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-20">
                            <i class="fa fa-check fa-4x text-success-light"></i>
                        </div>
                        <div class="font-size-h4 font-w600 text-white">Personal Plan</div>
                        <div class=" text-white-op">This is your current active plan</div>
                        <div class="pt-20">
                            <a class="btn btn-rounded btn-alt-success" href="javascript:void(0)">
                                <i class="fa fa-arrow-up mr-5"></i> Upgrade to VIP
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Row #4 -->
    </div> --}}

    {{-- modal event --}}
    <div id="event-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
            <div class="block-header bg-primary-dark">
                <h3 class="block-title" id="titleHeader"><span id="startTime">Detail</span></h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="si si-close"></i>
                    </button>
                </div>
            </div>
                @include('booking::partial.detail-event')
            </div>
        </div>
        </div>
    </div>
    {{-- end modal event --}}

<script type="text/javascript">
    jQuery(function(){
        Codebase.layout('sidebar_mini_on');
    });

    function showModalBooking(id){

        a = id.split("##");

        if(typeof(a[1]) == 'undefined'){
            $.alert({
                title: 'Error',
                icon: 'fa fa-warning',
                type: 'red',
                content: "An error occurred: Wrong Parameter, Please refresh this page !",
            });

            return false;
        }

        jQuery.ajax({
            url : '{!! route("admin.booking.getBooking") !!}',
            method : 'GET',
            data : {
                'id' : a[0],
                'type' : a[1],
            },
            beforeSend: function(xhr) {
                Codebase.blocks('#my-block2', 'state_loading');
            },
            error: function(x, status, error) {
                if (x.status == 403) {
                    $.alert({
                        title: 'Error',
                        icon: 'fa fa-warning',
                        type: 'red',
                        content: "Error: " + x.status + "",
                    });
                } else {
                    $.alert({
                        title: 'Error',
                        icon: 'fa fa-warning',
                        type: 'red',
                        content: "An error occurred: " + status + "nError: " + error,
                    });
                }
                    Codebase.blocks('#my-block2', 'state_normal');
                },
            success : function(data){
                if(data.status == true){
                    $.each(data.msg, function(key, val){
                        if(key == 'type' || key == 'id'){
                            $('#event-modal #'+key).val(val);
                        }else{
                            $('#event-modal #'+key).html(val);
                        }
                    })

                    $('#event-modal #status_name').hide();
                    $('#event-modal #status-logic').html(
                        '<div class="status">'
                        +'<select name="status" id="status" class="form-control select2" style="width:100%">'
                        +'<option value="1">Confirmed</option>'
                        +'<option value="3">Cancel</option>'
                        +'<option value="5" selected>Booking</option>'
                        +'<option value="7">Reschedule</option>'
                        +'</select>'
                        +"</div>"
                    );

                    $('#event-modal #status').select2();
                    $('#event-modal #status').on('change', function(){
                        if($(this).val() == 7){
                            $('#event-modal #form-logic').append(
                                '<div class="form-group col-md-6 reschedule">'
                                    +'<label>Tanggal</label>'
                                    +'<input type="text" class="form-control datepicker" id="appointment_date" name="appointment_date">'
                                +'</div>'
                                +'<div class="form-group col-md-6 reschedule">'
                                    +'<label>Jam</label>'
                                    +'<input type="text" class="js-masked-time form-control" id="appointment_time" name="appointment_time">'
                                +'</div>'
                            )
                            Codebase.helpers(['masked-inputs']);
                            $('.datepicker').datepicker({
                                todayHighlight: true,
                                format: "{{ setting()->get('date_format_js') }}",
                                weekStart: 1,
                                language: "{!! str_replace('_', '-', app()->getLocale()) !!}",
                                daysOfWeekHighlighted: "0,6",
                                autoclose: true
                            });
                        }else{
                            $('#event-modal form .reschedule').remove();
                        }
                    });

                    $('#event-modal #btn-div').html(btn_update);
                    $('#event-modal #btn-submit').on('click', function(){
                        $(this).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
                        $('#event-modal > #formUpdate').trigger('submit');
                    });

                    $('#event-modal').modal({
                        'backdrop': 'static'
                    });
                }

                Codebase.blocks('#my-block2', 'state_normal');
            }
        })
    }

</script>
@endsection
