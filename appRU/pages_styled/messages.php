<?php

// Connection with DB
require_once '../config.php';

//$user_id = $_GET['user_id'];
session_start();
$user = $_SESSION['user_id'];
// attempt to find passed user in DB
$conn->query("SET lc_time_names = 'ru_RU'");
$query = "SELECT messages.`author`,messages.`message`,DATE_FORMAT(messages.`created_at`, '%e %b') AS date, users.`first_name` name FROM messages JOIN users ON messages.author = users.id WHERE conversation='$user' ORDER BY messages.`created_at` ASC";
$result = $conn->query($query);
//if (!$result) die($conn->connect_error);
$rows = $result->num_rows;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script
            src="//code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/css/styleApp.css">
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
          crossorigin="anonymous">
    <?php require_once '../parts/header.php';?>
</head>
<body>
<div class='messagesPage'>
    <div class="messages">
        <div class="header">
            <h3>Сообщения</h3>
        </div>
        <ul id="iok">
            <?php
            if ($rows) {
                for ($i = 0; $i < $rows; ++$i) {
                    $result->data_seek($i);
                    $obj = $result->fetch_object();
                    if ($obj->author === '1') {
                        echo '<li class="teacher"><p class="message">' . $obj->message . '</p>';
                        echo '<p class="info">' . $obj->name . '  ' . $obj->date . '</p></li>';
                    } else {
                        echo '<li class="student"><p class="message">' . $obj->message . '</p>';
                        echo '<p class="info">' . $obj->name . '  ' . $obj->date . '</p></li>';
                    }

                }
            } else {
                echo '<p class="noMessages">У вас пока нет сообщений</p>';
            }
            ?>

        </ul>
        <div class="newMessage">
            <textarea name="" placeholder='Сообщение...' class='messageInput' id=""></textarea>
            <button id="send">Отправить</button>
        </div>
    </div>

    <?php include_once '../parts/footer.php' ?>
</div>

<script>
    // scroll to the bottom of list

    let full = document.getElementById('iok').scrollHeight;
    $('ul').scrollTop(full);
</script>
<script>

    function append(data) {

        // append to the end of ul
        //apply some easing
        //scroll to this new message
        if($('.noMessages').css('display') ==='block'){
            $('.noMessages').css('display','none');
        }
        $(data).appendTo('ul');
        let full = document.getElementById('iok').scrollHeight;
        $('ul').scrollTop(full);
        $('textarea').val('');
        location.reload();
    }

    function send(params) {
        
        var user_id = '<?php echo $user;?>';
        var message = $('.messageInput').val();
        if(!message){
            return false;
        }
        // ajax send message
        $.ajax({
            type: "POST",
            url: "../ajax/send.php",
            data: {
                user_id: user_id,
                message: message
            },
            success: function (data) {
//                alert('success');
                append(data);
            },
            error: function () {
//                alert('failure');
            }
        });
    }


    $('#send').click(send);
</script>


</body>
</html>
