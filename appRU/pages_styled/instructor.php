<?php

// Connection with DB
require_once '../config.php';


//get id of clicked item from url

$id = $_GET['id'];
//$user = $_GET['user'];
session_start();
$user = $_SESSION['user_id'];


require_once '../parts/header.php';




//select user data
//$query = "SELECT * FROM users WHERE id='$id'";
$query = "SELECT users.* , count(programs.id) programsCount
    FROM users
    JOIN programs ON users.id = programs.instructor_id WHERE users.id='$id'";
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
    <title>Albi | Учитель</title>
    <link rel="icon" href="../../assets/images/favicon.ico" type="image/x-icon">
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

<body class="instructorPage">
    <div class="header">
        <h3>Учитель</h3>

    </div>
    <div class="body">
        <div class="card">
            <div class="subInstructor">
                <div class="headerInstructor" style="background-image: url(../../assets/images/App/how_to_become_a_yoga_instructor.png);">
                </div>
                <div class="body">
                    <h3>Альбина Курская</h3>
                </div>
            </div>
        </div>
        <div class="infoContainer">
            <div class="stats">
                <ul>
                    <li>
<!--                        --><?php //echo '<p>Предлагает <span class="bold">' . $obj->programsCount . '</span> программ' . ($obj->programsCount > 1 && (($obj->programsCount % 2)!=1 && $obj->programsCount < 4) ? 'ы </p>' : 'у</p>');?>
                        <p>Предлагает <span class="bold">7</span> программ</p>
                    </li>
                </ul>
            </div>
            <div class="bookDiv">
                <?php echo '<a class="book" href="bookPrivateEvent.php?user=' . $user . '&student=' . $user . '&instructor=' . $id . '">Взять частный урок</a>';?>
            </div>

            <div class="about">
                <h3>Об учителе</h3>
                <p><?php echo $obj->about; ?></p>
            </div>



            <div class="contactInstructor">
                <a href='contact-instructor.php?user=<?php echo $user; ?>&page=instructor&instructor=<?php echo $id; ?>&student=<?php echo $user; ?>'>
                    <p>Связаться с учителем</p></a>
                <i class="far fa-comment"></i>
            </div>
        </div>
    </div>
    <?php include_once '../parts/footer.php' ?>

    <script
			  src="//code.jquery.com/jquery-3.3.1.min.js"
			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
			  crossorigin="anonymous"></script>
</body>

</html>