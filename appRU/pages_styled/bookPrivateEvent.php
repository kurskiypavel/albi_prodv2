<?php

// Connection with DB
require_once '../config.php';
require_once '../classes/eventClass.php';

//get id of clicked item from url
$student = $_GET['student'];
$instructor = '1';
//$user = $_GET['user'];
session_start();
$user = $_SESSION['user_id'];

// template id for sendgrid email - new event template
$template_id = 'd-155a815710dd438e9cbe3fbdfa57e2c3';

// day of week to restrict booking same day as today
$day_of_week = date('N', strtotime(date("l")));

require_once '../parts/header.php';

// get available schedule
$queryPrivate = "SELECT DISTINCT(date) AS date FROM `private-schedule` WHERE `taken` != '1' AND dayOfWeek != $day_of_week  GROUP BY date";
$resultPrivate = $conn->query($queryPrivate);
$rowsPrivate = $resultPrivate->num_rows;

$obj = new eventClass($conn);

if ($_POST) {
    if (isset($_POST['createPrivateEventB'])) {
        $date = htmlspecialchars($_POST['date']);
        $time = htmlspecialchars($_POST['time']);
        $comment = htmlspecialchars($_POST['comment']);
        $google_cal_id = $_POST['google_cal_id'];
        $repeatable = isset($_POST['repeatable']);

        //createPrivateEvent
        $obj->createPrivateEvent(
            $student, $instructor,
            $date, $time, $comment,
            $repeatable,$google_cal_id);
        $obj->makeTaken($date, $time);


//        include_once '../ajax/send-email_private.php';
//        echo "<script>location.href = 'instructor.php?user=" . $user . "&id=" . $instructor . "';</script>";
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
    <title>Albi | Взять частный урок</title>
    <link rel="icon" href="../../assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/loader.css">
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
<div  class="loader">
    <svg viewBox="0 0 100 150">
        <g>
            <path d="M 50,100 A 1,1 0 0 1 50,0"/>
        </g>
        <g>
            <path d="M 50,75 A 1,1 0 0 0 50,-25"/>
        </g>
        <defs>
            <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" style="stop-color:#FF56A1;stop-opacity:1"/>
                <stop offset="100%" style="stop-color:#FF9350;stop-opacity:1"/>
            </linearGradient>
        </defs>
    </svg>
</div>
<div class="bookPrivateEventPage">
    <div class="header">
        <a href='instructor.php?user=<?php echo $user; ?>&id=1'><i class="fas fa-arrow-left"></i></a>
        <h3>Взять частный урок</h3>
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
            echo <<<HEREFORM
                </select>
                <span class="error date"></span>
                <p class="label">Время</p>
                <select name="time" id="times"></select>
                <span class="error time"></span>
                <p class="label">Комментарий</p>
                <textarea class="" name='comment' type="text" value=""></textarea>
                <div class="repeatableDiv">
                    <p class="repeatableLabel">Повторные занятия</p>
                    <div class="repeatableCheckbox">
                        <input type='checkbox' class='ios8-switch' name="repeatable" id='checkbox-1'>
                        <!-- get to DB by checked property -->
                        <label for='checkbox-1'></label>
                    </div>
                </div>
                <input id="createPrivateEvent" class="button" type="submit" value="Готово">
                <input type="hidden" id="createEventIsset" name="createPrivateEventB" value="">
                <input type="hidden" id="google_cal_id" name="google_cal_id" value="">
HEREFORM;
        } else {
            echo '<div class="noPrivate">Сегодня все занято. Завтра могут появиться свободные места.</div>';
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


    })
</script>
<script>

    //        Pasting data from AJAX
    function succ(data2) {
        var length = data2.length;

        $('#times').html('');
        for (var i = 0; i < length; ++i) {
            $('#times').append('<option value="' + data2[i].time + '">' + data2[i].time + '</option>');
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
    $(document).ready(getList);
    $('#dates').change(getList);


</script>

<script async defer src="https://apis.google.com/js/api.js" onload="this.onload=function(){};handleClientLoad()"
        onreadystatechange="if (this.readyState === 'complete') this.onload()"></script>

<script>
    /**
     * Created by pavelkurskiy on 2019-03-10.
     */

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


        }, function (error) {
            appendPre(JSON.stringify(error, null, 2));
        });
    }

    /**
     *  Called when the signed in status changes, to update the UI
     *  appropriately. After a sign-in, the API is called.
     */
    function updateSigninStatus(isSignedIn) {
        $('.loader').css('opacity','0');
        console.log('updateSigninStatus');
        $('.loader').css({'visibility':'hidden','z-index':'0'});
    }


    /**
     * Append a pre element to the body containing the given message
     * as its text node. Used to display the results of the API call.
     *
     * @param {string} message Text to be placed in pre element.
     */
    function appendPre(message, id) {
        // var pre = document.getElementById('content');
        // var textContent = document.createTextNode(message + '\n');
        // pre.appendChild(textContent);
        console.log(message + '\n');
        $("#google_cal_id").val(id);

    }

    // call as ajax
    function newEvent(startDate, startTime, endTime, recurrence) {

        // Refer to the JavaScript quickstart on how to setup the environment:
        // https://developers.google.com/calendar/quickstart/js
        // Change the scope to 'https://www.googleapis.com/auth/calendar' and delete any
        // stored credentials.

        if (recurrence !== '') {
            var event = {
                'summary': 'Частный урок йоги',
                'location': 'Москва',
                'description': 'Частный урок йоги с Альбиной Курской',
                'start': {
                    'dateTime': startDate + 'T' + startTime,
                    'timeZone': 'Europe/Moscow'
                },
                'end': {
                    'dateTime': startDate + 'T' + endTime,
                    'timeZone': 'Europe/Moscow'
                },
                'recurrence': [
                    // 'RRULE:FREQ=WEEKLY;BYDAY='+gCalBYDAY+''
                    recurrence

                ],
                'reminders': {
                    "useDefault": false,
                    "overrides": [{
                        "method": "popup",
                        "minutes": 120
                    }]
                }
            };

        } else {
            var event = {
                'summary': 'Частный урок йоги',
                'location': 'Москва',
                'description': 'Частный урок йоги с Альбиной Курской',
                'start': {
                    'dateTime': startDate + 'T' + startTime,
                    'timeZone': 'Europe/Moscow'
                },
                'end': {
                    'dateTime': startDate + 'T' + endTime,
                    'timeZone': 'Europe/Moscow'
                },
                'recurrence': [],
                'reminders': {
                    "useDefault": false,
                    "overrides": [{
                        "method": "popup",
                        "minutes": 120
                    }]
                }
            };

        }


        var request = gapi.client.calendar.events.insert({
            'calendarId': 'primary',
            'resource': event
        });

        request.execute(function (event) {
            appendPre('Event created: ' + event.htmlLink, event.id);
            $('#createEventIsset').val('yes');
            $('form').submit();
        });

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

    // Set day of week on form submit

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
//     var privateRecurrence = 'RRULE:FREQ=WEEKLY;BYDAY=' + gCalBYDAYPrivate + '';
        var privateRecurrence;
        if (document.getElementById('checkbox-1').checked === true) {
            privateRecurrence = 'RRULE:FREQ=WEEKLY;BYDAY=' + gCalBYDAYPrivate + '';
        }

        startDateFinderPrivate(utcDayNum, privateDay, privateTimeStart, privateTimeEnd, privateRecurrence);
    }


    // Functions block universal use

    // Get next occurence date of this day of week related to today - used by private and group
    function getNextDayOfWeek(date, dayOfWeek) {
        var resultDate = new Date(date.getTime());
        resultDate.setDate(date.getDate() + (7 + dayOfWeek - date.getUTCDay()) % 7);
        return resultDate;
    }


    // Functions block private

    // Event listeners block private

    // Get the day to pass to Google events
    function startDateFinderPrivate(utcDayNum, privateDay, startTime, endTime, recurrence) {
        // if today date dayofweek = privateDayofweek
        if (utcDayNum == privateDay) {
            // pass today as starting date in google events
            let actualMonthNumber = today.getMonth() + 1;
            let startDate = today.getFullYear() + '-' + actualMonthNumber + '-' + today.getUTCDate();
            newEvent(startDate, startTime, endTime, recurrence);

        } else {
            // if not call getNextDayOfWeek passing today date and program's dayofweek
            // then pass nextOccurence as starting date in google events
            let nextOccurence = getNextDayOfWeek(today, privateDay);
            let actualMonthNumber = nextOccurence.getMonth() + 1;
            let startDate = nextOccurence.getFullYear() + '-' + actualMonthNumber + '-' + nextOccurence.getUTCDate();
            newEvent(startDate, startTime, endTime, recurrence);

        }
    }


    // create private event
    $('#createPrivateEvent').click(function (e) {
        e.preventDefault();
        $('.loader').css({'visibility':'visible','z-index':'2','opacity':'1','background':'#fffc'});
        parametersSeting();
    });

    /**
     *   Get the start date for group event ENDS
     */

</script>

</body>

</html>