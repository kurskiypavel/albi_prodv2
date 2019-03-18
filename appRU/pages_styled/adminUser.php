<?php

// Connection with DB
require_once '../config.php';
require_once '../classes/userClass.php';


//get id of clicked item from url

//$user = $_GET['user'];

session_start();
$user = $_SESSION['user_id'];




$obj = new userClass($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $birthdate = htmlspecialchars($_POST['birthdate']);
    $location = htmlspecialchars($_POST['location']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);





    //update user account
    $obj->update($user, $first_name, $last_name, $birthdate, $location, $email, $phone);
//    $obj->update($user, $first_name);
//    echo "<script>location.href = 'user.php?user=" . $user . "';</script>";
}


$query = "SELECT * FROM users WHERE id='$user' OR googleID='$user'";
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
    <title>Albi | Настройки</title>
    <link rel="icon" href="../../assets/images/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/flexiblegrid@v1.2.2/dist/css/flexible-grid.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/styleApp.css">
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
          crossorigin="anonymous">
    <?php require_once '../parts/header.php';?>

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
<div class="settingsPage">
    <div class="header">

        <a href='user.php?user=<?php echo $user;?>'><i class="fas fa-arrow-left"></i></a>
        <h3>Настройки</h3>
    </div>

    <div class="set">
        <h4>Ваш профиль</h4>
    </div>
    <form method="post">
        <ul class="settingsContent">
            <li>
                <div class="subContent">
                    <span class="bold">Имя</span>
                    <input name='first_name' value="<?php echo $obj->first_name; ?>" type="text" placeholder="—">
                </div>
            </li>
            <li>
                <div class="subContent">
                    <span class="bold">Фамилия</span>
                    <input name='last_name' value="<?php echo $obj->last_name; ?>" type="text" placeholder="—">
                </div>
            </li>

            <li>
                <div class="subContent">
                    <span class="bold">Дата рождения</span>
                    <input name='birthdate' value="<?php echo $obj->birthdate; ?>" type="text" placeholder="—">
                </div>
            </li>
            <li>
                <div class="subContent">
                    <span class="bold">Адрес</span>
                    <input name='location' value="<?php echo $obj->location; ?>" type="text" placeholder="—">
                </div>
            </li>
            <li>
                <div class="subContent">
                    <span class="bold">Email</span>
                    <input name='email' value="<?php echo $obj->email; ?>" type="text" placeholder="—">
                </div>
            </li>
            <li>
                <div class="subContent">
                    <span class="bold">Телефон</span>
                    <input id='yourphone2'  type="tel" name='phone' value="<?php echo $obj->phone; ?>"  placeholder="—">
                </div>
            </li>

        </ul>
        <div style='display:none;' class="changesNav">
            <!-- <input name="update" type="submit" value="Done"> -->
            <a id='done'>Принять</a>
            <a id='cancel' href="user.php?user=<?php echo $user; ?>">Отменить</a>
        </div>
    </form>
    <?php include_once '../parts/footer.php' ?>
</div>

<script
			  src="//code.jquery.com/jquery-3.3.1.min.js"
			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
              crossorigin="anonymous"></script>
              <script src='//s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js'></script>
<!--<script src="../../assets/js/phoneMask.js"></script>-->

<script>

//Show Save x Cancel buttons on Page:adminUser.php Field:form
// event on typing
$('.settingsPage form').keypress(function () {
    
    // hide back arrow
    $('.fa-arrow-left').css('opacity','0');
    //show upload button
    $('.changesNav').css('display','block');
});
// event on changing
$('.settingsPage form').change(function () {
    // hide back arrow
    $('.fa-arrow-left').css('opacity','0');
    //show upload button
    $('.changesNav').css('display','block');
});

//Refresh form and rollback all changes on Page:users.php Field:personal information
// if Cancel button pressed on Avatar form - reload page
$('#cancel').click(function () {
    location.reload();
});

$('#done').click(function () {
    $('form').submit();
});
</script>

</body>

</html>