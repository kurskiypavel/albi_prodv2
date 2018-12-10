<?php


//PRODUCTION
 $host = null;
 $user = "root";
 $password = "c484b8572c29633e048871c2ee39144cc161835e9041f502";
 $database = "albiRU";
 $port = null;

 $conn = new mysqli($host,$user,$password, $database,$port);
 $conn->set_charset("utf8");
 if(mysqli_connect_error()){
     echo mysqli_connect_error();
     exit();
 }

//LOCALHOST
//$host = null;
//$user = "root";
//$password = "root";
//$database = "playground";
//$port = null;
//$socket = "/Applications/MAMP/tmp/mysql/mysql.sock";
//$conn = new mysqli($host, $user, $password, $database, $port, $socket);
//if (mysqli_connect_error()) {
//    echo mysqli_connect_error();
//    exit();
//};


?>
