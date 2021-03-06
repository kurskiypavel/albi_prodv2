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
        signoutButton.onclick = handleSignoutClick;



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
    $("#eventID").val(id);

}

// call as ajax
function newEvent(startDate, startTime, endTime, recurrence) {
    console.log(gCalBYDAY);


    // Refer to the JavaScript quickstart on how to setup the environment:
    // https://developers.google.com/calendar/quickstart/js
    // Change the scope to 'https://www.googleapis.com/auth/calendar' and delete any
    // stored credentials.

    if (recurrence !== '') {
        var event = {
            'summary': 'Program Name',
            'location': 'location',
            'description': 'Short description for program',
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

    } else {
        var event = {
            'summary': 'Program Name',
            'location': 'location',
            'description': 'Short description for program',
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
    });

}

function updateEvent() {
    // get google event id
    var eventID = $("#eventID").val();
    var event = gapi.client.calendar.events.get({
        "calendarId": 'primary',
        "eventId": eventID
    });

    // Example showing a change in the location
    event.location = "New Address";

    // to change endtdate
    /*event.end= {
     'dateTime': '2019-03-13T14:00:00+03:00'
     };*/

    // to change startdate
    /* event.start= {
     'dateTime': '2019-03-13T14:00:00+03:00'
     };*/

    // to remove recurrence
    // event.recurrence = {};

    // to add recurrence
    // event.recurrence = [
    //     'RRULE:FREQ=WEEKLY;BYDAY=SA'
    // ];

    var request = gapi.client.calendar.events.patch({
        'calendarId': 'primary',
        'eventId': eventID,
        'resource': event
    });

    request.execute(function (event) {
        console.log(event);
        console.log('event updated');

    });
}

function deleteEvent() {

    var eventID = $("#eventID").val();
    var request = gapi.client.calendar.events.delete({
        calendarId: 'primary',
        eventId: eventID
    });

    request.execute();
    console.log('Event deleted with repeate');

}


$('#updateEvent').click(updateEvent);



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
let programWordDay = 'средам';
var programDay;
switch (programWordDay) {
    case 'воскресеньям':
        programDay = "0";
        break;
    case 'понедельникам':
        programDay = "1";
        break;
    case 'вторникам':
        programDay = "2";
        break;
    case 'средам':
        programDay = "3";
        break;
    case 'четвергам':
        programDay = "4";
        break;
    case 'пятницам':
        programDay = "5";
        break;
    case 'субботам':
        programDay = "6";
}

// UTC days of week numeration
// Sunday - 0
//...
// Saturday - 6
var utcDayNum = today.getUTCDay();

// Google calendar BYDAY selection
// Sunday - SU
//...
// Saturday - SA
var gCalBYDAY;
switch (programWordDay) {
    case 'воскресеньям':
        gCalBYDAY = "SU";
        break;
    case 'понедельникам':
        gCalBYDAY = "MO";
        break;
    case 'вторникам':
        gCalBYDAY = "TU";
        break;
    case 'средам':
        gCalBYDAY = "WE";
        break;
    case 'четвергам':
        gCalBYDAY = "TH";
        break;
    case 'пятницам':
        gCalBYDAY = "FR";
        break;
    case 'субботам':
        gCalBYDAY = "SA";
}


var startTime = '12:00:00';
var endTime = '13:00:00';
var recurrence = 'RRULE:FREQ=WEEKLY;BYDAY=' + gCalBYDAY + '';



// Private constants

// will be passed as startDate
var privateWordDay = 'суббота';
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

// will be passed as startTime
var privateTimeStart = '17:00:00';;
// will be passed as endTime
var privateTimeEnd = '18:00:00';
// will be passed as recurrence
// var privateRecurrence = 'RRULE:FREQ=WEEKLY;BYDAY=' + gCalBYDAYPrivate + '';
var privateRecurrence;


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
        newEvent(startDate, startTime, endTime, recurrence);

    } else {
        // if not call getNextDayOfWeek passing today date and program's dayofweek
        // then pass nextOccurence as starting date in google events
        let nextOccurence = getNextDayOfWeek(today, programDay);
        let actualMonthNumber = nextOccurence.getMonth() + 1;
        let startDate = nextOccurence.getFullYear() + '-' + actualMonthNumber + '-' + nextOccurence.getUTCDate();
        newEvent(startDate, startTime, endTime, recurrence);

    }
}


// Event listeners block group

// create group event
$('#createEvent').click(function () {
    startDateFinder(utcDayNum, programDay, startTime, endTime, recurrence);
});

// create event by ID with repeate
$('#deleteEvent').click(deleteEvent);



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
$('#createPrivateEvent').click(function () {
    startDateFinderPrivate(utcDayNum, privateDay, privateTimeStart, privateTimeEnd, privateRecurrence);
});

/**
 *   Get the start date for group event ENDS
 */
