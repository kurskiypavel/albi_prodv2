<?php


include_once 'config.php';

$CC = $_POST['CC'];
$pNumber = $_POST['pNumber'];


$phone =$CC.$pNumber;

$param_pNumber = preg_replace('/[^0-9.]+/', '', $phone);

// prepare an insert statement
$sql = "INSERT INTO phones (phone) VALUES (?)";

if ($stmt = $conn->prepare($sql)) {
    // bind variables to the prepared statement as parameters
    $stmt->bind_param("s", $param_pNumber);
    // attempt to execute the prepared statement
    $stmt->execute();
}
// close statement
$stmt->close();