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
    <?php


//select user data
$query = "SELECT * FROM users WHERE id='$instructor'";
$result = $conn->query($query);
if (!$result) die($conn->connect_error);
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
                <p>Позванить</p>
            </a>

            <p class="or">- или -</p>

            <a class="facebook" href="https://www.facebook.com/profile.php?id=1109115237">
                <img  src="../../assets/images/App/flogo_RGB_HEX-144.png" alt="Facebook">
                <p class="fb">Facebook</p>
            </a>

            <a class="instagram" href="https://www.instagram.com/yoga_albi/">
                <img  src="../../assets/images/App/2000px-Instagram@2x.png" alt="Instagram">
                <p class="insta">Instagram</p>
            </a>

        </div>
        <?php include_once '../parts/footer.php' ?>
    </div>


<script
			  src="//code.jquery.com/jquery-3.3.1.min.js"
			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
			  crossorigin="anonymous"></script>
</body>

</html>