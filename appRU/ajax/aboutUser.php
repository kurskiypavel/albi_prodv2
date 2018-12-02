<?php

// Connection with DB
require_once '../config.php';
require_once '../classes/userClass.php';

//class
$classUser = new userClass($conn);

$id = $_POST['id'];
$about = $_POST['about'];

//add and delete favorite program
if($_POST) {
    $classUser->updateAbout($id,$about);
}