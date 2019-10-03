<?php

// Connection with DB
require_once '../config.php';
require_once '../classes/notificationClass.php';

//class
$classNotification = new notificationClass($conn);

$notification = $_POST['notification'];

//read clicked notification
if($_POST) {
    $classNotification->read($notification);
}