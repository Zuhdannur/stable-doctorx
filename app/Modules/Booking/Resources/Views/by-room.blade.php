@extends('backend.layouts.app')

@section('title', app_name().' | '.__('booking::menus.booking.menu'))

@section('content')
<script src="{{ URL::asset('js/plugins/moment/moment.min.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('js/plugins/spectrum/spectrum.css') }}">
<script src="{{ URL::asset('js/plugins/spectrum/spectrum.js') }}"></script>

<div class="block" id="my-block2">
  <div class="block-header block-header-default">
    <h3 class="block-title">@lang('booking::menus.booking.menu')</h3>
    <div class="block-options">
        <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
            <button class="btn btn-sm btn-alt-info" id="create-treatment" title="@lang('labels.general.create_new')">
                <i class="fa fa-random"></i> Booking Treatment
            </button>
            &nbsp;
            &nbsp;
            <button class="btn btn-sm btn-alt-info" id="create-appointment" title="@lang('labels.general.create_new')">
                <i class="fa fa-random"></i> Booking Konsultasi
            </button>
        </div>
    </div>
  </div>
  
    <div class="block-content">
      <div id='calendar'></div>
    </div>
</div>

{{-- form modal appointment--}}
<div id="appointment-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
  <div class="modal-dialog modal-dialog-popout" role="document">
    <div class="modal-content">
      <div class="block block-themed block-transparent mb-0">
        <div class="block-header bg-primary-dark">
            <h3 class="block-title" id="titleHeader"><span id="startTime">Booking Konsultasi</span></h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                    <i class="si si-close"></i>
                </button>
            </div>
        </div>
          @include('booking::partial.form')
      </div>
    </div>
  </div>
</div>
{{-- end form modal appointment--}}

{{-- form modal treatment --}}
<div id="treatment-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
  <div class="modal-dialog modal-dialog-popout modal-lg" role="document">
    <div class="modal-content">
      <div class="block block-themed block-transparent mb-0">
        <div class="block-header bg-primary-dark">
            <h3 class="block-title" id="titleHeader"><span id="startTime">Booking Treatment</span></h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                    <i class="si si-close"></i>
                </button>
            </div>
        </div>
          @include('booking::partial.form-treatment')
      </div>
    </div>
  </div>
</div>
{{-- end form modal treatment --}}

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

<script>
jQuery(function () {
  jQuery(document).ready(function() {
    Codebase.helpers(['masked-inputs']);

    $('#create-appointment').on('click', function(e){
      e.preventDefault();
      $('#appointment-modal').modal({
        'backdrop': 'static'
      });
    })

    $('#create-treatment').on('click', function(e){
      e.preventDefault();
      $('#treatment-modal').modal({
        'backdrop': 'static'
      });
    })

    $('#pid').select2({
        placeholder: "Pilih",
        minimumInputLength: 3,
        allowClear: true
    }).on("select2:select", function (e) {
        var selected = e.params.data;

        if (typeof selected !== "undefined") {
            var url = '{{ route("patient.getflag", ":id") }}';
            url = url.replace(':id', selected.id);

           $.ajax({
            url: url,
            beforeSend: function(xhr) {
                Codebase.blocks('#my-block', 'state_loading');
            },
            error: function() {
                alert('An error has occurred');
                Codebase.blocks('#my-block', 'state_normal');
            },
            success: function(result) {
                if(result.status){
                    $('#patient_flag_id').val(result.data.patient_flag_id);
                    $("#patient_flag_id").spectrum("set", result.data.name.toLowerCase());
                }else{
                    alert('data empty!');
                }
                
                Codebase.blocks('#my-block', 'state_normal');
            },
            type: 'GET'
        });
        }
    });
    
    $('.datepicker').datepicker({
        todayHighlight: true,
        format: "{{ setting()->get('date_format_js') }}",
        weekStart: 1,
        language: "{!! str_replace('_', '-', app()->getLocale()) !!}",
        daysOfWeekHighlighted: "0,6",
        autoclose: true
    });

    var lastDate = new Date();
    lastDate.setDate(lastDate.getDate());//any date you want
    $(".datepicker").datepicker('setDate', lastDate);
    $('#appointment_time').val((lastDate.getHours()<10?'0':'') + lastDate.getHours() + ":" + (lastDate.getMinutes()<10?'0':'') + lastDate.getMinutes());

    $('#staff_id, #room_id').select2({
        placeholder: "Pilih",
        allowClear: true
    });

    $(document).on("focusout", ".datepicker", function() {
        $(this).prop('readonly', false);
    });

    $(document).on("focusin", ".datepicker", function() {
        $(this).prop('readonly', true);
    });

    /** calendar handler */
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
      locale: 'id',
      plugins: [ 'resourceTimeGrid' ],
      timeZone: 'ID',
      defaultView: 'resourceTimeGridDay',
      resources: {!! $room !!},
      nowIndicator : true,
      events: function(info, successCallback, failureCallback){
          jQuery.ajax({
            url : '{!! route("admin.booking.event-by-room") !!}',
            method : 'GET',
            data : {
              'start' : convert(info.start),
              'end' : convert(info.end),
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
                failureCallback(error);
                Codebase.blocks('#my-block2', 'state_normal');
            },
            success : function(data){
              successCallback(data);
              Codebase.blocks('#my-block2', 'state_normal');
            }
          })
      },

      eventRender: function(event) {
        var tooltip = event.event.extendedProps.description;
      
        $(event.el).attr("title", tooltip);
        $(event.el).tooltip({
            container: "body",
            trigger: 'hover',
            placement: 'top',
        });
      },

      eventClick : function(event){
        eventModal(event);
      }

    });

    calendar.render();
    /** End OF calendar handler */
  });

  $('#pid').val('{{ ($pid ? $pid : '') }}').focus();
  setTimeout(function(){ 
      $('#pid').focusout();
  }, 500);

  function eventModal(event){
    $('#reminder-logic').addClass('d-none');
    $('#event-modal #status_name').show();
    $('#event-modal .status').remove();
    $('#event-modal form .reschedule').remove();
    $('#event-modal #btn-div').html('');

    $.each(event.event._def.extendedProps, function(key, val){
        if(key == 'type'){
            $('#event-modal #'+key).val(val);
        }else{
            $('#event-modal #'+key).html(val);
        }
    })

    if(event.event._def.extendedProps.type == 'Treatment'){
      $('#end_time_group').removeClass('d-none')
    }else{
      $('#end_time_group').addClass('d-none')
    }

    switch (event.event._def.extendedProps.status) {
      case 5:
        $('#event-modal #status_name').hide();
        $('#event-modal #status-logic').append(
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

        $('#event-modal #id').val(event.event._def.publicId);
        $('#event-modal #btn-div').html(btn_wa + btn_update);
        $('#btnWa').on('click', function (e) {
          e.preventDefault();
          sendWa(event.event._def.extendedProps.patient, event.event._def.extendedProps.booking_full_date, $(this))
        });
        $('#reminder-logic').removeClass('d-none');
        $('#event-modal #btn-submit').on('click', function(){
            $('#formUpdate').trigger('submit');
        });
        break;
      case 1:
        $('#event-modal #btn-div').html(btn_tindakan);
        $('#event-modal #btn-tindakan').prop('href', event.event._def.extendedProps.url_modal);
        break;
    }
    
    $('#event-modal').modal({
        'backdrop': 'static'
    });
  }

})

function convert(str) {
  var dateObj = new Date(str);
  var momentObj = moment(dateObj);
  return momentObj.format("YYYY-MM-DD HH:mm:ss") ;
}
</script>
@endsection