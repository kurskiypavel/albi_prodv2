<?php

// fistname
// last name
// phone
//mail
//restrict marketing

// Google Auth values
$googleID = htmlspecialchars($_GET['googleID']);
$getGivenName = htmlspecialchars($_GET['getGivenName']);
$getFamilyName = htmlspecialchars($_GET['getFamilyName']);
$getEmail = htmlspecialchars($_GET['getEmail']);


// Connection with DB
require_once '../config.php';
// define variables and initialize with empty values
$email = $email_err = "";

// processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Пожалуйста введите email";
    } else {
        // prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";

        if ($stmt = $conn->prepare($sql)) {
            // bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            // set parameters
            $param_email = trim($_POST["email"]);

            // attempt to execute the prepared statement
            if ($stmt->execute()) {
                // store result
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $email_err = "Этот email уже используется";
                    echo $email_err;
                    die();
                } else {
                    $email = trim($_POST["email"]);
                }
            }
        }
        // close statement
        $stmt->close();
    }


    // set parameters

    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $phone = trim($_POST["phone"]);
    $noMail = isset($_POST['noMail']);
    $googleID = trim($_POST["googleID"]);

    // check input errors before inserting in database
    if (empty($email_err)) {


        // prepare an insert statement
        $sql = "INSERT INTO users (first_name,last_name, email, phone, noMail,googleID) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssss", $first_name, $last_name, $email, $phone, $noMail, $googleID);


            // attempt to execute the prepared statement
            if ($stmt->execute()) {
                //get new user id

                // prepare a select statement
                $sql = "SELECT id FROM users WHERE googleID = ?";

                if ($stmt = $conn->prepare($sql)) {
                    // bind variables to the prepared statement as parameters
                    $stmt->bind_param("s", $googleID);


                    // attempt to execute the prepared statement
                    if ($stmt->execute()) {
                        // store result
                        $stmt->store_result();

                        // check if phone exists, if yes then verify password
                        if ($stmt->num_rows == 1) {
                            // bind result variables
                            $stmt->bind_result($user_id);

                            if ($stmt->fetch()) {
                                //check if session

                                if (!$_SESSION['user_id']) {
                                    //new session
                                    session_start();
                                    $_SESSION['user_id'] = $user_id;
                                }
                                // redirect to home page
                                echo "<script>location.href = 'programs.php';</script>";
                            }
                        }
                    }
                }
            }
        }

        // close statement
        $stmt->close();
    }

// close connection
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Albi | Все верно?</title>
    <link rel="icon" href="../../assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/styleApp.css">
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
          crossorigin="anonymous">
</head>
<body class="looksGood">
<div class="header">
    <a id='signout_button' href='/'><i class="fas fa-arrow-left"></i></a>
    <h3>Все верно?</h3>
    <p>Убедитесь, что данные заполнены</p>
    <p>корректно прежде чем продолжить.</p>
</div>

<form class='form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <ul class="settingsContent">
        <li>
            <div class="subContent">
                <span class="bold">Имя</span>
                <input type="text" name="first_name"
                       value='<?php echo $getGivenName == "undefined" ? "" : $getGivenName; ?>'>
            </div>
        </li>
        <li>
            <div class="subContent">
                <span class="bold">Фамилия</span>
                <input type="text" name="last_name"
                       value='<?php echo $getFamilyName == "undefined" ? "" : $getFamilyName; ?>'>
            </div>
        </li>
        <li>
            <div class="subContent">
                <input hidden type="text" name="googleID" value='<?php echo $googleID; ?>'>
                <span class="bold">Телефон</span>
                <input type="tel" id='yourphone2' name="phone" placeholder="—">
            </div>
        </li>
        <li>
            <div class="subContent">
                <span class="bold">Email</span>
                <input type="text" name="email" value='<?php echo $getEmail; ?>'>
            </div>
        </li>

    </ul>
    <div class="offerBlock">
        <p class="offer">Мы будем высылать вам маркетинговые предложения по электронной почте.</p>
        <div class="allowSection">
            <p class="offer">Я не хочу получать маркетинговые сообщения от Albi.</p>
            <div class="toogle">
                <input type='checkbox' class='ios8-switch' name="noMail" id='checkbox-1'>
                <label for='checkbox-1'></label>
            </div>
        </div>
    </div>


    <button class='buttonRegister' type="submit">Зарегистрироваться</button>
</form>
<script
        src="//code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
<script src='//s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js'></script>
<script src="../../assets/js/phoneMask.js"></script>
<script>
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
    function appendPre(message) {
        var pre = document.getElementById('content');
        var textContent = document.createTextNode(message + '\n');
        pre.appendChild(textContent);
    }

</script>

<script async defer src="https://apis.google.com/js/api.js" onload="this.onload=function(){};handleClientLoad()"
        onreadystatechange="if (this.readyState === 'complete') this.onload()">
</script>
</body>
</html>