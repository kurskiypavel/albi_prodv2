<?php

// Connection with DB
require_once '../config.php';


$user_id = $_POST['user_id'];
$message =  htmlspecialchars(trim($_POST['message']));
$conversation = $author = $user_id;
$created_at = date("Y-m-d H:i:s");

$query = "INSERT INTO messages VALUES (null,'$conversation','$author','$message','$created_at') ";
$conn->query($query);

$conn->query("SET lc_time_names = 'ru_RU'");
$query = "SELECT first_name from users where id=$user_id";
$result = $conn->query($query);
$obj = $result->fetch_object();

echo '<li class="student"><p class="message">' . $message . '</p><p class="info">' . $obj->first_name . ' Сейчас</p></li>';