<?php $this->load->view('dashboard/menu'); ?>

<link rel="stylesheet" href="<?= base_url("assets/css/font-awesome.min.css"); ?>" />

<link href='<?= base_url("assets/js/fullcalendar/core/main.css");?>' rel='stylesheet' />
<link href='<?= base_url("assets/js/fullcalendar/daygrid/main.css");?>' rel='stylesheet' />
<script src='<?= base_url("assets/js/fullcalendar/core/main.js");?>'></script>
<script src='<?= base_url("assets/js/fullcalendar/interaction/main.js");?>'></script>
<script src='<?= base_url("assets/js/fullcalendar/daygrid/main.js"); ?>'></script>
<script>

    document.addEventListener('DOMContentLoaded', function() {


        var csrfHash    = '<?= $this->security->get_csrf_hash(); ?>',
            csrfName    = $('#csrfTokenName').val(),
            calendarEl  = document.getElementById('calendar');

        var calendar    = new FullCalendar.Calendar(calendarEl, {
            plugins:    [ 'interaction', 'dayGrid' ],
            defaultDate: '<?= date("Y-m-d", time()); ?>',
            editable:   true,
            eventLimit: true,
            events:     [<?= $agenda; ?>],
            selectable: true,
            select:     function(e){
                $("span[data-selected=1]").attr("data-selected", "0");
                $("#addevent").toggleClass('is-active');
                $('html').toggleClass('is-clipped');
                $("#datetoadd").html(e.startStr);
                $("#startdate").html(e.startStr);
                $("#stopdate").html(e.endStr);
                //var t = prompt("Create an event from "+e.startStr+" to "+e.endStr+" (excl).\nEnter a title:");
                //(t||e.view.type.match(/^timeGrid/))&&calendar.unselect(),t&&calendar.addEvent({title:t,start:e.start,end:e.end});
            },
            eventDrop: function(info) {

                if (confirm("Are you sure about this change?")) {
                    var id          = info.event.id,
                        date_start  = new Date(info.event.start.toISOString()),
                        date_stop   = new Date(info.event.end.toISOString()),
                        year1        = date_start.getFullYear(),
                        month1       = date_start.getMonth()+1,
                        dt1          = date_start.getDate(),
                        year2        = date_stop.getFullYear(),
                        month2       = date_stop.getMonth()+1,
                        dt2          = date_stop.getDate();

                    if (dt1 < 10) {
                        dt1 = '0' + dt1;
                    }
                    if (month1 < 10) {
                        month1 = '0' + month1;
                    }
                    var newdate1 = year1+'-' + month1 + '-'+dt1;

                    if (dt2 < 10) {
                        dt2 = '0' + dt2;
                    }
                    if (month2 < 10) {
                        month2 = '0' + month2;
                    }
                    var newdate2 = year2+'-' + month2 + '-'+dt2;
                    
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url("ceo/move_event/"); ?>",
                        data: { 
                            event_id:          id,
                            start_date:        newdate1,
                            stop_date:         newdate2,
                            [csrfName]:        $('#csrfHash').val()
                        },
                        success:function(response)
                        {
                            csrfHash = JSON.parse(response);
                            $('#csrfHash').val(csrfHash.csrfHash);
                        },
                        error: function() 
                        {
                            document.location = "<?= base_url("ceo/agenda"); ?>";
                        }
                    }); 
                } else info.revert();
            }
        });

        calendar.render();

        $(".tag").on('click', function() {
            $(".tag").css("opacity",        "0.5");
            $(".tag").attr("data-selected", "0");
            $(this).attr("data-selected",   "1");
            $(this).css("opacity",          "1");
        });

        $("#buttonevent").on('click', function() {
            event.preventDefault();
            var event_name  = $("#eventname").val(),
                event_color = $("span[data-selected=1]").attr("data-color"),
                event_type  = $("span[data-selected=1]").attr("data-type"),
                start_date  = $("#startdate").html(),
                stop_date   = $("#stopdate").html();

            $.ajax(
                {
                    type: "POST",
                    url: "<?= base_url("ceo/add_event/"); ?>",
                    data: { 
                        event_name:          event_name, 
                        event_type:          event_type, 
                        start_date:          start_date, 
                        stop_date:           stop_date,
                        event_color:         event_color,
                        [csrfName]:          $('#csrfHash').val()
                    },
                    success:function(response)
                    {
                        $("#eventname").html("");
                        $("#addevent").toggleClass('is-active');
                        $('html').toggleClass('is-clipped');
                        calendar.unselect();
                        calendar.addEvent({
                            title:      event_name,
                            start:      start_date,
                            end:        stop_date,
                            color:      event_color
                        });
                        csrfHash = JSON.parse(response);
                        $('#csrfHash').val(csrfHash.csrfHash);
                    },
                    error: function() 
                    {
                        document.location = "<?= base_url("ceo/agenda"); ?>";
                    }
                }
            ); 
        });
    });
</script>

<input id="csrfHash"      type="hidden" value="<?= $this->security->get_csrf_hash(); ?>">
<input id="csrfTokenName" type="hidden" value="<?= $this->security->get_csrf_token_name(); ?>">

<div class="column" style="padding:30px;">
    <div id="calendar"></div>

    <div class="modal" id="addevent">
        <div class="modal-background close-modal" data-modal-id="#addevent"></div>
        <div class="modal-content">
            <div class="card">
                <div class="card-content">
                    <div class="content">
                        <div class="columns">
                            <div class="column is-10">
                                <h3 class="subtitle">
                                    Add an event on <span id="datetoadd"></span>
                                </h3>
                                <span style="display:none" id="startdate"></span>
                                <span style="display:none" id="stopdate"></span>
                                <br>
                                <label class="label">Event name</label>
                                <input class="input" id="eventname" placeholder="Event name">
                                <br><br>
                                <label class="label">Label</label>
                                <div class="columns">
                                    <div class="column">
                                        <span class="tag is-success" data-type="meeting" data-color="#23d160">Meeting</span>
                                    </div>
                                    <div class="column">
                                        <span class="tag is-warning" data-type="todo" data-color="#ffdd57">Todo</span>
                                    </div>
                                    <div class="column">
                                        <span class="tag is-danger" data-type="important" data-color="#ff3860">Important</span>
                                    </div>
                                    <div class="column">
                                        <span class="tag is-info" data-type="info" data-color="#209cee">Info</span>
                                    </div>
                                </div>
                                <br><br>
                                <a href="javascript:void(0)" class="button is-info" id="buttonevent">Add event</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="modal-close close-modal is-large" data-modal-id="#addevent" aria-label="close"></button>
    </div>
</div>

<?php $this->load->view('dashboard/footer'); ?>