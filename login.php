<?php
// Connection with DB
require_once 'appRU/config.php';

// Define variables and initialize with empty values
$phone = $password = "";
$phone_err = $password_err = "";

// Processing form data when form is submitted

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if phone is empty
    if (empty(trim($_POST["phone"]))) {
        $phone_err = 'Пожалуйста введите номер телефона';
    } else {
        $phone = trim($_POST["phone"]);
    }

    // Check if password is empty
    if (empty(trim($_POST['password']))) {
        $password_err = 'Пожалуйста введите пароль';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate credentials
    if (empty($phone_err) && empty($password_err)) {
        // prepare a select statement
        $sql = "SELECT id, phone, password FROM users WHERE phone = ?";

        if ($stmt = $conn->prepare($sql)) {
            // bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_phone);
            // set parameters
            $param_phone = $phone;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // store result
                $stmt->store_result();

                // Check if phone exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // bind result variables
                    $stmt->bind_result($user_id, $phone, $hashed_password);

                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            /* password is correct, so start a new session and
                            save the phone to the session */
                            session_start();
                            $_SESSION['phone'] = $phone;
                            $_SESSION['user_id'] = $user_id;
                            header("location: appRU/pages_styled/events.php?user=" . $user_id);
                        } else {
                            // display an error message if password is not valid
                            $password_err = 'Пароль не подходит';
                        }
                    }
                } else {
                    // display an error message if phone doesn't exist
                    $phone_err = 'Телефон не найден';
                }
            }
        }
        // close statement
        $stmt->close();
    }
    // close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Albi | Войти</title>
    <link href="https://cdn.jsdelivr.net/npm/flexiblegrid@v1.2.2/dist/css/flexible-grid.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/styleApp.css">
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
          crossorigin="anonymous">


</head>
<body style="background: unset;">

<div class="loginPage">
    <div class="header">
        <a id='backHome' href='http://albi.yoga/index.html'><i class="fas fa-arrow-left"></i></a>
        <h3>Войти</h3>
    </div>


    <form class='form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <div class="slide1">

            <p class='label'>Введите ваш номер телефона</p>
            <input id='yourphone2' type="tel" class='gray' name="phone" value="<?php echo $phone; ?>">
            <span class="error phone"><?php echo $phone_err; ?></span>
            <button type="button" class='buttonNext'>Дальше <i class="fas fa-angle-right"></i></button>

        </div>
        <div style='display:none;' class="slide2">

            <p class='label'>Введите пароль</p>
            <input class='password' type="password" name="password">
            <span class="error">
                            <?php echo $password_err; ?>
                        </span>


            <input class='buttonLogin' type="submit" value="Войти">
            <button class='buttonForgot' href="forgot.php">Забыли пароль?</button>

        </div>
    </form>

    <p class='dont'>Новый участник?</p>
    <button class='buttonRegister' href="register.php">Зарегистрироваться</button>

</div>

<script
        src="//code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
<script src='//s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js'></script>
<script src="assets/js/phoneMask.js"></script>

<script>
    $('.buttonRegister').click(function (e) {
        e.preventDefault();
        location.href = 'register.php';
    });

    $('.buttonForgot').click(function (e) {
        e.preventDefault();
        location.href = 'forgot.php';
    });

    $('.buttonNext').click(function (e) {
        e.preventDefault();
        var phone = $('#yourphone2').val();

        if (phone == "") {
            $('.error.phone').text('Пожалуйста введите номер телефона');
        } else {
            $('.slide1').css('display', 'none');
            $('.slide2').css('display', 'block');
        }
    });


</script>


</body>
</html>