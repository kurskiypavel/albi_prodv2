<?php

// Connection with DB
require_once '../config.php';
require_once '../classes/notificationClass.php';

//class
$classNotification = new notificationClass($conn);

$notification = $_POST['notification'];
$active = $_POST['active'];

//read clicked notification
if($_POST && $active=='true') {
    $classNotification->read($notification);
} else {
    $classNotification->delete($notification);
}