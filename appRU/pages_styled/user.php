<?php

// Connection with DB
require_once '../config.php';
require_once '../classes/programClass.php';


//$user = $_GET['user'];
session_start();
$user = $_SESSION['user_id'];


require_once '../parts/header.php';

$classProgram = new programClass($conn);

//select userd data
$query = "SELECT * FROM users WHERE id='$user' OR googleID='$user'";
$result = $conn->query($query);
//if (!$result) die($conn->connect_error);
$rows = $result->num_rows;
$obj = $result->fetch_object();


if ($user == '1') {
    $notificationLink= '<a class="ring" href="notifications_admin.php"><i class="far fa-bell"></i></a>';
} else {
    $query = "SELECT id FROM `notifications-booking` WHERE owner='$user' and readed is null";
    $result = $conn->query($query);
    //if (!$result) die($conn->connect_error);
    $rows = $result->num_rows;
    if (!$rows) {
        $notificationLink= '<a class="ring" href="notifications.php"><i class="far fa-bell"></i></a>';
    } else {
        $notificationLink= '<a class="ring" href="notifications.php"><i class="fas active  fa-bell"></i></a>';
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Albi | Мой профиль</title>
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

<body class="userPage">
<div class="header">
    <?php echo $notificationLink; ?>

    <h3>Мой профиль</h3>
    <a style="display: none;" href="adminUser.php?user=<?php echo $user; ?>"><i class="fas fa-cog"></i></a>
</div>
<div class="body">
    <div class="card">
        <div class="subInstructor">
            <div class="headerInstructor"
                 style="background-image: url(../../assets/images/App/user-images/<?php echo $obj->avatar; ?>);">
            </div>
            <div class="body">
                <h3><?php echo $obj->first_name . ' ' . $obj->last_name ?></h3>
            </div>
        </div>
    </div>
    <div class="infoContainer">
       <div class="stats">
           <a href='messages.php' style='font-family: museoSans500; color:unset;'>Мои сообщения</a>
        </div>
        <div class="info">
            <h3>Личная информация</h3>
            <ul>

                <li>
                    <p><span class="bold">Дата рождения: </span><span id="bdateRU"></span></p>
                    <p class="hide" style="display: none;"><span class="bold">Дата рождения: </span>—/—/— —</p>
                </li>

                <li>
                    <p><span class="bold">Адрес: </span><?php echo $obj->location; ?></p>
                    <p class="hide" style="display: none;"><span class="bold">Адрес: </span>—</p>
                </li>
                <li>
                    <p><span class="bold">Email: </span><?php echo $obj->email; ?></p>
                    <p class="hide" style="display: none;"><span class="bold">Email: </span>—</p>
                </li>
                <li>
                    <p class="tel"><span class="bold">Телефон: </span><?php echo $obj->phone; ?></p>
                    <p class="hide" style="display: none;"><span class="bold">Телефон: </span>—</p>
                </li>
            </ul>
        </div>
        <div class="about">
            <h3>Дополнительно</h3>
            <textarea class="aboutContent"
                      placeholder="Расскажите вашему йога-мастеру о себе…"><?php echo $obj->about; ?></textarea>
        </div>
        <div class="favorites">


            <?php
            //        select favorite programs BEGINS
            $queryFavoriteProgram = "SELECT DISTINCT programs.title, `favorite-programs`.program as favoriteId
            FROM programs
            JOIN `favorite-programs` ON programs.id = `favorite-programs`.program WHERE `favorite-programs`.user='$user'";
            $resultFavoriteProgram = $conn->query($queryFavoriteProgram);
            $rowsFavoriteProgram = $resultFavoriteProgram->num_rows;
            if ($rowsFavoriteProgram) {
                echo '
                    <h3>Мои любимые программы</h3>
                    <div class="favoritesList">
                                <ul>';
                for ($i = 0; $i < $rowsFavoriteProgram; ++$i) {
                    $result->data_seek($i);
                    $objFavoriteProgram = $resultFavoriteProgram->fetch_object();
                    if ($objFavoriteProgram) {
                        echo '<a href="program.php?id=' . $objFavoriteProgram->favoriteId . '&user=' . $user . '">
                        <li class="row d-flex flex-d-row">
                            <p class="column-10">' . $objFavoriteProgram->title . '</p>
                            <div class="column-2" style="text-align: right;"><i id="' . $objFavoriteProgram->favoriteId . '"class="fas fa-times"></i></div>
                        </li>
                    </a>';
                    }
                }
                echo '            </ul>
                    </div>';
            }

            //        select favorite programs ENDS
            //        end
            ?>

        </div>
        <div style="margin-top: 30px; height: 100px;" id='signout_button' class="logout"><a href="../ajax/logout.php">Выйти</a></div>

    </div>

    <div style='display:none;' class="changesNav">
        <!-- <input name="update" type="submit" value="Done"> -->
        <a id='done'>Принять</a>
        <a id='cancel' href="user.php?user=<?php echo $user; ?>">Отменить</a>
    </div>
</div>

<?php include_once '../parts/footer.php' ?>

<script
        src="//code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

<script src="../js/app.js"></script>
<script src="../../assets/js/moment.min.js"></script>

<script>
    //Format birthdate date output
    //grab birthday date
    var birthday = "<?php echo $obj->birthdate; ?>";
    if (birthday) {
        //format rules
        var momentBirth = moment(birthday);
        //output the result on page
        momentBirth.locale('ru');
        var momentBirthRU = momentBirth.format("DD MMM YYYY");

        //output the result on page
        $('#bdateRU').text(momentBirthRU);
    }

    //Format join date output from now on Page:product.php Field:product join element
    //grab join date
    var joinDate = '<?php echo $obj->created_at; ?>';

    //format rule

    let momentjoinDate = moment(joinDate + "-03:00", "YYYY-MM-DD HH:mm:ssZ").locale('ru').fromNow();
    //output the result on page
    $('#joinDate').text(momentjoinDate);

</script>

<script>

    //Show Save x Cancel buttons on Page:adminUser.php Field:form
    // event on typing
    $('textarea').keypress(function () {
        //show upload button
        $('.changesNav').css('display', 'block');
    });
    // event on changing
    $('textarea').change(function () {
        //show upload button
        $('.changesNav').css('display', 'block');
    });

    //Refresh form and rollback all changes on Page:users.php Field:personal information
    // if Cancel button pressed on Avatar form - reload page
    $('#cancel').click(function () {
        location.reload();
    });

    $('#done').click(function () {
        var user = '<?php echo $user; ?>';
        var about = $('textarea').val();
        if (about != "") {
            $.ajax({
                type: "POST",
                url: "../ajax/aboutUser.php",
                data: {
                    id: user,
                    about: about
                }
            });
        }

        $('.changesNav').css('display', 'none');
    });
</script>

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