<?php


phpinfo();

require_once '../config.php';

$query = "SELECT @@character_set_database, @@collation_database";
$result = $conn->query($query);
$rows = $result->num_rows;
$obj = $result->fetch_object();
var_dump($conn->get_charset());
