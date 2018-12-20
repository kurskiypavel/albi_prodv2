<?php

// Connection with DB
require_once '../config.php';
require_once '../classes/eventClass.php';


//$user = $_GET['user'];
session_start();
$user = $_SESSION['user_id'];


require_once '../parts/header.php';



$classEvent = new eventClass($conn);



?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Albi | Занятия</title>
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

<body>
    <div class="header eventsPage">
        <h3>Занятия</h3>
        <ul class='eventLists'>
            <li id='groupEvents' class="active">Групповые</li>
            <li id='privateEvents' >Частные</li>
        </ul>

    </div>
    <!-- GROUP EVENTS -->
    <div class="events group" >
        <?php

        $query = "SELECT DISTINCT *,events.id event
        FROM events
        JOIN programs ON programs.id = events.program WHERE events.student='$user' AND events.private ='0'";

        $result = $conn->query($query);
        if (!$result) die($conn->connect_error);
        $rows = $result->num_rows;


        for ($i = 0; $i < $rows; ++$i) {
            $result->data_seek($i);
            $obj = $result->fetch_object();
            ?>

                <div class="event">
                    <?php echo '<a href="program.php?user='.$user.'&id=' . $obj->program . '">';?>
                    <div class="headerEvent" style="background-image: url(../../assets/images/App/programs-images/<?php echo $obj->image;?>);">

                    </div>
                    <?php echo '</a>'?>
                    <div class="body">
                        <?php echo '<a href="program.php?user='.$user.'&id=' . $obj->program . '">';?>
                        <h3><?php echo $obj->title;?></h3>
                        <div class="features">
                            <ul>
                                <li><img src="/assets/images/App/calendar-regular.svg" alt="calIcon">
                                    <p class="schedule">По <?php echo $obj->schedule;?></p>
                                </li>
                                <li>
                                    <p>Длительность: <span class="bold"><?php echo $obj->duration;?> мин</span></p>
                                </li>

                            </ul>
                        </div>
                        <?php echo '</a>'?>
                        <?php echo '<button class="booked" style="margin-top: 20px;" onclick="location.href =\'changeGroupEvent.php?user=' . $user . '&page=program&id=' . $obj->event . '\'">Изменить запись</button>';?>

                        <div class="nextLesson">
                            <?php
                            if ($obj->confirmed == 0) {
                                echo '<div class="notConfirmed">
                                <p>Занятие еще не подтверждено.</p>
                                <p>Пожалуйста, дождитесь подтверждения</p>
                                <p class="or">- или -</p>
                                
                            </div>';
                            }

                            ?>

                        </div>
                        <div class="contactInstructor">
                            <?php echo '<a href="contact-instructor.php?user='.$user.'&page=events&instructor=' . $obj->instructor . '&student=' . $obj->student . '"><p>Связаться с учителем</p></a>';?>
                            <i class="far fa-comment"></i>
                        </div>

                    </div>
                </div>


            <?php

        }
        ?>



    </div>

    <!-- PRIVATE EVENTS -->
    <div class="events private" style="display: none">
        <?php

        $query = "SELECT *,events.id event,TIME_FORMAT(events.time, '%k:%i') AS time
        FROM events
        JOIN users ON users.id = events.instructor WHERE events.student='$user' AND events.private ='1'";

        $result = $conn->query($query);
        if (!$result) die($conn->connect_error);
        $rows = $result->num_rows;

        for ($i = 0; $i < $rows; ++$i) {
            $result->data_seek($i);
            $obj = $result->fetch_object();
            ?>
            <div class="event">
                <div class="headerEvent">
                    <?php
                    if ($obj->confirmed == 0) {
                        echo '<img class="grayFace" src="/assets/images/App/myprofile_female_gray@2x.png" alt="girl">';
                    }else{
                        echo '<img class="sunFace" src="/assets/images/App/myprofile_female_sun@2x.png" alt="girl">';
                    }

                    ?>
                </div>
                <div class="body">

                    <p class="lessonWith">Учитель <span class="bold">
                        <?php echo '<a href="instructor.php?user='.$user.'&id=' . $obj->instructor . '&student=' . $obj->student . '&event=' . $obj->event . '">' . $obj->first_name . ' ' . $obj->last_name . '</a>';?>
                    </span></p>


                    <div class="nextLesson">

                        <?php
                        if ($obj->confirmed == 0) {
                            echo '<div class="notConfirmed">
                            <p class="nextText">Следующее занятие: <span class="bold privateNextDate">' . $obj->date. '</span> в <span class="bold">' . $obj->time. '</span></p>
                            <p>Занятие еще не подтверждено.</p>
                                <p>Пожалуйста, дождитесь подтверждения</p>
                            <button onclick="location.href =\'changePrivateEvent.php?user='.$user.'&page=events&id='.$obj->event.'\'" class="change" >Изменить запись</button>
                            <p class="or">- или -</p>
                        </div>';
                        } else{
                            echo '<div class="confirmed">
                            <p class="nextText">Следующее занятие: <span class="bold privateNextDate">' . $obj->date. '</span> в <span class="bold">' . $obj->time . '</span>
                            </p>
                            <button onclick="location.href =\'changePrivateEvent.php?user='.$user.'&page=events&id='.$obj->event.'\'" class="change" >Изменить запись</button>
                            <p class="or">- или -</p>
                        </div>';
                        }
                        ?>


                    </div>
                    <div class="contactInstructor">
                        <?php echo '<a href="contact-instructor.php?user='.$user.'&page=events&instructor=' . $obj->instructor . '&student=' . $user . '"><p>Связаться с учителем</p></a>';?>
                        <i class="far fa-comment"></i>
                    </div>

                </div>
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

        function formatDate(i,privateNext){
            //Format eventDate

            if (privateNext) {
                //format rules
                var momentPrivateNext = moment(privateNext);
                //output the result on page
                momentPrivateNext.locale('ru');
                var momentPrivateNextRU = momentPrivateNext.format("DD MMM");


                //insert each into
                listOfClasses[i].innerText=momentPrivateNextRU;
            }


        }

        //create array of listed dates
        var listOfClasses = document.getElementsByClassName('privateNextDate');
        for (var i = 0; i < listOfClasses.length; i++) {
            //pass each into function
            formatDate(i, listOfClasses[i].innerText);
        }


    </script>
</body>

</html>