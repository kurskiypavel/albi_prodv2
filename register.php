<?php

//select new email in DB
// if new - redirect to looks good page
// else redirect to programs/events



        //only new redirect to looks good
        
 
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


    function handleClientLoad() {
        gapi.load('client:auth2', initClient);
    }

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
                // console.log('Email: ' + profile.getEmail());
            }

        });
    }

    function updateSigninStatus(isSignedIn) {
        if (isSignedIn) {
            
            var insta = gapi.auth2.getAuthInstance();
            if (insta.isSignedIn.get()) {
                var profile = insta.currentUser.get().getBasicProfile();              
                
            }
        } else {
            authorizeButton.style.display = 'block';
            signoutButton.style.display = 'none';
        }
    }

    function handleAuthClick(event) {
        gapi.auth2.getAuthInstance().signIn();
    }

    function handleSignoutClick(event) {
        gapi.auth2.getAuthInstance().signOut();
    }


    // ajax to check google user
    function checkUser(email){
        
    }
</script>

<script async defer src="https://apis.google.com/js/api.js" onload="this.onload=function(){};handleClientLoad()"
        onreadystatechange="if (this.readyState === 'complete') this.onload()">
</script>


</body>
</html>