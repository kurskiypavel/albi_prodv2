<?php
// Connection with DB
require_once 'appRU/config.php';

// Define variables and initialize with empty values
$phone = $password = $confirm_password = "";
$phone_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if phone is empty
    if (empty(trim($_POST["phone"]))) {
        $phone_err = 'Please enter phone';
    } else {
        $phone = trim($_POST["phone"]);

    }

    // validate password
    if (empty(trim($_POST['password']))) {
        $password_err = "Please enter a password";
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST['password']);
    }

    // validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = 'Please confirm password';
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if ($password != $confirm_password) {
            $confirm_password_err = 'Password did not match.';
        }
    }

    // Validate credentials
    if (empty($phone_err) && empty($password_err)) {
        // prepare a select statement
        $sql = "SELECT id, phone FROM users WHERE phone = ?";

        if ($stmt = $conn->prepare($sql)) {
            // bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_phone);
            // set parameters
            $param_phone = $phone;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // store result
                $stmt->store_result();

                // Check if phone exists, if yes then overwritepassword
                if ($stmt->num_rows == 1) {

                    // check input errors before inserting in database
                    if (empty($phone_err) && empty($password_err) && empty($confirm_password_err)) {
                        // prepare an insert statement
                        $sql = "UPDATE users SET password = ? WHERE phone = ?";

                        if ($stmt = $conn->prepare($sql)) {
                            // bind variables to the prepared statement as parameters
                            $stmt->bind_param("ss", $param_password, $param_phone);
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
                                header("location: appRU/pages_styled/programs.php?user=" . $user_id);
                            }
                        }
                        // close statement
                        $stmt->close();
                    }


                } else {
                    // display an error message if phone doesn't exist
                    $phone_err = 'No account found with that phone';
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recover password</title>
    <link href="https://cdn.jsdelivr.net/npm/flexiblegrid@v1.2.2/dist/css/flexible-grid.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/styleApp.css">
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
          crossorigin="anonymous">

</head>
<body style="background: unset;">

<div class="forgotPage">
    <div class="header">
        <a id='backHome' href='http://178.128.238.166/index.html'><i class="fas fa-arrow-left"></i></a>
        <h3>Recover password</h3>
    </div>


    <form class='form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <div class="slide1">
            <p class='label'>Enter your mobile phone</p>
            <input id='yourphone2' type="tel" class='gray' name="phone" value="<?php echo $phone; ?>">
            <span class="error phone"><?php echo $phone_err; ?></span>

            <button class='buttonNext'>Next <i class="fas fa-angle-right"></i></button>
        </div>

        <div style='display:none;' class="slide2">
            <p class='label'>Enter password</p>
            <input class='password' type="password" name="password" value="<?php echo $password; ?>">
            <span class="error"><?php echo $password_err; ?></span>

            <p class='label'>Confirm password</p>
            <input class='password' type="password" name="confirm_password"
                   value="<?php echo $confirm_password; ?>">
            <span class="error"><?php echo $confirm_password_err; ?></span>

            <button class='buttonRecover' type="submit">Recover</button>
        </div>
    </form>

    <p class='dont'>Don't have an account?</p>
    <button class='buttonRegister' href="register.php">Register</button>
    
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



    $('.buttonNext').click(function (e) {
        e.preventDefault();

        var phone=$('#yourphone2').val();

        if(phone == ""){
            $('.error.phone').text('Please enter phone');
        } else {
            $('.slide1').css('display', 'none');
            $('.slide2').css('display', 'block');
        }

    })

</script>

</body>
</html>