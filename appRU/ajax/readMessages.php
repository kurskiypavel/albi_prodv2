<?php

// Connection with DB
require_once '../config.php';
require_once '../classes/messageClass.php';

//class
$classMessage = new messageClass($conn);

$user_id = $_POST['user_id'];
$conversation = $_POST['conversation'];

if ($conversation == null) {
    $conversation  = $author = $user_id;
}  else {
    $author = $user_id;
}

//read all unreaded messages for this conversation
if($_POST) {
    $classMessage->readAll($author,$conversation);
}