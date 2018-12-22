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

</head>

<body class="notificationsPage">
<div class="header">
    <h3>Уведомления</h3>
</div>
<!-- USER NOTIFICATOINS -->
<div class="notifications">
    <?php

    $query = "SELECT *,TIME_FORMAT(`notifications-booking`.time, '%k:%i') AS time
        FROM `notifications-booking`
        WHERE owner='$user' ORDER BY created_at DESC";

    $result = $conn->query($query);
    $data = $result->fetch_all(MYSQLI_ASSOC);

    $len = sizeof($data);
    //    $rows = $result->num_rows;


    for ($i = 0; $i < $len; ++$i) {
        ?>

        <div  id='<?php echo $data[$i]['id'];?>' class="notification <?php echo $data[$i]['readed'] !='1' ? 'active' : '' ?>">
            <?php
            if ($data[$i]['program']) {

                if ($data[$i]['change'] != '1' && $data[$i]['canceled'] != '1') {
//                    echo 'Вы записались в группу ' . $data[$i]['program'] . ' по ' . $data[$i]['schedule'] . '<br><span class="notifDate">' . $data[$i]['created_at'] . '</span>';
                    echo 'Вы записались в группу ' . $data[$i]['program'] . ' по ' . $data[$i]['schedule'];
                } elseif ($data[$i]['canceled'] == '1') {
//                    echo 'Вы отменили занятие в группе ' . $data[$i]['program'] . ' по ' . $data[$i]['schedule'] . '<br><span class="notifDate">' . $data[$i]['created_at'] . '</span>';
                    echo 'Вы отменили занятие в группе ' . $data[$i]['program'] . ' по ' . $data[$i]['schedule'];
                }
            } else {
                if ($data[$i]['change'] == '1') {
//                    echo 'Вы изменили занятие с Альбиной. Занятие состоится <span class="eventNextDate">' . $data[$i]['date'] . '</span> в ' . $data[$i]['time'] . '<br><span class="notifDate">' . $data[$i]['created_at'] . '</span>';
                    echo 'Вы изменили занятие с Альбиной. Занятие состоится <span class="eventNextDate">' . $data[$i]['date'] . '</span> в ' . $data[$i]['time'];
                } elseif ($data[$i]['canceled'] == '1') {
//                    echo 'Вы отменили занятие с Альбиной на <span class="eventNextDate">' . $data[$i]['date'] . '</span> в ' . $data[$i]['time'] . '<br><span class="notifDate">' . $data[$i]['created_at'] . '</span>';
                    echo 'Вы отменили занятие с Альбиной на <span class="eventNextDate">' . $data[$i]['date'] . '</span> в ' . $data[$i]['time'];
                } elseif ($data[$i]['confirmed'] == '1') {
//                    echo 'Альбина подтвердила занятие <span class="eventNextDate">' . $data[$i]['date'] . '</span> в ' . $data[$i]['time'] . '<br><span class="notifDate">' . $data[$i]['created_at'] . '</span>';
                    echo 'Альбина подтвердила занятие <span class="eventNextDate">' . $data[$i]['date'] . '</span> в ' . $data[$i]['time'];
                } elseif ($data[$i]['confirmed'] != '1' && $data[$i]['canceled'] != '1' && $data[$i]['change'] != '1') {
//                    echo 'Вы записались на занятие с Альбиной на <span class="eventNextDate">' . $data[$i]['date'] . '</span> в ' . $data[$i]['time'] . '<br><span class="notifDate">' . $data[$i]['created_at'] . '</span>';
                    echo 'Вы записались на занятие с Альбиной на <span class="eventNextDate">' . $data[$i]['date'] . '</span> в ' . $data[$i]['time'];
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
            let momentNotifDate = moment(notifNext + "+03:00", "YYYY-MM-DD HH:mm").locale('ru').fromNow();
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


<script>
    /* Read norifications on visiting the page script */

    function read() {
        $(this).removeClass("active");

        // get id of clicked notificaiton
        let notification = $(this).attr('id');
        //read ajax
        $.ajax({
            type: "POST",
            url: "../ajax/read.php",
            data: {
                notification: notification
            }
        });
    }

    $(".notification.active").click(read);




</script>

</body>

</html>