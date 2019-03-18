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
// var signoutButton = document.getElementById('signout_button');


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
        // signoutButton.onclick = handleSignoutClick;



        var insta = gapi.auth2.getAuthInstance();
        if (insta.isSignedIn.get()) {
            var profile = insta.currentUser.get().getBasicProfile();

            console.log('initClient');
            
            
        }

    }, function (error) {
        // appendPre(JSON.stringify(error, null, 2));
        console.log(JSON.stringify(error, null, 2));
        
    });
}

/**
 *  Called when login to start session
 */


 function startSession(id){
    $.ajax({
        type: "POST",
        url: "../appRU/ajax/startSessionGAPI.php",
        data: {
            id: id
        },success: function (data) {
            // redirect to programs
            location.href = '/appRU/pages_styled/programs.php';
            
        }
    });
 }

/**
 *  Called when signedIN to check user exist
 */
function checkUser(googleID,getGivenName,getFamilyName,getEmail) {
    // console.log(googleID);
    // ajax checkGoogleUser.php
    $.ajax({
        type: "GET",
        url: "../appRU/ajax/checkGoogleUser.php",
        data: {
            googleID: googleID
        },
        success: function (data) {
            if (!data){
                location.href = '/appRU/pages_styled/looksGood.php?googleID='+googleID+'&getFamilyName='+getFamilyName
                +'&getGivenName='+getGivenName+'&getEmail='+getEmail;
                console.log('!data - new user');
                
            } else {
                // login user
                // session start with google id
                console.log(data);
                
                startSession(data);
            }
        }
    });
}
/**
 *  Called when the signed in status changes, to update the UI
 *  appropriately. After a sign-in, the API is called.
 */
function updateSigninStatus(isSignedIn) {
    if (isSignedIn) {

        var insta = gapi.auth2.getAuthInstance();
        if (insta.isSignedIn.get()) {
            var profile = insta.currentUser.get().getBasicProfile();
            let googleID = profile.getId();
            // console.log('Full Name: ' + profile.getName());
            let getGivenName= profile.getGivenName();
            let getFamilyName=profile.getFamilyName();
            // console.log('Image URL: ' + profile.getImageUrl());
            let getEmail= profile.getEmail();
            
            console.log('updateSigninStatus');
            
            checkUser(googleID,getGivenName,getFamilyName,getEmail);

        }
    } else {
        // authorizeButton.style.display = 'block';
        // signoutButton.style.display = 'none';
        // $('.loader').css({'opacity':'0','display':'none'});
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
// function handleSignoutClick(event) {
//     gapi.auth2.getAuthInstance().signOut();
// }
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
