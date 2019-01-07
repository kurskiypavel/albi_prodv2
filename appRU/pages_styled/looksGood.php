<?php

// fistname
// last name
// phone
//mail
//restrict marketing

// Google Auth values
$googleID = htmlspecialchars($_GET['googleID']);
$getGivenName = htmlspecialchars($_GET['getGivenName']);
$getFamilyName = htmlspecialchars($_GET['getFamilyName']);
$getEmail = htmlspecialchars($_GET['getEmail']);


// Connection with DB
require_once '../config.php';
// define variables and initialize with empty values
$email = $email_err = "";

// processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Пожалуйста введите email";
    } else {
        // prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";

        if ($stmt = $conn->prepare($sql)) {
            // bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            // set parameters
            $param_email = trim($_POST["email"]);

            // attempt to execute the prepared statement
            if ($stmt->execute()) {
                // store result
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $email_err = "Этот email уже используется";
                    echo $email_err;
                    die();
                } else {
                    $email = trim($_POST["email"]);
                }
            }
        }
        // close statement
        $stmt->close();
    }


    // set parameters

    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $phone = trim($_POST["phone"]);
    $allow = isset($_POST['allow']);
    $googleID = trim($_POST["googleID"]);

    // check input errors before inserting in database
    if (empty($email_err)) {


        // prepare an insert statement
        $sql = "INSERT INTO users (first_name,last_name, email, phone, allow,googleID) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssss", $first_name, $last_name , $email,$phone,$allow, $googleID);


            // attempt to execute the prepared statement
            if ($stmt->execute()) {
                //get new user id

                // prepare a select statement
                $sql = "SELECT id FROM users WHERE googleID = ?";

                if ($stmt = $conn->prepare($sql)) {
                    // bind variables to the prepared statement as parameters
                    $stmt->bind_param("s", $googleID);


                    // attempt to execute the prepared statement
                    if ($stmt->execute()) {
                        // store result
                        $stmt->store_result();

                        // check if phone exists, if yes then verify password
                        if ($stmt->num_rows == 1) {
                            // bind result variables
                            $stmt->bind_result($user_id);

                            if ($stmt->fetch()) {
                                //check if session

                                if (!$_SESSION['user_id']) {
                                    //new session
                                    session_start();
                                    $_SESSION['user_id'] = $user_id;
                                }
                                // redirect to home page
                                echo "<script>location.href = 'programs.php';</script>";
                            }
                        }
                    }
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
    <title>Document</title>
</head>
<body>
    <form class='form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="first_name" value='<?echo $getGivenName;?>'>
        <input type="text" name="last_name" value='<?echo $getFamilyName;?>'>
        <input type="text" name="email" value='<?echo $getEmail;?>'>
        <input hidden type="text" name="googleID" value='<?echo $googleID;?>'>
        <input type="tel" name="phone">
        <input type='checkbox' class='ios8-switch' name="allow" id='checkbox-1'>
        <button class='buttonRegister' type="submit">Зарегистрироваться</button>
    </form>
    
</body>
</html>