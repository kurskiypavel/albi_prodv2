<?php

// Connection with DB
require_once '../config.php';
require_once '../classes/programClass.php';

//class
$classProgram = new programClass($conn);

//$user = $_GET['user'];
session_start();
$user = $_SESSION['user_id'];


require_once '../parts/header.php';

$id = $_GET['id'];


$query = "SELECT *,programs.level level  FROM programs JOIN users ON programs.instructor_id=users.id WHERE programs.id='$id'";
$result = $conn->query($query);
$rows = $result->num_rows;
$obj = $result->fetch_object();

?>


<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Albi | Программа</title>
    <link href="https://cdn.jsdelivr.net/npm/flexiblegrid@v1.2.2/dist/css/flexible-grid.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/styleApp.css">
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css"
          integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">


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

<body class="single">

<div class="programItem">
    <div class="program">
        <div class="headerProgram"
             style="background-image: url(../../assets/images/App/programs-images/<?php echo $obj->image; ?>);">
            <ul>
                <li style="opacity: 0;"><i class="fas fa-share"></i></li>
                <?php
                //        select favorite programs BEGINS
                $queryFavoriteProgram = "SELECT * FROM `favorite-programs` WHERE user='$user' AND program='$id'";
                $resultFavoriteProgram = $conn->query($queryFavoriteProgram);
                $rowsFavoriteProgram = $resultFavoriteProgram->num_rows;
                $objFavoriteProgram = $resultFavoriteProgram->fetch_object();
                if (!$objFavoriteProgram) {
                    echo '<li><i class="far fa-heart"></i></li>';
                } elseif ($objFavoriteProgram) {
                    echo '<li><i class="fas fa-heart"></i></li>';
                }
                //        select favorite programs ENDS
                ?>

            </ul>
        </div>
        <div class="body">
            <h3><?php echo $obj->title; ?></h3>
            <div class="features">
                <ul>
                    <li><img src="../../assets/images/App/calendar-regular.svg" alt="calIcon">
                        <p>По <?php echo $obj->schedule; ?></p>
                    </li>
                    <li>
                        <?php
                        //        booking functionality BEGINS
                        $queryEvent = "SELECT * FROM events WHERE student='$user' AND program='$id'";
                        $result = $conn->query($queryEvent);
                        $rows = $result->num_rows;
                        $objEvent = $result->fetch_object();
                        if (!$objEvent) {
                            echo '<button class="book" onclick="location.href =\'bookGroupEvent.php?user=' . $user . '&page=program&program=' . $id . '&student=' . $user . '&instructor=' . $obj->instructor_id . '\'">Записаться в группу</button>';

                        } elseif ($objEvent) {
                            //already booked - event query
                            echo '<button class="booked" onclick="location.href =\'changeGroupEvent.php?user=' . $user . '&page=program&id=' . $objEvent->id . '\'">Изменить запись</button>';
                        }
                        //        booking functionality ENDS
                        ?>

                    </li>
                </ul>
            </div>
            <div class="overview">
                <h3>Обзор</h3>
                <ul>
                    <li>
                        <p>Фокус: <?php echo $obj->focus; ?></p>
                    </li>
                    <li>
                        <p>Уровень: <span class="bold"><?php echo $obj->level; ?></span></p>
                    </li>
                    <li>
                        <p>Длительность: <span class="bold"><?php echo $obj->duration; ?></span> мин</p>
                    </li>
                    <li>
                        <p>Размер группы: <span class="bold"><?php echo $obj->group_size; ?></span> человек</p>
                    </li>
                </ul>
            </div>

            <div class="description">
                <h3>Описание</h3>
                <p><?php echo $obj->description; ?></p>
            </div>

            <div class="instructor">
                <h3>Инструктор</h3>
                <a href="instructor.php?user=<?php echo $user; ?>&id=<?php echo $obj->instructor_id; ?>">
                    <div class="subInstructor">
                        <div class="headerInstructor"
                             style="background-image: url(../../assets/images/App/user-images/<?php echo $obj->avatar; ?>);">
                        </div>

                        <div class="body">
                            <h3><?php echo $obj->first_name . ' ' . $obj->last_name; ?></h3>
                            <p class="shortDescription"><?php echo $obj->about; ?></p>
                            <p class="more">подробнее</p>
                        </div>

                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<?php include_once '../parts/footer.php' ?>

<script
        src="//code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

<script src="../js/app.js">

</script>
</body>

</html>