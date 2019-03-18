<?php

// Connection with DB
require_once '../config.php';


// attempt to find passed user in DB
$conn->query("SET lc_time_names = 'ru_RU'");
$query = "SELECT messages.`conversation`,messages.`message`,DATE_FORMAT(messages.`created_at`, '%e %b') AS date, users.`first_name` name FROM (SELECT m1.*
FROM messages m1 LEFT JOIN messages m2
 ON (m1.conversation = m2.conversation AND m1.id < m2.id)
WHERE m2.id IS NULL)  messages JOIN users ON messages.conversation = users.id  ORDER BY messages.`created_at` DESC";
$result = $conn->query($query);
$rows = $result->num_rows;




?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Albi | Admin messages</title>
    <link rel="icon" href="../../assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <style>
        a {
            color: unset;
            text-decoration: none;
            border-bottom: 1px solid grey;
            display: block;
            padding:  10px;
            font-family:Arial;

        }
        .name{
            display: inline-block;
            font-weight:bold;
            font-size:16px;
            color: #3E3C3C;
        }
        .message{
            display: block;
            margin-top: 10px;
            color: #707070;
            font-size:14px;
        }
        .date{
            display: inline-block;
            position: absolute;
            right: 10px;
            font-size:14px;
            color: #707070;
        }
        .myMessages{
            color: #F68170;
            position: absolute;
            right: 65px;
            display: inline-block;
            font-size: 14px;
        }
    </style>
</head>

<body>

<?php
if ($rows) {
    for ($i = 0; $i < $rows; ++$i) {
        $result->data_seek($i);
        $obj = $result->fetch_object();
        echo '<a href="conversationAdmin.php?conversation=' . $obj->conversation . '">
                <p class="name">' . $obj->name . '</p>
                <p class="date">' . $obj->date . '</p>';

                // get if any written by user from this conversation unreaded by author
                $queryNew= "SELECT id from messages where author != '1' and readed = 0 and conversation='$obj->conversation'";
                $resultNew = $conn->query($queryNew);
                $rowsNew = $resultNew->num_rows;
                if ($rowsNew) {
                    echo '<p class="myMessages">Новое</p>';
                }

                echo '<p class="message">' . $obj->message . '</p>
                
                </a>';
    }
} else {
    echo '<p class="noMessages">У вас пока нет сообщений.</p>';
}
?>
<script
        src="//code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
<script>

    // recolor Мои сообщение если есть непрочитанные
    // если колокольчик неактивный но есть кружок в навигации то перекрасить
    if($('.myMessages').text() === '0'){
        $('.myMessages').css('display','inline-block');
    }
</script>
</body>

</html>