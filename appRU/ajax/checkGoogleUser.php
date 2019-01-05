<?php

// Connection with DB
require_once '../config.php';




$googleID = $_GET['googleID'];


// attempt to find passed user in DB


$query = "SELECT id FROM users WHERE googleID='$googleID'";
$result = $conn->query($query);
//if (!$result) die($conn->connect_error);
$rows = $result->num_rows;


if($rows){
    echo $rows;
}