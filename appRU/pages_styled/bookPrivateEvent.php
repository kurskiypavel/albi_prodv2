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


require_once '../parts/header.php';


$obj = new eventClass($conn);


if ($_POST) {



        $date = htmlspecialchars($_POST['date']);
        $time = htmlspecialchars($_POST['time']);
        $comment = htmlspecialchars($_POST['comment']);
        $confirmed = '0';
        $canceled = '0';
        $repeatable = isset($_POST['repeatable']);


        //createPrivateEvent
        $obj->createPrivateEvent(
            $student, $instructor,
            $date, $time, $comment,
            $confirmed, $canceled, $repeatable);
        echo "<script>location.href = 'instructor.php?user=" . $user . "&id=" . $instructor . "';</script>";



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
        <p class="label ">Дата</p><input name='date' class="gray date" type="date">
        <span class="error date"></span>
        <p class="label ">Время</p><input name='time' class="gray time" type="time">
        <span class="error time"></span>
        <p class="label">Комментарий</p><textarea class="" name='comment' type="text"></textarea>

        <div class="repeatableDiv">
            <p class="repeatableLabel">Повторные занятия</p>
            <div class="repeatableCheckbox">

                <input type='checkbox' class='ios8-switch' name="repeatable" id='checkbox-1'>
                <!-- get to DB by checked property -->
                <label for='checkbox-1'></label>
            </div>
        </div>

        <input class="button" name="createPrivateEvent" type="submit" value="Готово">

    </form>
    <?php include_once '../parts/footer.php' ?>
</div>
<script
        src="//code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

<script>
    $('input.button').click(function (e) {

        var dateVal = $('.date').val();
        var timeVal = $('.time').val();

        if (dateVal  == "" && timeVal == "") {
            e.preventDefault();
            $('.error.date').text('Пожалуйста выберите дату');
            $('.error.time').text('Пожалуйста выберите время');
        } else if (dateVal == "") {
            e.preventDefault();
            $('.error.time').text('');
            $('.error.date').text('Пожалуйста выберите дату');
        } else if (timeVal == "") {
            e.preventDefault();
            $('.error.date').text('');
            $('.error.time').text('Пожалуйста выберите время');
        } else {

        }


    })
</script>
</body>

</html>