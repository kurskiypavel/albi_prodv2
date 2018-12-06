<?php
/**
 * Created by PhpStorm.
 * User: GSalmela
 * Date: 2018-10-02
 * Time: 12:02 PM
 */


$thisPage = str_ireplace(array('-', '.php'), array(' ', ''), basename($_SERVER['PHP_SELF']) );


echo '


<div class="footerBar">
        <ul>
            <li class="'.($thisPage == 'programs' ? 'active' : '').'">
                <a href="programs.php?user='.$user.'">';
                if($thisPage=='programs'){
                    echo '<img  src="../../assets/images/App/foot-sun.png" alt="" srcset="">';
                }else{
                    echo '<img  src="../../assets/images/App/foot-grey.png" alt="" srcset="">';
                }
                echo '
                    <p>Программы</p>
                </a>
            </li>
            <li class="'.($thisPage == 'bookPrivateEvent' ? 'active' : '').'">
                <a href="bookPrivateEvent.php?user='.$user.'&student='.$user.'&page='.$page.'">
                    <i class="far fa-clock"></i>
                    <p>Урок с Albi</p>
                </a>
            </li>
            <li class="'.($thisPage == 'events' ? 'active' : '').'">
                <a href="events.php?user='.$user.'">
                    <i class="far fa-calendar-alt"></i>
                    <p>Мои занятия</p>
                </a>
            </li>
            <li class="'.($thisPage == 'user' ? 'active' : '').'">
                <a href="user.php?user='.$user.'">
                    <i class="fas fa-user"></i>
                    <p>Профиль</p>
                </a>
            </li>
        </ul>
    </div>';




?>