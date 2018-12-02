<?php

// Connection with DB
require_once '../config.php';
require_once '../classes/programClass.php';


//$user = $_GET['user'];
session_start();
$user = $_SESSION['user_id'];


require_once '../parts/header.php';

$classProgram = new programClass($conn);

//select userd data
$query = "SELECT * FROM users WHERE id='$user'";
$result = $conn->query($query);
//if (!$result) die($conn->connect_error);
$rows = $result->num_rows;
$obj = $result->fetch_object();


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Мой профиль</title>
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

<body class="userPage">
<div class="header">
    <h3>Мой профиль</h3>
    <a href="adminUser.php?user=<?php echo $user; ?>"><i class="fas fa-cog"></i></a>
</div>
<div class="body">
    <div class="card">
        <div class="subInstructor">
            <div class="headerInstructor"
                 style="background-image: url(../../assets/images/App/user-images/<?php echo $obj->avatar; ?>);">
            </div>
            <div class="body">
                <h3><?php echo $obj->first_name . ' ' . $obj->last_name ?></h3>
            </div>
        </div>
    </div>
    <div class="infoContainer">
        <div class="stats">
            <ul>
                <!-- <li>
                            <p>17 Lessons Competed</p>
                        </li> -->
                <li>
                    <!-- <p><span class="bold">6</span> Favorites</p> -->
                </li>
                <li>
                    <!--                        <p>Member Since <span class="bold">8 Months Ago</span></p>-->
                    <p>Вы присоединились <span id='joinDate'></span></p>
                </li>
            </ul>
        </div>
        <div class="info">
            <h3>Личная информация</h3>
            <ul>

                <li>
                    <!--                        <p><span class="bold">Birthdate: </span>2011-11-11</p>-->
                    <p><span class="bold">Дата рождения: </span><span id="bdateRU"></span></p>
                    <p class="hide" style="display: none;"><span class="bold">Дата рождения: </span>—/—/— —</p>
                </li>

                <li>
                    <!--                        <p><span class="bold">Location: </span>Madrid, Spain</p>-->
                    <p><span class="bold">Адрес: </span><?php echo $obj->location; ?></p>
                    <p class="hide" style="display: none;"><span class="bold">Адрес: </span>—</p>
                </li>
                <li>
                    <p><span class="bold">Email: </span><?php echo $obj->email; ?></p>
                    <p class="hide" style="display: none;"><span class="bold">Email: </span>—</p>
                </li>
                <li>
                    <!--                        <p><span class="bold">Phone: </span>+1 (289) 830-1724</p>-->
                    <p><span class="bold">Телефон: </span><?php echo $obj->phone; ?></p>
                    <p class="hide" style="display: none;"><span class="bold">Телефон: </span>—</p>
                </li>
            </ul>
        </div>
        <div class="about">
            <h3>Дополнительно</h3>
            <textarea class="aboutContent"
                      placeholder="Расскажите вашему йога-мастеру о себе…"><?php echo $obj->about; ?></textarea>
        </div>
        <div class="favorites">


            <?php
            //        select favorite programs BEGINS
            $queryFavoriteProgram = "SELECT DISTINCT programs.title, `favorite-programs`.program as favoriteId
            FROM programs
            JOIN `favorite-programs` ON programs.id = `favorite-programs`.program WHERE `favorite-programs`.user='$user'";
            $resultFavoriteProgram = $conn->query($queryFavoriteProgram);
            $rowsFavoriteProgram = $resultFavoriteProgram->num_rows;
            if ($rowsFavoriteProgram) {
                echo '
                    <h3>Мои любимые программы</h3>
                    <div class="favoritesList">
                                <ul>';
                for ($i = 0; $i < $rowsFavoriteProgram; ++$i) {
                    $result->data_seek($i);
                    $objFavoriteProgram = $resultFavoriteProgram->fetch_object();
                    if ($objFavoriteProgram) {
                        echo '<a href="program.php?id=' . $objFavoriteProgram->favoriteId . '&user=' . $user . '">
                        <li class="row d-flex flex-d-row">
                            <p class="column-10">' . $objFavoriteProgram->title . '</p>
                            <div class="column-2" style="text-align: right;"><i id="' . $objFavoriteProgram->favoriteId . '"class="fas fa-times"></i></div>
                        </li>
                    </a>';
                    }
                }
                echo '            </ul>
                    </div>';
            }

            //        select favorite programs ENDS
            //        end
            ?>

        </div>
    <div style="margin-top: 30px;" class="logout"><a href="../ajax/logout.php">Выйти</a></div>

    </div>

    <div style='display:none;' class="changesNav">
        <!-- <input name="update" type="submit" value="Done"> -->
        <a id='done'>Принять</a>
        <a id='cancel' href="user.php?user=<?php echo $user; ?>">Отменить</a>
    </div>
</div>

<?php include_once '../parts/footer.php' ?>

<script
        src="//code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

<script src="../js/app.js"></script>
<script src="../../assets/js/moment.min.js"></script>
<script>
    //Format birthdate date output
    //grab birthday date
    var birthday = "<?php echo $obj->bdate; ?>";
    if (birthday) {
        //format rules
        var momentBirth = moment(birthday);
        //output the result on page
        momentBirth.locale('ru');
        var momentBirthRU = momentBirth.format("DD MMM YYYY");        
        
        //output the result on page
        $('#bdateRU').text(momentBirthRU);
    }

    //Format join date output from now on Page:product.php Field:product join element
    //grab join date
    var joinDate = '<?php echo $obj->created_at; ?>';
    
        //format rule
        let momentjoinDate = moment(joinDate, "YYYYMMDD").locale('ru').fromNow();
        //output the result on page
        $('#joinDate').text(momentjoinDate);
    
</script>

<script>

    //Show Save x Cancel buttons on Page:adminUser.php Field:form
    // event on typing
    $('textarea').keypress(function () {
        //show upload button
        $('.changesNav').css('display','block');
    });
    // event on changing
    $('textarea').change(function () {
        //show upload button
        $('.changesNav').css('display','block');
    });

    //Refresh form and rollback all changes on Page:users.php Field:personal information
    // if Cancel button pressed on Avatar form - reload page
    $('#cancel').click(function () {
        location.reload();
    });

    $('#done').click(function () {
            var user='<?php echo $user; ?>';
            var about = $('textarea').val();
            if (about != ""){
                $.ajax({
                    type: "POST",
                    url: "../ajax/aboutUser.php",
                    data: {
                        id: user,
                        about: about
                    }
                });
            }

        $('.changesNav').css('display','none');
    });
</script>
</body>

</html>