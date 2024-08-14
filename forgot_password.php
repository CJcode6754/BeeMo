<?php
session_start();
require './src/db.php';
require './src/otp.php';
require './src/mailer.php';

$db = new Database();
$conn = $db->getConnection();

$otpHandler = new OTP($conn);
$emailHandler = new Mailer();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email address.';
        header('Location: forgot_password.php');
        exit;
    }

    $query = "SELECT * FROM admin_table WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $name = $row['admin_name'];

        $otpData = $otpHandler->generateOTP($email);
        if ($emailHandler->sendOTPFP($email, $name, $otpData['otp'])) {
            $_SESSION['admin_name'] = $name;
            $_SESSION['email'] = $email;
            header('Location: email_link.php');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to send OTP. Please try again later.';
            header('Location: forgot_password.php');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Email not found.';
        header('Location: forgot_password.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/forgot_password.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/b4ce5ff90a.js" crossorigin="anonymous"></script>
    <title>BeeMo</title>
    <link rel="icon" href="img/beemo-ico.ico">
</head>

<body>
    <div id="contents">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 bg1">
                    <div id="LoginLogo" class="container-fluid">
                      <main class="form-signin w-auto m-auto px4">
                            <form action="forgot_password.php" method="post" id="forgotForm" novalidate>
                            <div class="top px-2 pt-4">
                                <a href="index.php"><img id="loginLogo"  src="img/LOGO2.png" alt="Logo"></a>
                                <p class="about pt-1">ABOUT&nbsp;US</p>
                              </div>
                              <hr class="d-block d-lg-none">
                              <div class="form-content px-2">
                                <h1 class="text">Forgot Password.</h1>
                                <p><b>Enter your email </b></p>
                                <div class="form-floating pb-3">
                                  <input name="email" type="email" class="form-control" id="floatingInput1" placeholder="name@example.com" required>
                                  <label class="" for="floatingInput1"><i class="fa-solid fa-envelope"></i>  Email address </label>
                                  <div class="invalid-feedback">Please enter a valid email address.</div>
                                </div>
                                <p>We will send a recovery link to this email</p>

                                <button id="btn" name="submit" class="w-100 py-3" type="submit"><b>SEND RECOVERY LINK</b></button>
                              </div>
                            </form>
                          </main>
                    </div>
                </div>
                <div class="col-lg-8 bg2">
                    <div id="loginImg" class="container-fluid"></div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> 
<script src="./js/forgotPassword.js" type="text/javascript"></script>
</html>
