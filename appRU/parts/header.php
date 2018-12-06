<?php


if (!$user) {
//    echo 'Please login or register to see the content';
//    echo '<a href="../../login.php">Login</a>';
//    echo '<a href="../../register.php">Register</a>';

    echo '<!DOCTYPE html>
        <html lang="ru">
        
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Albi | Нет доступа</title>
            <link href="https://cdn.jsdelivr.net/npm/flexiblegrid@v1.2.2/dist/css/flexible-grid.min.css" rel="stylesheet">
            <link rel="stylesheet" href="/assets/css/style.css">
            <link rel="stylesheet" href="/assets/css/reset.css">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
                  integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
                  crossorigin="anonymous">
        </head>
        
        <body>
        <div class="page">
        
            <div class="slider">
                <img class=\'logo\' src="/assets/images/logo.svg" alt="">
            </div>
            <div class="main">
                <h3>Пожалуйста войдите или зарегистрируйтесь чтобы увидеть контент</h3>
                
                <div class="form">
                    <button onclick="location.href = \'/register.php\'" class="register">Зарегистрироваться</button>
                    <button onclick="location.href = \'/login.php\'" class="login">Войти в аккаунт</button>
                    <span class=\'contactUs\'>
                        <a href="mailto:Albina.kurskaya@gmail.com?Subject=Question" target="_top">
                            Написать в Albi
                        </a>
                    </span>
                </div>
            </div>
        </div>
        
        
        </body>
        
        </html>';
    exit;
} ?>