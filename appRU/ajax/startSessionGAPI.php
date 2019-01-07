<?php
/**
 * Created by PhpStorm.
 * User: pavelkurskiy
 * Date: 2019-01-06
 * Time: 10:24 PM
 */


$user_id = $_POST["googleID"];

if (!$_SESSION['user_id']) {
    //new session
    session_start();
    $_SESSION['user_id'] = $user_id;
}

