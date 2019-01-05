<?php




//LOCALHOST
$host = null;
$user = "root";
$password = "root";
$database = "playground";
$port = null;
$socket = "/Applications/MAMP/tmp/mysql/mysql.sock";
$conn = new mysqli($host, $user, $password, $database, $port, $socket);
if (mysqli_connect_error()) {
    echo mysqli_connect_error();
    exit();
};


?>
