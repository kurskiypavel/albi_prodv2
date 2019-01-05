<?php



?>



<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Albi | Войти</title>
    <link href="https://cdn.jsdelivr.net/npm/flexiblegrid@v1.2.2/dist/css/flexible-grid.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/styleApp.css">
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
          crossorigin="anonymous">


</head>
<body style="background: unset;">

<div class="loginPage">
    <div class="header">
        <a id='backHome' href='http://albi.yoga/index.html'><i class="fas fa-arrow-left"></i></a>
        <h3>Войти</h3>
    </div>

    <!--Add buttons to initiate auth sequence and sign out-->
    <a class='loginWith' id="authorize_button" style="display: none;"><img src="assets/images/google_icon.png" alt=""> <p>Продолжить с Google</p></a>
    <button id="signout_button" style="display: none;">Sign Out</button>
    <button class='loginWith' onclick="location.href = 'phone_login.php'" class=''>По номеру телефона</button>

    <pre id="content" style="white-space: pre-wrap;"></pre>

    <p class='dont'>Новый участник?</p>
    <button onclick="location.href = 'register.php'" class='buttonRegister'>Зарегистрироваться</button>

</div>

<script
        src="//code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
<script src='//s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js'></script>
<script src="assets/js/phoneMask.js"></script>

<script type="text/javascript">
    // Client ID and API key from the Developer Console
    var CLIENT_ID = '412446253370-6k4h35sg8n0353i9qicd2674vbn2lrrm.apps.googleusercontent.com';
    var API_KEY = 'AIzaSyDNJyonJn7LxALbqihYt8oo9y0nHodPMLs';
    // Array of API discovery doc URLs for APIs used by the quickstart
    var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];
    // Authorization scopes required by the API; multiple scopes can be
    // included, separated by spaces.
    var SCOPES = "https://www.googleapis.com/auth/calendar";
    var authorizeButton = document.getElementById('authorize_button');
    // hide
    var signoutButton = document.getElementById('signout_button');


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
            authorizeButton.onclick = handleAuthClick;
            // hide
            signoutButton.onclick = handleSignoutClick;


            //            newEvent();
            var insta = gapi.auth2.getAuthInstance();
            if (insta.isSignedIn.get()) {
                var profile = insta.currentUser.get().getBasicProfile();
                // console.log('ID: ' + profile.getId());
                // console.log('Full Name: ' + profile.getName());
                // console.log('Given Name: ' + profile.getGivenName());
                // console.log('Family Name: ' + profile.getFamilyName());
                // console.log('Image URL: ' + profile.getImageUrl());
                // console.log('Email: ' + profile.getEmail());
            }

        }, function (error) {
            appendPre(JSON.stringify(error, null, 2));
        });
    }
    /**
     *  Called when the signed in status changes, to update the UI
     *  appropriately. After a sign-in, the API is called.
     */
    function updateSigninStatus(isSignedIn) {
        if (isSignedIn) {
            
            // authorizeButton.style.display = 'none';
            // signoutButton.style.display = 'block';
            //            listUpcomingEvents();
            var insta = gapi.auth2.getAuthInstance();
            if (insta.isSignedIn.get()) {
                var profile = insta.currentUser.get().getBasicProfile();
                // console.log('ID: ' + profile.getId());
                // console.log('Full Name: ' + profile.getName());
                // console.log('Given Name: ' + profile.getGivenName());
                // console.log('Family Name: ' + profile.getFamilyName());
                // console.log('Image URL: ' + profile.getImageUrl());
                // console.log('Email: ' + profile.getEmail());



                // only for new users
                location.href = 'appRU/pages_styled/looksGood.php?email='+profile.getEmail();
                // only for logged in users
                
            }
        } else {
            authorizeButton.style.display = 'block';
            signoutButton.style.display = 'none';
        }
    }
    /**
     *  Sign in the user upon button click.
     */
    function handleAuthClick(event) {
        gapi.auth2.getAuthInstance().signIn();
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
    function appendPre(message) {
        var pre = document.getElementById('content');
        var textContent = document.createTextNode(message + '\n');
        pre.appendChild(textContent);
    }
    /**
     * Print the summary and start datetime/date of the next ten events in
     * the authorized user's calendar. If no events are found an
     * appropriate message is printed.
     */
    // function listUpcomingEvents() {
    //     gapi.client.calendar.events.list({
    //         'calendarId': 'primary',
    //         'timeMin': (new Date()).toISOString(),
    //         'showDeleted': false,
    //         'singleEvents': true,
    //         'maxResults': 10,
    //         'orderBy': 'startTime'
    //     }).then(function (response) {
    //         var events = response.result.items;
    //         appendPre('Upcoming events:');
    //         if (events.length > 0) {
    //             for (i = 0; i < events.length; i++) {
    //                 var event = events[i];
    //                 var when = event.start.dateTime;
    //                 if (!when) {
    //                     when = event.start.date;
    //                 }
    //                 appendPre(event.summary + ' (' + when + ')')
    //             }
    //         } else {
    //             appendPre('No upcoming events found.');
    //         }
    //     });

    // }


    // call as ajax
    function newEvent() {

        // Refer to the JavaScript quickstart on how to setup the environment:
        // https://developers.google.com/calendar/quickstart/js
        // Change the scope to 'https://www.googleapis.com/auth/calendar' and delete any
        // stored credentials.

        var event = {
            'summary': 'title',
            'location': 'location',
            'description': 'description',
            'start': {
                'dateTime': '2019-01-02T09:00:00-07:00',
                'timeZone': 'America/Los_Angeles'
            },
            'end': {
                'dateTime': '2019-01-02T17:00:00-07:00',
                'timeZone': 'America/Los_Angeles'
            },
            'recurrence': [
                'RRULE:FREQ=DAILY;COUNT=2'
            ],
            'reminders': {
                'useDefault': false,
                'overrides': [{
                    'method': 'email',
                    'minutes': 24 * 60
                },
                    {
                        'method': 'popup',
                        'minutes': 10
                    }
                ]
            }
        };

        var request = gapi.client.calendar.events.insert({
            'calendarId': 'primary',
            'resource': event
        });

        request.execute(function (event) {
            appendPre('Event created: ' + event.htmlLink);
        });

    }
</script>

<script async defer src="https://apis.google.com/js/api.js" onload="this.onload=function(){};handleClientLoad()"
        onreadystatechange="if (this.readyState === 'complete') this.onload()">
</script>


</body>
</html>