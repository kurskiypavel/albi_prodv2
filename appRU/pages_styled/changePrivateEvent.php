<?php


// Connection with DB
require_once '../config.php';
require_once '../classes/eventClass.php';

//get id of clicked item from url
$id = $_GET['id'];
//$user = $_GET['user'];
session_start();
$user = $_SESSION['user_id'];

// day of week to restrict booking/changing same day as today
$day_of_week = date('N', strtotime(date("l")));


require_once '../parts/header.php';

// get available schedule
//    instead load actual date and time for this event
$queryPrivate = "SELECT DISTINCT(date) AS date FROM `private-schedule` WHERE `taken` != '1' AND dayOfWeek != $day_of_week GROUP BY date";
$resultPrivate = $conn->query($queryPrivate);
$rowsPrivate = $resultPrivate->num_rows;


$page = $_GET['page'];

$obj = new eventClass($conn);

$queryEvent = "SELECT * FROM events WHERE id='$id'";
$resultEvent = $conn->query($queryEvent);

$rowsEvent = $resultEvent->num_rows;
$objEvent = $resultEvent->fetch_object();


define("OLD_DATE", $objEvent->date);
define("OLD_TIME", $objEvent->time);


if ($_POST) {
    if ($_POST['changePrivateEvent'] != "") {

        // template id for sendgrid email - change template
        $template_id = 'd-ca88b9abf4cc42de84e9aebc90b6af20';


        $date = htmlspecialchars($_POST['date']);
        $time = htmlspecialchars($_POST['time']);
        $comment = htmlspecialchars($_POST['comment']);
        $repeatable = isset($_POST['repeatable']);

        //changePrivateEvent
        $obj->updatePrivate($date, $time, $id, $comment, $repeatable);

        //        book date and time in private_schedule table
        $obj->makeTaken($date, $time);
        //        unbook previous date and time in private_schedule table
        $obj->deleteTaken(OLD_DATE, OLD_TIME);

        //        include_once '../ajax/send-email_private.php';


        echo "<script>location.href = 'programs.php';</script>";


    } elseif ($_POST['deletePrivateEvent'] != "") {

        // template id for sendgrid email - cancel template
        $template_id = 'd-2c01b97dc903476c925377e9f702e34d';

        $date = htmlspecialchars($_POST['date']);
        $time = htmlspecialchars($_POST['time']);

        //deletePrivateEvent
        $obj->delete($id);

        //        unbook previous date and time in private_schedule table
        $obj->deleteTaken(OLD_DATE, OLD_TIME);
        //        include_once '../ajax/send-email_private.php';

        echo "<script>location.href = 'programs.php';</script>";

    }

}


?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Albi | Изменение записи</title>
    <link href="https://cdn.jsdelivr.net/npm/flexiblegrid@v1.2.2/dist/css/flexible-grid.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/styleApp.css">
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
          crossorigin="anonymous">


    <!-- FONTS IMPORT -->
    <script>
        (function (d) {
            var config = {
                    kitId: 'wkb3jgo',
                    scriptTimeout: 3000,
                    async: true
                },
                h = d.documentElement,
                t = setTimeout(function () {
                    h.className = h.className.replace(/\bwf-loading\b/g, "") + " wf-inactive";
                }, config.scriptTimeout),
                tk = d.createElement("script"),
                f = false,
                s = d.getElementsByTagName("script")[0],
                a;
            h.className += " wf-loading";
            tk.src = 'https://use.typekit.net/' + config.kitId + '.js';
            tk.async = true;
            tk.onload = tk.onreadystatechange = function () {
                a = this.readyState;
                if (f || a && a != "complete" && a != "loaded") return;
                f = true;
                clearTimeout(t);
                try {
                    Typekit.load(config)
                } catch (e) {
                }
            };
            s.parentNode.insertBefore(tk, s)
        })(document);
    </script>


</head>

<body style="background: unset;">
<div class="changePrivateEventPage">
    <div class="header">
        <a href='events.php?user=<?php echo $user; ?>'><i class="fas fa-arrow-left"></i></a>
        <h3>Изменение записи</h3>
    </div>


    <form class='form' method="post">

        <?php
        if ($rowsPrivate) {

            echo '<p class="label">День недели</p><select name="date" id="dates">';
            for ($i = 0; $i < $rowsPrivate; ++$i) {
                $resultPrivate->data_seek($i);
                $objPrivate = $resultPrivate->fetch_object();
                echo '<option value="' . $objPrivate->date . '">' . $objPrivate->date . '</option>';
            }
            echo '<option selected value="' . $objEvent->date . '">' . $objEvent->date . '</option></select><span class="error date"></span><p class="label">Время</p><select name="time" id="times"><option value="' . $objEvent->time . '">' . $objEvent->time . '</option></select><span class="error time"></span>';


            ?>
            <p class="label">Комментарий</p><textarea name='comment' type="text"
                                                      value=""></textarea>

            <div class="repeatableDiv">
                <p class="repeatableLabel">Повторные занятия</p>
                <div class="repeatableCheckbox">
                    <input type='checkbox' class='ios8-switch' <?php echo $objEvent->repeatble ? 'checked' : '' ?>
                           name="repeatable" id='checkbox-1'>
                    <!-- get to DB by checked property -->
                    <label for='checkbox-1'></label>
                </div>
            </div>

            <input id="updateEvent" class="button" type="submit" value="Изменить">
            <input type="hidden" id="changeEventIsset" name="changePrivateEvent" value="">
            <input id="deleteEvent" class="cancel" type="submit" value="Отменить занятие">
            <input type="hidden" id="deleteEventIsset" name="deletePrivateEvent" value="">

            <input type="hidden" id="google_cal_id" name="google_cal_id"
                   value="<?php echo $objEvent->google_cal_id; ?>">
            <?php
        } else {
            echo '
            <div class="noPrivate"><span>Сегодня все занято. Завтра могут появиться свободные места.</span><input class="cancel" name="deletePrivateEvent" type="submit" value="Отменить занятие"></div>';

        }
        ?>
    </form>
    <?php include_once '../parts/footer.php' ?>
</div>
<script
        src="//code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

<script>

    //rewrite according to new
    $('input.button').click(function (e) {
        var dateVal = $('#dates').val();
        var timeVal = $('#times').val();


        if (dateVal == null && timeVal == null) {
            e.preventDefault();
            $('.error.date').text('Пожалуйста выберите день недели');
            $('.error.time').text('Пожалуйста выберите время');
            return false;
        } else if (dateVal == null) {
            e.preventDefault();
            $('.error.time').text('');
            $('.error.date').text('Пожалуйста выберите день недели');
            return false;
        } else if (timeVal == null) {
            e.preventDefault();
            $('.error.date').text('');
            $('.error.time').text('Пожалуйста выберите время');
            return false;
        }


    });
</script>

<script>

    //        Pasting data from AJAX
    function succ(data2) {
        var length = data2.length;

        $('#times').html('');
        let eventTime = "<?php echo $objEvent->time;?>";
        for (var i = 0; i < length; ++i) {
            if (eventTime === data2[i].time) {
                $('#times').append('<option selected value="' + data2[i].time + '">' + data2[i].time + '</option>');
            } else {
                $('#times').append('<option value="' + data2[i].time + '">' + data2[i].time + '</option>');
            }

        }


    }
    //    request times according to chosen weekday
    function getList() {

        var chosenDate = $('#dates').val();
        var article = $.ajax({
            type: 'POST',
            url: '../ajax/get-private-schedule.php',
            dataType: "text",
            data: {chosenDate: chosenDate},
            async: true,
            success: function (data) {

                var json = data;
                obj = JSON.parse(json);
                succ(obj);


            },
            error: function () {

                console.log('error');

            }

        });
    }
    //    instead load actual date and time for this event
    $(document).ready(getList);
    $('#dates').change(getList);


</script>

<script async defer src="https://apis.google.com/js/api.js" onload="this.onload=function(){};handleClientLoad()"
        onreadystatechange="if (this.readyState === 'complete') this.onload()"></script>
<script>
    /**
     *   Google API enet management BEGINS
     */

        // Client ID and API key from the Developer Console
    var CLIENT_ID = '412446253370-6k4h35sg8n0353i9qicd2674vbn2lrrm.apps.googleusercontent.com';
    var API_KEY = 'AIzaSyDNJyonJn7LxALbqihYt8oo9y0nHodPMLs';
    // Array of API discovery doc URLs for APIs used by the quickstart
    var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];
    // Authorization scopes required by the API; multiple scopes can be
    // included, separated by spaces.
    var SCOPES = "https://www.googleapis.com/auth/calendar";


    //    var signoutButton = document.getElementById('signout_button');


    /**
     *  On load, called to load the auth2 library and API client library.
     */
    function handleClientLoad() {
        gapi.load('client:auth2', initClient);
    }
    /**
     *  Initializes the API client library and sets up sign-in state
     *  listeners.
     */
    function initClient() {
        gapi.client.init({
            apiKey: API_KEY,
            clientId: CLIENT_ID,
            discoveryDocs: DISCOVERY_DOCS,
            scope: SCOPES
        }).then(function () {
            // Listen for sign-in state changes.
            gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);
            // Handle the initial sign-in state.
            updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
//            signoutButton.onclick = handleSignoutClick;


        }, function (error) {
            appendPre(JSON.stringify(error, null, 2));
        });
    }

    /**
     *  Called when the signed in status changes, to update the UI
     *  appropriately. After a sign-in, the API is called.
     */
    function updateSigninStatus(isSignedIn) {
        console.log('updateSigninStatus');
    }


    /**
     * Append a pre element to the body containing the given message
     * as its text node. Used to display the results of the API call.
     *
     * @param {string} message Text to be placed in pre element.
     */
    function appendPre(message, id) {
        console.log(message + '\n');
    }


    function updateEvent(startDate, startTime, endTime, recurrence) {

        let eventID = $("#google_cal_id").val();

        var event = gapi.client.calendar.events.get({
            "calendarId": 'primary',
            "eventId": eventID
        });

        // Example showing a change in the location - just for testing
//        event.location = "New Address";

        // to change startdate
        event.start= {
                'dateTime': startDate+'T' + startTime
             };
        // to change endtdate
        event.end= {
                'dateTime': startDate+'T' + endTime
            };

        // will be passed as recurrence
        if (recurrence !== '') {
            // to add recurrence
             event.recurrence = [
                recurrence
             ];
        } else {
            // to remove recurrence
             event.recurrence = {};
        }





        var request = gapi.client.calendar.events.patch({
            'calendarId': 'primary',
            'eventId': eventID,
            'resource': event
        });

        request.execute(function (event) {
            console.log('event updated');
            $('#changeEventIsset').val('yes');
                $('form').submit();
        });


    }

    function deleteEvent(e) {
        var eventID = $("#google_cal_id").val();
        if (eventID !== "") {
            e.preventDefault();

            var request = gapi.client.calendar.events.delete({
                calendarId: 'primary',
                eventId: eventID
            });

            request.execute(function (event) {
                console.log('Event deleted with repeate');
                $('#deleteEventIsset').val('yes');
                $('form').submit();
            });
        }


    }

    /**
     *   Google API enet management ENDS
     */

    var today = new Date();

    // UTC days of week numeration
    // Sunday - 0
    //...
    // Saturday - 6
    var utcDayNum = today.getUTCDay();

// Functions block universal use

// Get next occurence date of this day of week related to today - used by private and group
function getNextDayOfWeek(date, dayOfWeek) {
    var resultDate = new Date(date.getTime());
    resultDate.setDate(date.getDate() + (7 + dayOfWeek - date.getUTCDay()) % 7);
    return resultDate;
}

    // Get the day to pass to Google events
    function startDateFinderPrivate(utcDayNum, privateDay, startTime, endTime, recurrence) {
        // if today date dayofweek = privateDayofweek
        if (utcDayNum == privateDay) {
            // pass today as starting date in google events
            let actualMonthNumber = today.getMonth() + 1;
            let startDate = today.getFullYear() + '-' + actualMonthNumber + '-' + today.getUTCDate();
            updateEvent(startDate, startTime, endTime, recurrence);

        } else {
            // if not call getNextDayOfWeek passing today date and program's dayofweek
            // then pass nextOccurence as starting date in google events
            let nextOccurence = getNextDayOfWeek(today, privateDay);
            let actualMonthNumber = nextOccurence.getMonth() + 1;
            let startDate = nextOccurence.getFullYear() + '-' + actualMonthNumber + '-' + nextOccurence.getUTCDate();
            updateEvent(startDate, startTime, endTime, recurrence);

        }
    }


    function parametersSeting() {
        // will be passed as startDate
        var privateWordDay = $('#dates').val();
        var privateDay;
        switch (privateWordDay) {
            case 'воскресенье':
                privateDay = "0";
                break;
            case 'понедельник':
                privateDay = "1";
                break;
            case 'вторник':
                privateDay = "2";
                break;
            case 'среда':
                privateDay = "3";
                break;
            case 'четверг':
                privateDay = "4";
                break;
            case 'пятница':
                privateDay = "5";
                break;
            case 'суббота':
                privateDay = "6";
        }

        // Google calendar BYDAY selection
        // Sunday - SU
        //...
        // Saturday - SA
        var gCalBYDAYPrivate;
        switch (privateWordDay) {
            case 'воскресенье':
                gCalBYDAYPrivate = "SU";
                break;
            case 'понедельник':
                gCalBYDAYPrivate = "MO";
                break;
            case 'вторник':
                gCalBYDAYPrivate = "TU";
                break;
            case 'среда':
                gCalBYDAYPrivate = "WE";
                break;
            case 'четверг':
                gCalBYDAYPrivate = "TH";
                break;
            case 'пятница':
                gCalBYDAYPrivate = "FR";
                break;
            case 'суббота':
                gCalBYDAYPrivate = "SA";
        }

        var timesArr = $('#times').val().split(" - ");
        // will be passed as startTime
        var privateTimeStart = timesArr[0] + ':00';
        // will be passed as endTime
        var privateTimeEnd = timesArr[1] + ':00';
        // will be passed as recurrence
        var privateRecurrence;
        if (document.getElementById('checkbox-1').checked === true) {
            privateRecurrence = 'RRULE:FREQ=WEEKLY;BYDAY=' + gCalBYDAYPrivate + '';
        }

        startDateFinderPrivate(utcDayNum, privateDay, privateTimeStart, privateTimeEnd, privateRecurrence);
    }


    // update event
    $('#updateEvent').click(function (e) {
        // get google event id
        let eventID = $("#google_cal_id").val();

        if (eventID !== "") {
            e.preventDefault();
            parametersSeting();
        }

    });

    // delete event by ID with repeate
    $('#deleteEvent').click(deleteEvent);

</script>
</body>

</html>