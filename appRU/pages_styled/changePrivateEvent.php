<?php


// Connection with DB
require_once '../config.php';
require_once '../classes/eventClass.php';

//get id of clicked item from url
$id = $_GET['id'];
//$user = $_GET['user'];
session_start();
$user = $_SESSION['user_id'];

// day of week to restrict booking/changing same day as today
$day_of_week = date('N', strtotime(date("l")));


require_once '../parts/header.php';

// get available schedule
//    instead load actual date and time for this event
$queryPrivate = "SELECT DISTINCT(date) AS date FROM `private-schedule` WHERE `taken` != '1' AND dayOfWeek != $day_of_week GROUP BY date";
$resultPrivate = $conn->query($queryPrivate);
$rowsPrivate = $resultPrivate->num_rows;


$page = $_GET['page'];

$obj = new eventClass($conn);

$queryEvent = "SELECT * FROM events WHERE id='$id'";
$resultEvent = $conn->query($queryEvent);
if (!$resultEvent) die($conn->connect_error);
$rowsEvent = $resultEvent->num_rows;
$objEvent = $resultEvent->fetch_object();


define("OLD_DATE", $objEvent->date);
define("OLD_TIME", $objEvent->time);


if ($_POST) {
    if (isset($_POST['changePrivateEvent'])) {

        // template id for sendgrid email - change template
        $template_id = 'd-ca88b9abf4cc42de84e9aebc90b6af20';


        $date = htmlspecialchars($_POST['date']);
        $time = htmlspecialchars($_POST['time']);
        $comment = htmlspecialchars($_POST['comment']);
        $repeatable = isset($_POST['repeatable']);

        //changePrivateEvent
        $obj->updatePrivate($date, $time, $id, $comment, $repeatable);

        //        book date and time in private_schedule table
        $obj->makeTaken($date, $time);
        //        unbook previous date and time in private_schedule table
        $obj->deleteTaken(OLD_DATE, OLD_TIME);

        //        include_once '../ajax/send-email_private.php';


        echo "<script>location.href = 'programs.php';</script>";


    } elseif (isset($_POST['deletePrivateEvent'])) {

        // template id for sendgrid email - cancel template
        $template_id = 'd-2c01b97dc903476c925377e9f702e34d';

        $date = htmlspecialchars($_POST['date']);
        $time = htmlspecialchars($_POST['time']);

        //deletePrivateEvent
        $obj->delete($id);

        //        unbook previous date and time in private_schedule table
        $obj->deleteTaken(OLD_DATE, OLD_TIME);
        //        include_once '../ajax/send-email_private.php';

        echo "<script>location.href = 'programs.php';</script>";

    }

}


?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Albi | Изменение записи</title>
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
<div class="changePrivateEventPage">
    <div class="header">
        <a href='events.php?user=<?php echo $user; ?>'><i class="fas fa-arrow-left"></i></a>
        <h3>Изменение записи</h3>
    </div>


    <form class='form' method="post">

        <?php
        if ($rowsPrivate) {

            echo '<p class="label">День недели</p><select name="date" id="dates">';
            for ($i = 0; $i < $rowsPrivate; ++$i) {
                $resultPrivate->data_seek($i);
                $objPrivate = $resultPrivate->fetch_object();
                if ($objPrivate->date == $objEvent->date) {
                    // selected
                    echo '<option selected value="' . $objPrivate->date . '">' . $objPrivate->date . '</option>';
                } else {
                    echo '<option value="' . $objPrivate->date . '">' . $objPrivate->date . '</option>';
                }

            }
            echo '</select><span class="error date"></span><p class="label">Время</p><select name="time" id="times"></select><span class="error time"></span>';


        ?>
        <p class="label">Комментарий</p><textarea name='comment' type="text"
                                                  value=""></textarea>

        <div class="repeatableDiv">
            <p class="repeatableLabel">Повторные занятия</p>
            <div class="repeatableCheckbox">
                <input type='checkbox' class='ios8-switch' <?php echo $objEvent->repeatble ? 'checked' : '' ?>
                       name="repeatable" id='checkbox-1'>
                <!-- get to DB by checked property -->
                <label for='checkbox-1'></label>
            </div>
        </div>

        <input class="button" name="changePrivateEvent" type="submit" value="Изменить">
        <input class="cancel" name="deletePrivateEvent" type="submit" value="Отменить занятие">
        <?php
        } else {
            echo '
            <div class="noPrivate"><span>Сегодня все занято. Завтра могут появиться свободные места.</span><input class="cancel" name="deletePrivateEvent" type="submit" value="Отменить занятие"></div>';

        }
        ?>
    </form>
    <?php include_once '../parts/footer.php' ?>
</div>
<script
        src="//code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

<script>

    //rewrite according to new
    $('input.button').click(function (e) {

        var dateVal = $('#dates').val();
        var timeVal = $('#times').val();


        if (dateVal == null && timeVal == null) {
            e.preventDefault();
            $('.error.date').text('Пожалуйста выберите день недели');
            $('.error.time').text('Пожалуйста выберите время');
            return false;
        } else if (dateVal == null) {
            e.preventDefault();
            $('.error.time').text('');
            $('.error.date').text('Пожалуйста выберите день недели');
            return false;
        } else if (timeVal == null) {
            e.preventDefault();
            $('.error.date').text('');
            $('.error.time').text('Пожалуйста выберите время');
            return false;
        }


    })
</script>

<script>

    //        Pasting data from AJAX
    function succ(data2) {
        var length = data2.length;

        $('#times').html('');
        let eventTime = "<?php echo $objEvent->time;?>";
        for (var i = 0; i < length; ++i) {
            if (eventTime === data2[i].time) {
                $('#times').append('<option selected value="' + data2[i].time + '">' + data2[i].time + '</option>');
            } else {
                $('#times').append('<option value="' + data2[i].time + '">' + data2[i].time + '</option>');
            }

        }


    }
    //    request times according to chosen weekday
    function getList() {

        var chosenDate = $('#dates').val();
        var article = $.ajax({
            type: 'POST',
            url: '../ajax/get-private-schedule.php',
            dataType: "text",
            data: {chosenDate: chosenDate},
            async: true,
            success: function (data) {

                var json = data;
                obj = JSON.parse(json);
                succ(obj);


            },
            error: function () {

                console.log('error');

            }

        });
    }
    //    instead load actual date and time for this event
    $(document).ready(getList);
    $('#dates').change(getList);


</script>
</body>

</html>