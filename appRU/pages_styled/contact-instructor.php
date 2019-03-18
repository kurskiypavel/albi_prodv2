<?php
// Connection with DB
require_once '../config.php';

//instructor id
$instructor = '1';

//$user = $_GET['user'];
session_start();
$user = $_SESSION['user_id'];


require_once '../parts/header.php';

$page = $_GET['page'];
?>


<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Albi | Связаться с учителем</title>
    <link rel="icon" href="../../assets/images/favicon.ico" type="image/x-icon">
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
<?php


//select user data
$query = "SELECT * FROM users WHERE id='$instructor'";
$result = $conn->query($query);
$rows = $result->num_rows;
$obj = $result->fetch_object();

$email = $obj->email;
$phone = $obj->phone;
$facebook = $obj->facebook;
$instagram = $obj->instagram;


?>

<div class="contactInstructorPage">
    <div class="header">

        <?php
        if ($page == 'events') {

            echo '<a href="events.php?user=' . $user . '"><i class="fas fa-arrow-left"></i></a>';
        } elseif ($page == 'instructor') {
            echo '<a href="instructor.php?user=' . $user . '&id=' . $instructor . '"><i class="fas fa-arrow-left"></i></a>';

        }

        ?>
        <h3>Способы связи</h3>
    </div>
    <div class="contactBlock">
        <a class='btn message' href="mailto:Albina.kurskaya@gmail.com?Subject=Question" target="_top">
            <i class="far fa-envelope"></i>
            <p>Написать письмо</p>
        </a>

        <a class='btn call' href="tel:+79636861278">
            <i class="fas fa-phone"></i>
            <p>Позвонить</p>
        </a>

        <p class="or">- или -</p>

        <!--            <a class="facebook" href="https://www.facebook.com/profile.php?id=1109115237">-->
        <!--                <img  src="../../assets/images/App/flogo_RGB_HEX-144.png" alt="Facebook">-->
        <!--                <p class="fb">Facebook</p>-->
        <!--            </a>-->

        <!--            <a class="instagram" href="https://www.instagram.com/yoga_albi/">-->
        <!--                <img  src="../../assets/images/App/2000px-Instagram@2x.png" alt="Instagram">-->
        <!--                <p class="insta">Instagram</p>-->
        <!--            </a>-->
        <a class="instagram" href="messages.php">
            <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="comments" role="img" xmlns="http://www.w3.org/2000/svg" class="svg-inline--fa fa-comments fa-w-18 fa-5x" style="width: 66px;" viewBox="0 0 576 512"><path d="M569.9 441.1c-.5-.4-22.6-24.2-37.9-54.9 27.5-27.1 44-61.1 44-98.2 0-80-76.5-146.1-176.2-157.9C368.4 72.5 294.3 32 208 32 93.1 32 0 103.6 0 192c0 37 16.5 71 44 98.2-15.3 30.7-37.3 54.5-37.7 54.9-6.3 6.7-8.1 16.5-4.4 25 3.6 8.5 12 14 21.2 14 53.5 0 96.7-20.2 125.2-38.8 9.1 2.1 18.4 3.7 28 4.8 31.5 57.5 105.5 98 191.8 98 20.8 0 40.8-2.4 59.8-6.8 28.5 18.5 71.6 38.8 125.2 38.8 9.2 0 17.5-5.5 21.2-14 3.6-8.5 1.9-18.3-4.4-25zM155.4 314l-13.2-3-11.4 7.4c-20.1 13.1-50.5 28.2-87.7 32.5 8.8-11.3 20.2-27.6 29.5-46.4L83 283.7l-16.5-16.3C50.7 251.9 32 226.2 32 192c0-70.6 79-128 176-128s176 57.4 176 128-79 128-176 128c-17.7 0-35.4-2-52.6-6zm289.8 100.4l-11.4-7.4-13.2 3.1c-17.2 4-34.9 6-52.6 6-65.1 0-122-25.9-152.4-64.3C326.9 348.6 416 278.4 416 192c0-9.5-1.3-18.7-3.3-27.7C488.1 178.8 544 228.7 544 288c0 34.2-18.7 59.9-34.5 75.4L493 379.7l10.3 20.7c9.4 18.9 20.8 35.2 29.5 46.4-37.1-4.2-67.5-19.4-87.6-32.4z" class="" fill="#D75057"></path></svg>
            <p class="insta">Чат</p>

        </a>


    </div>
    <?php include_once '../parts/footer.php' ?>
</div>


<!--<script-->
<!--        src="//code.jquery.com/jquery-3.3.1.min.js"-->
<!--        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="-->
<!--        crossorigin="anonymous"></script>-->
</body>

</html>