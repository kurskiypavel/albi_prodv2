<?php

// Connection with DB
require_once '../config.php';
require_once '../classes/eventClass.php';

//get id of clicked item from url
$program = $_GET['program'];
$student = $_GET['student'];
$instructor = '1';
$page = $_GET['page'];
//$user = $_GET['user'];
session_start();
$user = $_SESSION['user_id'];


require_once '../parts/header.php';


$obj = new eventClass($conn);

if ($_POST) {
    if (isset($_POST['createGroupEventB'])) {

        $comment = htmlspecialchars($_POST['comment']);
        $google_cal_id = $_POST['google_cal_id'];
        $group_event_id = $program;

        //createGroupEvent
        $obj->createGroupEvent($group_event_id, $program,
            $student, $instructor, $comment, $google_cal_id);

//        include_once '../ajax/send-email_group.php';

        if ($page == 'programs') {
            echo "<script>location.href = 'programs.php?user=" . $user . "';</script>";
        } elseif ($page == 'program') {
            echo "<script>location.href = 'program.php?user=" . $user . "&id=" . $program . "';</script>";

        }

    }

}

//Get available schedule for this program
$query = "SELECT schedule,title,gWeekDay,startTime,endTime FROM programs WHERE id='$program'";
$result = $conn->query($query);
$rows = $result->num_rows;
$obj = $result->fetch_all();


$schedule = $obj[0][0];
$title = $obj[0][1];
$gWeekDay = $obj[0][2];
$startTime = $obj[0][3];
$endTime = $obj[0][4];


?>
    <!DOCTYPE html>
    <html lang="ru">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Albi | Записаться в группу</title>
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
<div class="bookGroupEventPage">
    <div class="header">
        <?php
        if ($page == 'programs') {
            echo "<a href='programs.php?user=$user'><i class=\"fas fa-arrow-left\"></i></a>";
        } elseif ($page == 'program') {
            echo "<a href='program.php?user=$user&id=$program'><i class=\"fas fa-arrow-left\"></i></a>";
        }

        ?>

        <h3>Записаться в группу</h3>
    </div>

    <form class='form' method="post">
        <!--            <p class="label">Дата</p>-->
        <!--            <input class="gray" name='date' type="date">-->

        <!--            <p class="label">Время</p>-->
        <!--            <input class="gray" name='time' type="time">-->
        <p class="schedule">Занятия проходят по <?php echo $schedule; ?></p>


        <p class="label">Комментарий</p><textarea placeholder="<?php if ($schedule == 'записи') {
            echo 'Спросите о времени и месте занятий тут';
        } ?>" class="" name='comment' type="text"></textarea>

        <input id="createEvent" class="button" type="submit" value="Готово">
        <input type="hidden" id="createEventIsset" name="createGroupEventB" value="">
        <input type="hidden" id="google_cal_id" name="google_cal_id" value="">
    </form>
    <?php include_once '../parts/footer.php' ?>
</div>

<!--<div style="margin-top: 30px; height: 100px;" id='signout_button' class="logout"><a href="../ajax/logout.php">Выйти</a></div>-->
<script
        src="//code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
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
        $('.loader').css('opacity','0');
        console.log('updateSigninStatus');
        $('.loader').css({'visibility':'hidden','z-index':'0'});
    }

    /**
     *  Sign out the user upon button click.
     */
    function handleSignoutClick(event) {
        gapi.auth2.getAuthInstance().signOut();
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
    function newEvent(title, startDate, startTime, endTime, recurrence) {

        // Refer to the JavaScript quickstart on how to setup the environment:
        // https://developers.google.com/calendar/quickstart/js
        // Change the scope to 'https://www.googleapis.com/auth/calendar' and delete any
        // stored credentials.


            var event = {
                'summary': title,
                'location': 'Москва',
                'description': 'Групповое занятие по йоге с Альбиной Курской',
                'start': {
                    // dateTime = day of week of program ->find when is next date
                    // concat date and time from function input startDate startTime
                    // 'dateTime': '2019-03-08T12:00:00',
                    'dateTime': startDate + 'T' + startTime,
                    'timeZone': 'Europe/Moscow'
                },
                'end': {
                    // end date same as start date
                    // concat date and time from function input endTime
                    // 'dateTime': '2019-03-08T13:00:00',
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


    /**
     *   Get the start date for group event BEGINS
     */

    // Constants block

    var today = new Date();


    // Programs constants

    // воскресеньям - 0
    // ...
    // субботам - 6

    // get program dayOfWeek
    var gCalBYDAY = '<?php echo $gWeekDay; ?>';
    var programDay;
    switch (gCalBYDAY) {
        case 'SU':
            programDay = "0";
            break;
        case 'MO':
            programDay = "1";
            break;
        case 'TU':
            programDay = "2";
            break;
        case 'WE':
            programDay = "3";
            break;
        case 'TH':
            programDay = "4";
            break;
        case 'FR':
            programDay = "5";
            break;
        case 'SA':
            programDay = "6";
    }

    // UTC days of week numeration
    // Sunday - 0
    //...
    // Saturday - 6
    var utcDayNum = today.getUTCDay();

    var startTime = '<?php echo $startTime; ?>';
    var endTime = '<?php echo $endTime; ?>';
    var recurrence = 'RRULE:FREQ=WEEKLY;BYDAY=' + gCalBYDAY + '';
    var title = '<?php echo $title; ?>';
    // Functions block universal use

    // Get next occurence date of this day of week related to today - used by private and group
    function getNextDayOfWeek(date, dayOfWeek) {
        var resultDate = new Date(date.getTime());
        resultDate.setDate(date.getDate() + (7 + dayOfWeek - date.getUTCDay()) % 7);
        return resultDate;
    }


    // Functions block programs

    // Get the day to pass to Google events
    function startDateFinder(utcDayNum, programDay, startTime, endTime, recurrence) {
        // if today date dayofweek = programdayofweek
        if (utcDayNum == programDay) {
            // pass today as starting date in google events
            let actualMonthNumber = today.getMonth() + 1;
            let startDate = today.getFullYear() + '-' + actualMonthNumber + '-' + today.getUTCDate();
            newEvent(title,startDate, startTime, endTime, recurrence);

        } else {
            // if not call getNextDayOfWeek passing today date and program's dayofweek
            // then pass nextOccurence as starting date in google events
            let nextOccurence = getNextDayOfWeek(today, programDay);
            let actualMonthNumber = nextOccurence.getMonth() + 1;
            let startDate = nextOccurence.getFullYear() + '-' + actualMonthNumber + '-' + nextOccurence.getUTCDate();
            newEvent(title,startDate, startTime, endTime, recurrence);

        }
    }


    // Event listeners block group

    // create group event
    var schedule = '<?php echo $schedule;?>';
    $('#createEvent').click(function (e) {
        if(schedule !== 'записи'){
            e.preventDefault();
            $('.loader').css({'visibility':'visible','z-index':'2','opacity':'1','background':'#fffc'});
            startDateFinder(utcDayNum, programDay, startTime, endTime, recurrence);
        }

    });



    /**
     *   Get the start date for group event ENDS
     */
</script>
</body>

</html>