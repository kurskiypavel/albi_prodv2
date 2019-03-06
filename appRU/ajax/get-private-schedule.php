<?php
/**
 * Created by PhpStorm.
 * User: GSalmela
 * Date: 2018-10-29
 * Time: 1:47 PM
 */
include_once '../config.php';

header('Content-Type: application/json');

$chosenDate = $_POST['chosenDate'];

//var_dump($chosenDate);

$query = "SELECT DISTINCT(time) as time from `private-schedule` WHERE `taken` != '1' && date= '$chosenDate' GROUP BY time";

$result = $conn->query($query);
$rows = $result->num_rows;

$data = $result->fetch_all( MYSQLI_ASSOC );
echo json_encode( $data );

