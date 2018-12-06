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
    if (isset($_POST['createGroupEvent'])) {



        $comment = htmlspecialchars($_POST['comment']);
        $confirmed = '0';
        $canceled = '0';
        $group_event_id = $program;

        //createGroupEvent
        $obj->createGroupEvent($group_event_id, $program,
            $student, $instructor, $comment,
            $confirmed, $canceled);

        if ($page == 'programs') {
            echo "<script>location.href = 'programs.php?user=" . $user . "';</script>";
        } elseif ($page == 'program') {
            echo "<script>location.href = 'program.php?user=" . $user . "&id=" . $program . "';</script>";

        }

    }

}

//Get available schedule for this program
$query = "SELECT schedule FROM programs WHERE id='$program'";
$result = $conn->query($query);
$rows = $result->num_rows;
$obj = $result->fetch_all();

//$schedule= '<p class="gray">По ' . $obj[0][0]. '</p>';
$schedule= $obj[0][0];




?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Albi | Записаться в группу</title>
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
        <p class="schedule">Занятия проходят по <?php echo $schedule;?></p>


        <p class="label">Комментарий</p><textarea class="" name='comment' type="text"></textarea>

        <input class="button" name="createGroupEvent" type="submit" value="Готово">
    </form>
    <?php include_once '../parts/footer.php' ?>
</div>
<script
        src="//code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
</body>

</html>