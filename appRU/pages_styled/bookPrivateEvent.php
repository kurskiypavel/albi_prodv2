<?php

// Connection with DB
require_once '../config.php';
require_once '../classes/eventClass.php';

//get id of clicked item from url
$student = $_GET['student'];
$instructor = '1';
//$user = $_GET['user'];
session_start();
$user = $_SESSION['user_id'];

// template id for sendgrid email - new event template
$template_id = 'd-155a815710dd438e9cbe3fbdfa57e2c3';

// day of week to restrict booking same day as today
$day_of_week = date('N', strtotime(date("l")));

require_once '../parts/header.php';

// get available schedule
$queryPrivate = "SELECT DISTINCT(date) AS date FROM `private-schedule` WHERE `taken` != '1' AND dayOfWeek != $day_of_week  GROUP BY date";
$resultPrivate = $conn->query($queryPrivate);
$rowsPrivate = $resultPrivate->num_rows;

$obj = new eventClass($conn);

if ($_POST) {
    if (isset($_POST['createPrivateEventB'])) {
        $date = htmlspecialchars($_POST['date']);
        $time = htmlspecialchars($_POST['time']);
        $comment = htmlspecialchars($_POST['comment']);
        $repeatable = isset($_POST['repeatable']);

        //createPrivateEvent
        $obj->createPrivateEvent(
            $student, $instructor,
            $date, $time, $comment,
            $repeatable);
        $obj->makeTaken($date, $time);


//        include_once '../ajax/send-email_private.php';
//        echo "<script>location.href = 'instructor.php?user=" . $user . "&id=" . $instructor . "';</script>";
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
    <title>Albi | Взять частный урок</title>
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
<div class="bookPrivateEventPage">
    <div class="header">
        <a href='instructor.php?user=<?php echo $user; ?>&id=1'><i class="fas fa-arrow-left"></i></a>
        <h3>Взять частный урок</h3>
    </div>


    <form class='form' method="post">

        <?php
        if ($rowsPrivate) {

            echo '<p class="label">День недели</p><select name="date" id="dates">';
            for ($i = 0; $i < $rowsPrivate; ++$i) {
                $resultPrivate->data_seek($i);
                $objPrivate = $resultPrivate->fetch_object();
                echo '<option value="' . $objPrivate->date . '">' . $objPrivate->date . '</option>';
            }
            echo <<<HEREFORM
                </select>
                <span class="error date"></span>
                <p class="label">Время</p>
                <select name="time" id="times"></select>
                <span class="error time"></span>
                <p class="label">Комментарий</p>
                <textarea class="" name='comment' type="text" value=""></textarea>
                <div class="repeatableDiv">
                    <p class="repeatableLabel">Повторные занятия</p>
                    <div class="repeatableCheckbox">
                        <input type='checkbox' class='ios8-switch' name="repeatable" id='checkbox-1'>
                        <!-- get to DB by checked property -->
                        <label for='checkbox-1'></label>
                    </div>
                </div>
                <input class="button" name="createPrivateEventB" type="submit" value="Готово">
HEREFORM;
        } else {
            echo '<div class="noPrivate">Сегодня все занято. Завтра могут появиться свободные места.</div>';
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
        for (var i = 0; i < length; ++i) {
            $('#times').append('<option value="' + data2[i].time + '">' + data2[i].time + '</option>');
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
    $(document).ready(getList);
    $('#dates').change(getList);


</script>

</body>

</html>