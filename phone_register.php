<?php
// Connection with DB
require_once 'appRU/config.php';
// define variables and initialize with empty values
$phone = $first_name = $password = $confirm_password = "";
$phone_err = $password_err = $confirm_password_err = $param_password = "";

// processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // validate phone
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Пожалуйста введите номер телефона";
    } else {
        // prepare a select statement
        $sql = "SELECT id FROM users WHERE phone = ?";

        if ($stmt = $conn->prepare($sql)) {
            // bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_phone);
            // set parameters
            $param_phone = trim($_POST["phone"]);

            // attempt to execute the prepared statement
            if ($stmt->execute()) {
                // store result
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $phone_err = "Этот номер уже используется";
                } else {
                    $phone = trim($_POST["phone"]);
                }
            }
        }
        // close statement
        $stmt->close();
    }

    // validate password
    if (empty(trim($_POST['first_name']))) {
        $name_err = "Пожалуйста введите имя";
    } else {
        $first_name = htmlspecialchars(trim($_POST["first_name"]));
    }

    // validate password
    if (empty(trim($_POST['password']))) {
        $password_err = "Пожалуйста введите пароль";
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $password_err = "Пароль должен содержать минимум 6 символов";
    } else {
        $password = trim($_POST['password']);
    }

    // validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = 'Пожалуйста подтвердите пароль';
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if ($password != $confirm_password) {
            $confirm_password_err = 'Пароли не совпали';
        }
    }

    // check input errors before inserting in database
    if (empty($phone_err) && empty($password_err) && empty($confirm_password_err)) {


        // prepare an insert statement
        $sql = "INSERT INTO users (first_name,phone, password) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $first_name, $param_phone, $param_password);
            // set parameters
            $param_phone = $phone;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // attempt to execute the prepared statement
            if ($stmt->execute()) {
                //get new user id
                // prepare a select statement
                $sql = "SELECT id FROM users WHERE phone = ?";

                if ($stmt = $conn->prepare($sql)) {
                    // bind variables to the prepared statement as parameters
                    $stmt->bind_param("s", $param_phone);
                    // set parameters
                    $param_phone = $phone;

                    // attempt to execute the prepared statement
                    if ($stmt->execute()) {
                        // store result
                        $stmt->store_result();

                        // check if phone exists, if yes then verify password
                        if ($stmt->num_rows == 1) {
                            // bind result variables
                            $stmt->bind_result($user_id);

                            if ($stmt->fetch()) {
                                $_SESSION['user_id'] = $user_id;
                            }
                        }
                    }
                }

                //check if session
                if (!$_SESSION['phone']) {
                    //new session
                    session_start();
                    $_SESSION['phone'] = $phone;
                    $_SESSION['user_id'] = $user_id;
                }
                // redirect to home page
//                header("location: appRU/pages_styled/programs.php?user=" . $user_id);
                echo "<script>location.href = 'appRU/pages_styled/programs.php?user=" . $user_id . "';</script>";
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
    <title>Albi | Регистрация</title>
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/flexiblegrid@v1.2.2/dist/css/flexible-grid.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/styleApp.css">
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
          crossorigin="anonymous">



</head>
<body style="background: unset;">

<!--<div class="registerPage">-->
<div class="phoneRegisterPage">
    <div class="header">
        <a id='backHome' href='http://albi.yoga/index.html'><i class="fas fa-arrow-left"></i></a>
        <h3>Регистрация</h3>
    </div>


    <form class='form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <div class="slide1">
            <p class='label'>Введите ваш номер телефона</p>
            <input id='yourphone2' type="tel" class='gray' name="phone" value="<?php echo $phone; ?>">
            <span class="error phone"><?php echo $phone_err; ?></span>

            <p class='label'>Введите имя</p>
            <input class='gray first_name' type="text" name="first_name" value="<?php echo $first_name; ?>">
            <span class="error name"><?php echo $name_err; ?></span>

            <button class='buttonNext'>Дальше <i class="fas fa-angle-right"></i></button>
        </div>

        <div style='display:none;' class="slide2">
            <p class='label'>Введите пароль</p>
            <input class='password' type="password" name="password" value="<?php echo $password; ?>">
            <span class="error"><?php echo $password_err; ?></span>

            <p class='label'>Подтвердите пароль</p>
            <input class='password' type="password" name="confirm_password"
                   value="<?php echo $confirm_password; ?>">

            <span class="error"><?php echo $confirm_password_err; ?></span>
            <button class='buttonRegister' type="submit">Зарегистрироваться</button>
        </div>


    </form>
    <button class='buttonLogin'>Войти в аккаунт</button>
</div>


<script
        src="//code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
<script src='//s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js'></script>
<script src="assets/js/phoneMask.js"></script>

<script>
    $('.buttonLogin').click(function () {
        location.href = 'phone_login.php';
    });

    $('.buttonNext').click(function (e) {
        e.preventDefault();

        var name = $('.first_name').val();
        var phone = $('#yourphone2').val();

        if (name == "" && phone == "") {
            $('.error.name').text('Пожалуйста введите имя');
            $('.error.phone').text('Пожалуйста введите номер телефона');
        } else if (name == "") {
            $('.error.name').text('Пожалуйста введите имя');
        } else if (phone == "") {
            $('.error.phone').text('Пожалуйста введите номер телефона');
        } else {
            $('.slide1').css('display', 'none');
            $('.slide2').css('display', 'block');
        }


    })

</script>

</body>
</html>