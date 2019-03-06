<?php
/**
 * Created by PhpStorm.
 * User: GSalmela
 * Date: 2018-10-02
 * Time: 12:02 PM
 */


$thisPage = str_ireplace(array('-', '.php'), array(' ', ''), basename($_SERVER['PHP_SELF']));
if ($user != '1') {

    $query = "SELECT id FROM `notifications-booking` WHERE owner='$user' and readed is null";
    $result = $conn->query($query);
    $rows = $result->num_rows;
    if ($rows) {
        $newNotification = '<div class="notification" style="height: 8px;position: absolute;background: #fea400;width: 8px;border-radius: 50%;right: 25px;top: 4px;"></div>';
    } else {
        $query = "SELECT id from messages where author = '1' and conversation = '$user' and readed = 0";
        $result = $conn->query($query);
        $rows = $result->num_rows;
        if ($rows) {
            $newNotification = '<div class="notification mail" style="height: 8px;position: absolute;background: #fea400;width: 8px;border-radius: 50%;right: 25px;top: 4px;"></div>';
        }
    }
} else {
    $query = "SELECT id from messages where author != '1' and readed = 0";
    $result = $conn->query($query);
    $rows = $result->num_rows;

    if ($rows) {
        $newNotification = '<div class="notification mail" style="height: 8px;position: absolute;background: #fea400;width: 8px;border-radius: 50%;right: 25px;top: 4px;"></div>';
    }
}

echo '

<div class="margy"></div>
<div class="footerBar">
        <ul>
            <li class="' . ($thisPage == 'programs' ? 'active' : '') . '">
                <a href="programs.php?user=' . $user . '">';
if ($thisPage == 'programs') {
    echo '<img  src="../../assets/images/App/foot-sun.png" alt="" srcset="">';
} else {
    echo '<img  src="../../assets/images/App/foot-grey.png" alt="" srcset="">';
}
echo '
                    <p>Программы</p>
                </a>
            </li>
            <li class="' . ($thisPage == 'bookPrivateEvent' ? 'active' : '') . '">
                <a href="bookPrivateEvent.php?user=' . $user . '&student=' . $user . '&page=' . $page . '">
                    <i class="far fa-clock"></i>
                    <p>Урок с Albi</p>
                </a>
            </li>
            <li class="' . ($thisPage == 'events' ? 'active' : '') . '">
                <a href="events.php?user=' . $user . '">
                    <i class="far fa-calendar-alt"></i>
                    <p>Мои занятия</p>
                </a>
            </li>
            <li class="' . ($thisPage == 'user' ? 'active' : '') . '">
            <!--If notification show below-->
            ' . $newNotification . '
            <!--If notification show above-->
                <a href="user.php?user=' . $user . '">
                    <i class="fas fa-user"></i>
                    <p>Профиль</p>
                </a>
            </li>
        </ul>
    </div>';

echo "<script>
let is_safari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
if(is_safari){
let inputs = document.querySelectorAll('input:not(.gray)');
let buttons = document.querySelectorAll('button:not(.book)');

[].forEach.call(inputs, function(input) {

    input.style.paddingTop = '5px';
});

[].forEach.call(buttons, function(button) {

    button.style.paddingTop = '5px';
});
}
</script>";


?>