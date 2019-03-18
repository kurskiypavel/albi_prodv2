<?php

// Connection with DB
require_once '../config.php';
require_once '../classes/programClass.php';

//$user = $_GET['user'];
session_start();
$user = $_SESSION['user_id'];


require_once '../parts/header.php';


$classProgram = new programClass($conn);


?>


<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Albi | Программы</title>
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

    <script
            src="//code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>

    <script>

    </script>
</head>

<body>
<?php
//select user data
$queryUser = "SELECT level FROM users WHERE level='Instructor' AND id='$user'";
$result = $conn->query($queryUser);
$rows = $result->num_rows;
$objUser = $result->fetch_object();


?>
<div class="header">
    <div class="searchBar">
        <div class="input">
            <input id='myInput' onkeyup="myFunction(); showAllPrograms();" type="text" placeholder="Найти программу...">
            <i class="fas fa-search"></i>
        </div>
    </div>


    <ul class='programLists'>
        <li id='newProgram'>Новые</li>
        <li id='allProgram' class="active">Все</li>
    </ul>

</div>

<div style="display: none;" class="programs new">
    <table id="myTableNew">
        <?php

        $query = "SELECT * FROM programs WHERE new='1'";
        $result = $conn->query($query);

        $rows = $result->num_rows;

        for ($i = 0; $i < $rows; ++$i) {
            $result->data_seek($i);
            $obj = $result->fetch_object();

            echo '<tr><td><a href="program.php?user=' . $user . '&id=' . $obj->id . '">
            <div class="program">
                <div class="headerProgram" style="background-image: url(../../assets/images/App/programs-images/' . $obj->image . ');">
                    <ul>
                        <li style="opacity: 0;"><i class="fas fa-share"></i></li>
                ';
                //        select favorite programs BEGINS
                $queryFavoriteProgram = "SELECT * FROM `favorite-programs` WHERE user='$user' AND program='$obj->id'";
                $resultFavoriteProgram = $conn->query($queryFavoriteProgram);
                $rowsFavoriteProgram = $resultFavoriteProgram->num_rows;
                $objFavoriteProgram = $resultFavoriteProgram->fetch_object();
                if (!$objFavoriteProgram){
                    echo '<li><i id="'.$obj->id.'" class="far fa-heart"></i></li>';
                } elseif ($objFavoriteProgram){
                    echo '<li><i id="'.$obj->id.'" class="fas fa-heart"></i></li>';
                }
                //        select favorite programs ENDS

                echo '
                    </ul>
                </div>
                <div class="body">
                    <h3 class="title">' . $obj->title . '</h3>
                    <div class="features">
                        <ul>
                            <li><img src="../../assets/images/App/calendar-regular.svg" alt="calIcon">
                                <p>По ' . $obj->schedule . '</p>
                            </li>
                            <li>
                                <p>Уровень: <span class="bold">' . $obj->level . '</span></p>
                            </li>
                            <li>
                                <p>Длительность: <span class="bold">' . $obj->duration . ' мин</span></p>
                            </li>
                        </ul>
                    </div>

                    <div class="description">
                        <h3>Описание</h3>
                        <p class="shortDescription">' . $obj->description . '</p>

                        <p class="more">подробнее</p>

                    </div></a>';


            //        booking functionality
            $queryEvent = "select id from events WHERE program='$obj->id' AND student='$user'";
            $resultEvent = $conn->query($queryEvent);
            $rowsEvent = $resultEvent->num_rows;
            $objEvent = $resultEvent->fetch_object();
            //book place - redirect to bookGroupevent
            if (!$objEvent) {
                echo '<button class="book" onclick="location.href =\'bookGroupEvent.php?user=' . $user . '&page=programs&program=' . $obj->id . '&student=' . $user . '&instructor=' . $obj->instructor_id . '\'">Записаться в группу</button>';
            } elseif ($objEvent) {
                //already booked - event query
                echo '<button class="booked" onclick="location.href =\'changeGroupEvent.php?user=' . $user . '&page=programs&id=' . $objEvent->id . '\'">Изменить запись</button>';
            }

            echo '</div>
            </div>
            </td></tr>
        ';
        }

        ?>
    </table>

</div>

<div class="programs all">
    <table id="myTableAll">
        <?php

        $query = "SELECT * FROM programs";
        $result = $conn->query($query);
        
        $rows = $result->num_rows;

        for ($i = 0; $i < $rows; ++$i) {
            $result->data_seek($i);
            $obj = $result->fetch_object();

            echo '<tr><td><a href="program.php?user=' . $user . '&id=' . $obj->id . '">
            <div class="program">
                <div class="headerProgram" style="background-image: url(../../assets/images/App/programs-images/' . $obj->image . ');">
                    <ul>
                        <li style="opacity: 0;"><i class="fas fa-share"></i></li>
                ';
                //        select favorite programs BEGINS
                $queryFavoriteProgram = "SELECT * FROM `favorite-programs` WHERE user='$user' AND program='$obj->id'";
                $resultFavoriteProgram = $conn->query($queryFavoriteProgram);
                $rowsFavoriteProgram = $resultFavoriteProgram->num_rows;
                $objFavoriteProgram = $resultFavoriteProgram->fetch_object();
                if (!$objFavoriteProgram){
                    echo '<li><i id="'.$obj->id.'" class="far fa-heart"></i></li>';
                } elseif ($objFavoriteProgram){
                    echo '<li><i id="'.$obj->id.'" class="fas fa-heart"></i></li>';
                }
                //        select favorite programs ENDS

                echo '
                    </ul>
                </div>
                <div class="body">
                    <h3 class="title">' . $obj->title . '</h3>
                    <div class="features">
                        <ul>
                            <li><img src="../../assets/images/App/calendar-regular.svg" alt="calIcon">
                                <p>По ' . $obj->schedule . '</p>
                            </li>
                            <li>
                                <p>Уровень: <span class="bold">' . $obj->level . '</span></p>
                            </li>
                            <li>
                                <p>Длительность: <span class="bold">' . $obj->duration . ' мин</span></p>
                            </li>
                        </ul>
                    </div>

                    <div class="description">
                        <h3>Описание</h3>
                        <p class="shortDescription">' . $obj->description . '</p>

                        <p class="more">подробнее</p>

                    </div>
                    </a>';


            //        booking functionality
            $queryEvent = "select id from events WHERE program='$obj->id' AND student='$user'";
            $resultEvent = $conn->query($queryEvent);
            $rowsEvent = $resultEvent->num_rows;
            $objEvent = $resultEvent->fetch_object();
            //book place - redirect to bookGroupevent
            if (!$objEvent) {
                echo '<button class="book" onclick="location.href =\'bookGroupEvent.php?user=' . $user . '&page=programs&program=' . $obj->id . '&student=' . $user . '&instructor=' . $obj->instructor_id . '\'">Записаться в группу</button>';
            } elseif ($objEvent) {
                //already booked - event query
                echo '<button class="booked" onclick="location.href =\'changeGroupEvent.php?user=' . $user . '&page=programs&id=' . $objEvent->id . '\'">Изменить запись</button>';
            }

            echo '</div>
            </div>
            </td></tr>
        ';
        }

        ?>

    </table>
</div>

<?php include_once '../parts/footer.php' ?>


<script src='../js/app.js'></script>
</body>

</html>