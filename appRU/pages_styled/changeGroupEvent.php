<?php


// Connection with DB
require_once '../config.php';
require_once '../classes/eventClass.php';

//get id of clicked item from url
$id = $_GET['id'];
$page = $_GET['page'];
//$user = $_GET['user'];
session_start();
$user = $_SESSION['user_id'];


require_once '../parts/header.php';



$obj = new eventClass($conn);

$queryEvent = "SELECT * FROM events WHERE id='$id'";
$resultEvent = $conn->query($queryEvent);
if (!$resultEvent) die($conn->connect_error);
$rowsEvent = $resultEvent->num_rows;
$objEvent = $resultEvent->fetch_object();


if ($_POST) {
    if (isset($_POST['changeGroupEvent'])) {



        $comment = htmlspecialchars($_POST['comment']);
        $group_event_id = $objEvent->program;

        //changeGroupEvent
        $obj->updateGroup( $id, $group_event_id, $comment);
        if($page=='programs') {
            echo "<script>location.href = 'programs.php?user=".$user."';</script>";
        } elseif ($page=='program') {
            echo "<script>location.href = 'program.php?user=".$user."&id=".$objEvent->program."';</script>";
        } elseif ($page=='events') {
            echo "<script>location.href = 'events.php?user=".$user."';</script>";
        }

    } elseif (isset($_POST['deleteGroupEvent'])) {

        //deleteGroupEvent
        $obj->delete($id);
        if($page=='programs') {
            echo "<script>location.href = 'programs.php?user=".$user."';</script>";
        } elseif ($page=='program') {
            echo "<script>location.href = 'program.php?user=".$user."&id=".$objEvent->program."';</script>";

        }elseif ($page=='events') {
            echo "<script>location.href = 'events.php?user=".$user."';</script>";
        }

    }

}

//Get available schedule for this program
$query = "SELECT schedule FROM programs WHERE id='$objEvent->program'";
$result = $conn->query($query);
$rows = $result->num_rows;
$obj = $result->fetch_all();

//$schedule= '<p class="gray">По ' . $obj[0][0]. '</p>';
$schedule= $obj[0][0];



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Изменение записи</title>
    <link href="https://cdn.jsdelivr.net/npm/flexiblegrid@v1.2.2/dist/css/flexible-grid.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/styleApp.css">
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
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
                } catch (e) {}
            };
            s.parentNode.insertBefore(tk, s)
        })(document);
    </script>


</head>

<body style="background: unset;">
    <div class="changeGroupEventPage">
        <div class="header">
            <?php
            if ($page == 'programs') {
                echo "<a href='programs.php?user=".$user."'><i class=\"fas fa-arrow-left\"></i></a>";
            } elseif ($page == 'program') {
                echo "<a href='program.php?user=".$user."&id=" . $objEvent->program . "'><i class=\"fas fa-arrow-left\"></i></a>";
            }
            ?>

            <h3>Изменение записи</h3>
        </div>



        <form class='form' method="post">
            <p class="schedule">Занятия проходят по <?php echo $schedule;?></p>
            <p class="label">Комментарий</p><textarea class=""  name='comment' type="text" value="<?php echo $objEvent->comment; ?>"><?php echo $objEvent->comment; ?></textarea>

            <input class="button" name="changeGroupEvent" type="submit" value="Изменить">
            <input class="cancel" name="deleteGroupEvent" type="submit" value="Отменить занятие">
            </br>
        </form>
        <?php include_once '../parts/footer.php'?>
    </div>
    <script
			  src="//code.jquery.com/jquery-3.3.1.min.js"
			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
			  crossorigin="anonymous"></script>
</body>

</html>