<?php

// Connection with DB
require_once '../config.php';


$user_id = $_POST['user_id'];
$message = htmlspecialchars(trim($_POST['message']));
$conversation = $_POST['conversation'];
if ($conversation == null) {
    $conversation = $author = $user_id;
} else {
    $author = $user_id;
}

$readed = 0;

$created_at = date("Y-m-d H:i:s");

$query = "INSERT INTO messages VALUES (null,'$conversation','$author','$message','$created_at',$readed) ";
$conn->query($query);

$conn->query("SET lc_time_names = 'ru_RU'");
$query = "SELECT first_name from users where id=$user_id";
$result = $conn->query($query);
$obj = $result->fetch_object();

if ($user_id == '1') {
    echo '<li class="teacher"><p class="message">' . $message . '</p><p class="info">' . $obj->first_name . ' Сейчас</p></li>';
} else {
    echo '<li class="student"><p class="message">' . $message . '</p><p class="info">' . $obj->first_name . ' Сейчас</p></li>';
}

