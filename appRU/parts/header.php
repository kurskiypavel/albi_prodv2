<?php

echo '
    <script>
        if (window.innerWidth > 767) {
            location.href="/splash.html";
        }
    </script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(51443365, "init", {
        id:51443365,
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/51443365" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->';


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
                <h3 class="unlogged">Пожалуйста войдите или зарегистрируйтесь чтобы увидеть контент</h3>
                
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