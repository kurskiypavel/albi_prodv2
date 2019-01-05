<?php



?>



<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Albi | Регистрация</title>
    <link href="https://cdn.jsdelivr.net/npm/flexiblegrid@v1.2.2/dist/css/flexible-grid.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/styleApp.css">
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
          crossorigin="anonymous">


</head>
<body style="background: unset;">

<div class="loginPage">
    <div class="header">
        <a id='backHome' href='index.html'><i class="fas fa-arrow-left"></i></a>
        <h3>Регистрация</h3>
    </div>

    <!--Add buttons to initiate auth sequence and sign out-->
    <a class='loginWith' id="authorize_button" style="display: none;"><img src="assets/images/google_icon.png" alt=""> <p>Продолжить с Google</p></a>
    <button id="signout_button" style="display: none;">Sign Out</button>
    <button class='loginWith' onclick="location.href = 'phone_register.php'" class=''>По номеру телефона</button>

    <pre id="content" style="white-space: pre-wrap;"></pre>

    <!-- <p class='dont'>Новый участник?</p>
    <button onclick="location.href = 'register.php'" class='buttonRegister'>Зарегистрироваться</button> -->

</div>

<script
        src="//code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

<script src="assets/js/gapi.js"></script>

<script async defer src="https://apis.google.com/js/api.js" onload="this.onload=function(){};handleClientLoad()"
        onreadystatechange="if (this.readyState === 'complete') this.onload()">
</script>


</body>
</html>