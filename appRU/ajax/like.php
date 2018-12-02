<?php

// Connection with DB
require_once '../config.php';
require_once '../classes/programClass.php';

//class
$classProgram = new programClass($conn);

$user = $_POST['user'];
$id = $_POST['id'];

//add and delete favorite program
if($_POST) {
    $classProgram->addToFavorite($user, $id);
}