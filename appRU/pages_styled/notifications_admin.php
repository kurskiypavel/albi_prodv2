<?php

// Connection with DB
require_once '../config.php';


//$user = $_GET['user'];
session_start();
$user = $_SESSION['user_id'];


require_once '../parts/header.php';


?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Albi | Уведомления</title>
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

    <style>
        .notifications {
            width: 300px;

            margin: 0 auto;
        }

        .notification {
            padding: 10px;
            line-height: 18px;
        }
    </style>
</head>

<body>
<div class="header eventsPage">
    <h3>Уведомления</h3>


</div>
<!-- USER NOTIFICATOINS -->
<div class="notifications">
    <?php

    $query = "SELECT *,TIME_FORMAT(`notifications-booking_admin`.time, '%k:%i') AS time
        FROM `notifications-booking_admin`
        ORDER BY created_at DESC";

    $result = $conn->query($query);
    $data = $result->fetch_all(MYSQLI_ASSOC);

    $len = sizeof($data);
    //    $rows = $result->num_rows;


    for ($i = 0; $i < $len; ++$i) {
        ?>

        <div class="notification">
            <?php
            if ($data[$i]['program']) {

                if ($data[$i]['change'] != '1' && $data[$i]['canceled'] != '1') {
                    echo $data[$i]['user_name'] . ' будет ходить на занятия ' . $data[$i]['program'] . ' по ' . $data[$i]['schedule'] . '<br>Телефон: ' . $data[$i]['user_phone'];
                    if ($data[$i]['comment']) {
                        echo '<br>Комментарий: ' . $data[$i]['comment'] . '<br><span class="notifDate">' . $data[$i]['created_at'] . '</span>';
                    } else {
                        echo '<br><span class="notifDate">' . $data[$i]['created_at'] . '</span>';
                    }
                } elseif ($data[$i]['canceled'] == '1') {
                    echo $data[$i]['user_name'] . ' ушел(а) из группы ' . $data[$i]['program'] . ' по ' . $data[$i]['schedule'] . '<br><span class="notifDate">' . $data[$i]['created_at'] . '</span>';
                }
            } else {
                if ($data[$i]['change'] == '1') {
                    echo $data[$i]['user_name'] . ' изменил(а) частное занятие. Перенос на <span class="eventNextDate">' . $data[$i]['date'] . '</span> в ' . $data[$i]['time'];
                    if ($data[$i]['comment']) {
                        echo '<br>Комментарий: ' . $data[$i]['comment'];
                    }
                    if ($data[$i]['repeatble']) {
                        echo '<br>Это повторяющееся занятие';
                    }
                    echo '<br><span class="notifDate">' . $data[$i]['created_at'] . '</span>';

                } elseif ($data[$i]['canceled'] == '1') {
                    echo $data[$i]['user_name'] . ' отменил(а) занятие на <span class="eventNextDate">' . $data[$i]['date'] . '</span> в ' . $data[$i]['time'] . '<br><span class="notifDate">' . $data[$i]['created_at'] . '</span>';
                } elseif ($data[$i]['confirmed'] != '1' && $data[$i]['canceled'] != '1' && $data[$i]['change'] != '1') {
                    echo $data[$i]['user_name'] . ' забронировал(а) частное занятие на <span class="eventNextDate">' . $data[$i]['date'] . '</span> в ' . $data[$i]['time'] . '<br>Телефон: ' . $data[$i]['user_phone'];
                    if ($data[$i]['comment']) {
                        echo '<br>Комментарий: ' . $data[$i]['comment'];
                    }
                    if ($data[$i]['repeatble']) {
                        echo '<br>Это повторяющееся занятие';
                    }
                    echo '<br><span class="notifDate">' . $data[$i]['created_at'] . '</span>';
                }
            }

            ?>


        </div>


        <?php

    }
    ?>


</div>


<?php include_once '../parts/footer.php' ?>

<script
        src="//code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
<script src='../js/app.js'></script>
<script src="../../assets/js/moment.min.js"></script>

<script>

    function formatNotifDate(i, notifNext) {
        //Format notificationDate
        if (notifNext) {
            let momentNotifDate = moment(notifNext, "YYYY-MM-DD HH:mm").locale('ru').fromNow();
            listOfClassesNotif[i].innerText = momentNotifDate;
        }
    }

    //create array of listed dates
    var listOfClassesNotif = document.getElementsByClassName('notifDate');
    for (var i = 0; i < listOfClassesNotif.length; i++) {
        //pass each into function
        formatNotifDate(i, listOfClassesNotif[i].innerText);
    }


    function formatEventDate(i, eventNext) {
        //Format eventDate

        if (eventNext) {
            //format rules
            var momentEventNext = moment(eventNext);
            //output the result on page
            momentEventNext.locale('ru');
            var momentEventNextRU = momentEventNext.format("DD MMM");
            //insert each into
            listOfClassesEvent[i].innerText = momentEventNextRU;
        }


    }

    //create array of listed dates
    var listOfClassesEvent = document.getElementsByClassName('eventNextDate');
    for (var i = 0; i < listOfClassesEvent.length; i++) {
        //pass each into function
        formatEventDate(i, listOfClassesEvent[i].innerText);
    }


</script>


</body>

</html>