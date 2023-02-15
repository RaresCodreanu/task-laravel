<?php
require_once '..\resources\library.php';
$userId = Auth::id();
?>

<link rel="stylesheet" type="text/css" href="{{ url('/css/style.css') }}" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">

<script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<!-- fullcalendar -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.1/index.global.min.js'></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Appointment calendar') }}
        </h2>
    </x-slot>

    <div id="calendar"></div>

    <!-- schedule modal -->
    <div class="modal" tabindex="-1" role="dialog" id="add-modal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Schedule event</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
                <label for="event_name" class="col-lg-3 col-form-label">Event name</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" name="event_name" id="event_name">
                </div>
            </div>
            <div class="form-group row">
                <label for="event_date" class="col-lg-3 col-form-label">Date</label>
                <div class="col-lg-9">
                  <input type="text" class="form-control datepicker" name="event_date" id="event_date" readonly="readonly">
                </div>
            </div>
            <div class="form-group row">
                <label for="event_slot" class="col-lg-3 col-form-label">Event start</label>
                <div class="col-lg-9">
                    <select class="form-control" name="event_slot" id="event_slot" style="width: 100%;">
                    <?php
                      //first array of time ranges
                      //9 am = 32400
                      //12 pm = 43200
                      $first_range = hoursRange(  32400, 43200, 60 * 30, 'H:i:s'  );

                      //second array of time ranges
                      //3:30 pm = 55800
                      //8 pm = 72000
                      $second_range = hoursRange(  55800, 72000, 60 * 30, 'H:i:s'  );

                      $all_ranges = array();
                      $all_ranges = array_merge($first_range, $second_range);
                      // Log::info($all_ranges)
                      foreach($all_ranges as $value => $text){
                        echo "<option value='{$value}'>{$text}</option>";
                      }
                    ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="event_duration" class="col-lg-3 col-form-label">Duration</label>
                <div class="col-lg-9">
                  <input type="text" disabled class="form-control" name="event_duration" id="event_duration" value="1 hour">
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn" id="save-event">Save</button>
            <button type="button" class="btn" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- delete modal -->
    <div class="modal" tabindex="-1" role="dialog" id="delete-event-modal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Delete event</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Ares you sure yo want to delete this event?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn" id="confirm-delete">Delete</button>
            <button type="button" class="btn" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

</x-app-layout> 
<script>
     let user_id = <?php echo $userId; ?>;

     let event_id;

     let calendar, calendar_events = [];
     document.addEventListener('DOMContentLoaded', function() {

      $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
      });

        let calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
          events:
            <?php
            $appoitments = array();
            array_push($appoitments, $data);
            $appoitments = array_unique($appoitments);
            foreach($appoitments as $d){
              echo $d;
            };
            ?>
          ,
          headerToolbar: { center: 'dayGridMonth,timeGridWeek,timeGridDay', left: 'add_event_button' },
          businessHours: [
            {
              daysOfWeek: [ 1, 2, 3, 4, 5 ],
              startTime: '09:00', // 9am
              endTime: '13:00' // 1pm
            },
            {
              daysOfWeek: [ 1, 2, 3, 4, 5 ],
              startTime: '15:30', // 3:30pm
              endTime: '21:00' // 9pm
            }
          ],
          customButtons: {
              add_event_button: {
                  text: 'Click to schedule appointment',
                  click: function(info) {
                      $('#add-modal').modal('show');
                      $('#delete-btn-modal-opener').hide();
                  }
              }
          },
          initialView: 'dayGridMonth',
          locale: 'ro',
          editable: false,
          selectable: true,
          dateClick: function(info) {
            calendar.changeView('timeGridDay',info.dateStr);
          },
          eventClick: function(info) {
            $('#delete-event-modal').modal('show');
            event_id = info.event.id;
          }
        });
        calendar.render();

      });

      $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        startDate: "today",
        daysOfWeekDisabled: [0,6]
      });

      $('body').on('click', '#save-event', function(){
        let event_name    = $('#event_name').val(),
            event_date    = $('#event_date').val(),
            event_start   = $('#event_slot').val(),
            event_end;

        let first_value = event_start.substring(0, event_start.indexOf(":"));
        first_value = parseInt(first_value, 10);

        let second_value = event_start.substr(event_start.indexOf(":") + 1);
        second_value = parseInt(second_value, 10);

        if(first_value == 9 && second_value == 0){
          event_end = '10:00'
        } else if(first_value == 9 && second_value == 30){
          event_end = '10:30'
        } else if(first_value != 9 && second_value == 0){
          first_value += 1;
          event_end = ''+first_value+':00'
        } else if(first_value != 9 && second_value != 0){
          first_value += 1;
          event_end = ''+first_value+':30'
        }

        if(event_name != '' && event_date != '' && event_start != ''){
          $.ajax({
              url:"/calendar/action",
              type:"POST",
              data:{
                  event_name: event_name,
                  event_date: event_date,
                  event_start: ''+event_date+'T'+event_start+':00',
                  event_end: ''+event_date+'T'+event_end+':00',
                  user_id: user_id,
                  type: 'add'
              },
              success:function(data)
              {
                  calendar.refetchEvents();
                  window.location.reload();
              }
          })
        }
      })

      //delete event
      $('body').on('click', '#confirm-delete', function(){
        let id = event_id;

        $.ajax({
          url:"/calendar/action",
          type:"POST",
          data:{
              id: id,
              type: 'delete'
          },
          success:function(data)
          {
              calendar.refetchEvents();
              window.location.reload();
          }
        })
      })
</script>