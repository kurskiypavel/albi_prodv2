<?php

// Connection with DB
require_once '../config.php';
require_once '../classes/notificationClass.php';

//class
$classNotification = new notificationClass($conn);

$author = $_POST['user_id'];


//read all unreaded notifications for this user
if($_POST) {
    $classNotification->readAll($author);
}