@extends('backend.layouts.app')

@section('title', app_name() . ' | Kalendar Pasien')

@section('content')
<!-- Page JS Plugins CSS -->
<!-- Calendar and Events functionality is initialized in js/pages/be_comp_calendar.min.js which was auto compiled from _es6/pages/be_comp_calendar.js -->
<!-- For more info and examples you can check out https://fullcalendar.io/ -->
<div class="block" id="my-block">
    <div class="block-content">
        <div class="row items-push">
            <div class="col-xl-12">
                <!-- Calendar Container -->
                <div class="js-calendar"></div>
            </div>
        </div>
    </div>
</div>
<!-- END Calendar -->

<!-- Pop Out Modal -->
<div id="eventContent" class="modal fade" id="modal-popout" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title" id="titleHeader"><span id="startTime"></span></h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="font-size-lg text-black mb-5" id="eventInfo"></div>
                    <address>
                    Notes: <span id="eventDesc"></span><br>
                    Dokter: <span id="eventDokter"></span><br>
                    Ruangan: <span id="eventRoom"></span><br>
                    Lantai: <span id="eventLantai"></span>
                    </address>
                </div>
            </div>
            <div class="modal-footer">
                <a id="eventLink" href="" target="_blank" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Input Tindakan
                </a>
            </div>
        </div>
    </div>
</div>
<!-- END Pop Out Modal -->

<script src="{{ URL::asset('js/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/moment/moment.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/fullcalendar/fullcalendar.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/fullcalendar/locale/id.js') }}"></script>

<script type="text/javascript">
class BeCompCalendar {
    /*
     * Init calendar demo functionality
     *
     */
    static initCalendar() {
        let date = new Date();
        let d    = date.getDate();
        let m    = date.getMonth();
        let y    = date.getFullYear();

        jQuery('.js-calendar').fullCalendar({
            locale: 'id',
            firstDay: 1,
            editable: true,
            droppable: true,
            header: {
                left: 'title',
                right: 'prev,next today month,agendaWeek,agendaDay,listWeek'
            },
            drop: (date, jsEvent, ui, resourceId) => { // this function is called when something is dropped
                let event = jQuery(ui.helper);

                // retrieve the dropped element's stored Event Object
                let originalEventObject = event.data('eventObject');

                // we need to copy it, so that multiple events don't have a reference to the same object
                let copiedEventObject = jQuery.extend({}, originalEventObject);

                // assign it the date that was reported
                copiedEventObject.start = date;

                // render the event on the calendar
                // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                jQuery('.js-calendar').fullCalendar('renderEvent', copiedEventObject, true);

                // remove the element from the "Draggable Events" list
                event.remove();
            },
            events: '{!! route('admin.patient.calendar.load') !!}',
            loading: function( isLoading, view ) {
                if(isLoading) {// isLoading gives boolean value
                    Codebase.blocks('#my-block', 'state_loading');
                } else {
                    Codebase.blocks('#my-block', 'state_normal');
                }
            },
            editable: true,
            selectable:true,
            selectHelper:true,
            select: function (start, end, allDay)
            {
                window.location.replace("{{ route('admin.patient.appointment.create', [0,0]) }}");
                // calendar.fullCalendar('refetchEvents');

                /*var loadUrl = $(this).data('href'),
                    cacheResult = $(this).data('cache') === 'on';

                $('#modal-form-appointment.modal').remove();
                $('#modal-form-appointment.modal-backdrop').remove();

                var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");

                $.ajax({
                    url: "{{ route('admin.patient.appointment.loadform') }}",
                    data: {
                        start: start,
                        end: end
                    },
                    localCache: cacheResult,
                    dataType: 'html',
                    beforeSend: function(xhr) {
                        Codebase.blocks('#my-block', 'state_loading');
                    },
                    error: function(x, status, error) {
                        if (x.status == 403) {
                            $.alert({
                                title: 'Error',
                                icon: 'fa fa-warning',
                                type: 'red',
                                content: "Error: " + x.status + "",
                            });
                        } else if (x.status === 422) {
                            var errors = x.responseJSON;
                            var errorsHtml = '';
                            $.each(errors['errors'], function(index, value) {
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

                        } else {
                            $.alert({
                                title: 'Error',
                                icon: 'fa fa-warning',
                                type: 'red',
                                content: "An error occurred: " + status + "nError: " + error,
                            });
                        }

                        Codebase.blocks('#my-block', 'state_normal');
                    },
                    success: function(data) {
                        $('body').append(data);

                        var $modal = $('#modal-form-appointment.modal');

                        $modal.modal({
                            'backdrop': 'static'
                        });

                        $modal.modal('show');

                        Codebase.blocks('#my-block', 'state_normal');
                    }
                });*/
            },
            editable: true,

            /*eventResize: function (event)
            {
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                var title = event.title;
                var id = event.id;
                $.ajax(
                {
                    url: "update.php",
                    type: "POST",
                    data:
                    {
                        title: title,
                        start: start,
                        end: end,
                        id: id
                    },
                    success: function ()
                    {
                        calendar.fullCalendar('refetchEvents');
                        alert('Event Update');
                    }
                })
            },

            eventDrop: function (event)
            {
                var start = event.start;
                var end = event.end;
                var title = event.title;
                var id = event.id;
                $.ajax(
                {
                    url: "update.php",
                    type: "POST",
                    data:
                    {
                        title: title,
                        start: start,
                        end: end,
                        id: id
                    },
                    success: function ()
                    {
                        calendar.fullCalendar('refetchEvents');
                        alert("Event Updated");
                    }
                });
            },

            eventClick: function (event)
            {
                if (confirm("Are you sure you want to remove it?"))
                {
                    var id = event.id;
                    $.ajax(
                    {
                        url: "delete.php",
                        type: "POST",
                        data:
                        {
                            id: id
                        },
                        success: function ()
                        {
                            calendar.fullCalendar('refetchEvents');
                            alert("Event Removed");
                        }
                    })
                }
            },*/
            /*events: [
                {
                    title: 'Gaming Day',
                    start: new Date(y, m, 1),
                    allDay: true,
                    color: '#fcf7e6'
                },
                {
                    title: 'Skype Meeting',
                    start: new Date(y, m, 3)
                },
                {
                    title: 'Project X',
                    start: new Date(y, m, 9),
                    end: new Date(y, m, 12),
                    allDay: true,
                    color: '#fae9e8'
                },
                {
                    title: 'Work',
                    start: new Date(y, m, 17),
                    end: new Date(y, m, 19),
                    allDay: true,
                    color: '#fae9e8'
                },
                {
                    id: 999,
                    title: 'Hiking (repeated)',
                    start: new Date(y, m, d - 1, 15, 0)
                },
                {
                    id: 999,
                    title: 'Hiking (repeated)',
                    start: new Date(y, m, d + 3, 15, 0)
                },
                {
                    title: 'Landing Template',
                    start: new Date(y, m, d - 3),
                    end: new Date(y, m, d - 3),
                    allDay: true,
                    color: '#fcf7e6'
                },
                {
                    title: 'Lunch',
                    start: new Date(y, m, d + 7, 15, 0),
                    color: '#ebf5df'
                },
                {
                    title: 'Coding',
                    start: new Date(y, m, d, 8, 0),
                    end: new Date(y, m, d, 14, 0),
                    color: '#fcf7e6'
                },
                {
                    title: 'Trip',
                    start: new Date(y, m, 25),
                    end: new Date(y, m, 27),
                    allDay: true,
                    color: '#fcf7e6'
                },
                {
                    title: 'Reading',
                    start: new Date(y, m, d + 8, 20, 0),
                    end: new Date(y, m, d + 8, 22, 0)
                },
                {
                    title: 'Follow me on Twitter',
                    start: new Date(y, m, 22),
                    allDay: true,
                    url: 'http://twitter.com/pixelcave'
                }
            ]*/
            /*eventRender: function(event, element) {
                $(element).tooltip({
                    title: event.title,
                    placement: 'top',
                });
            },*/
            eventRender: function (event, element) {
                element.attr('href', 'javascript:void(0);');
                element.click(function() {
                    $("#startTime").html(moment(event.start).format('Do MMMM Y HH:mm:ss'));
                    $("#eventInfo").html(event.description);
                    $("#eventDesc").html(event.notes);
                    $("#eventDokter").html(event.staff);
                    $("#eventRoom").html(event.room);
                    $("#eventLantai").html(event.floor);
                    $("#eventLink").attr('href', event.url);
                    $("#eventContent").modal({ 
                        'backdrop': 'static'
                    });
                });
            }
        });
    }

    /*
     * Init functionality
     *
     */
    static init() {
        this.initCalendar();
    }
}

// Initialize when page loads
jQuery(() => { BeCompCalendar.init(); });
</script>
@endsection