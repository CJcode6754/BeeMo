<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require './src/db.php';
require './src/otp.php';
require './src/mailer.php';

$db = new Database();
$conn = $db->getConnection();

$otpHandler = new OTP($conn);
$emailHandler = new Mailer();

if (isset($_POST['resend'])) {
    if (isset($_SESSION['email']) && isset($_SESSION['admin_name'])) {
        $email = $_SESSION['email'];
        $name = $_SESSION['admin_name'];
        $otp = $otpHandler->generateOTP($email);

        if ($emailHandler->sendOTP($email, $otp['otp'], $name)) {
            $_SESSION['status'] = 'New OTP sent! Check your email.';
            header('Location: verify.php');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to resend OTP. Please try again later.';
            header('Location: verify.php');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Session expired. Please log in again.';
        header('Location: signup.php');
        exit;
    }
}


if (isset($_POST['submit'])) {
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $otp = $_POST['otp'];

        if ($otpHandler->verifyOTP($email, $otp)) {
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['error'] = 'Invalid OTP or OTP has expired';
            header('Location: verify.php');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Session expired. Please log in again.';
        header('Location: signup.php');
        exit;
    }
}

if (!isset($_SESSION['otp_expiry'])) {
    if (isset($_SESSION['email']) && isset($_SESSION['admin_name'])) {
        $email = $_SESSION['email'];
        $name = $_SESSION['admin_name'];
        $otpData = $otpHandler->generateOTP($email);

        if ($emailHandler->sendOTP($email, $otpData['otp'], $name)) {
            $_SESSION['status'] = 'OTP sent! Check your email.';
        } else {
            $_SESSION['error'] = 'Failed to send OTP. Please try again later.';
        }

        $_SESSION['otp_expiry'] = $otpData['otp_expiry'];
    } else {
        $_SESSION['error'] = 'Session expired. Please log in again.';
        header('Location: signup.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./css/verify.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/b4ce5ff90a.js" crossorigin="anonymous"></script>
    <link rel="icon" href="img/beemo-ico.ico">
    <title>BeeMo</title>
</head>
<body>
    <!-- Contents -->
    <div id="contents">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 bg1">
                    <div id="LoginLogo" class="container-fluid">
                        <main class="form-signin w-auto m-auto px4">
                            <form action="verify.php" method="post">
                                <div class="top px-2 pt-4">
                                    <a href="index.php"><img id="loginLogo" src="img/LOGO2.png" alt="Logo"></a>
                                    <p class="about pt-1">ABOUT&nbsp;US</p>
                                </div>
                                <hr class="d-block d-lg-none">
                                <div class="px-2">
                                    <h1 class="text">Verify</h1>
                                    <p class="fs-4 pb-5">Your code was sent to you via email</p>
                                    <label for="otp">Enter OTP</label>
                                    <div class="form-group d-flex align-items-center position-relative">
                                        <input type="text" name="otp" id="otp" class="form-control" oninput="checkOTP()">
                                        <div id="countdownTimer" style="color:red" class="position-absolute end-0 pe-2"><span id="countdownTimer"></span></div> <!-- Timer -->
                                    </div>
                                    <button name="resend" id="resendBtn" class="mt-2 border-0 bg-white" type="submit">Resend Email</button>
                                    <button id="btn" name="submit" class="w-100 py-3" type="submit" disabled><b>VERIFY</b></button>
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

    <script>
     function startCountdown(expiryTime, display) {
            var endTime = new Date(expiryTime).getTime();
            var now = new Date().getTime();
            var duration = endTime - now;

            var intervalId = setInterval(function () {
                duration -= 1000;
                if (duration <= 0) {
                    clearInterval(intervalId);
                    display.textContent = "Expired";
                    document.getElementById("btn").disabled = true;
                    document.getElementsByName("resend")[0].disabled = false;
                } else {
                    var minutes = Math.floor((duration / (1000 * 60)) % 60);
                    var seconds = Math.floor((duration / 1000) % 60);

                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    display.textContent = minutes + ":" + seconds;
                }
            }, 1000);
        }

        window.addEventListener('load', function () {
            var otpExpiry = '<?php echo isset($_SESSION['otp_expiry']) ? $_SESSION['otp_expiry'] : ''; ?>';
            var countdownDisplay = document.getElementById("countdownTimer");

            if (otpExpiry) {
                startCountdown(otpExpiry, countdownDisplay);
            } else {
                console.error('OTP expiry time not set or invalid.');
            }
        });
        function checkOTP() {
        var otpInput = document.getElementById("otp").value.trim();
        document.getElementById("btn").disabled = otpInput === "";
    }
    // Start countdown when page fully loads
    // window.addEventListener('load', function () {
    //     var otpExpiry = '<?php echo isset($_SESSION['otp_expiry']) ? $_SESSION['otp_expiry'] : ''; ?>';
    //     var countdownDisplay = document.getElementById("countdownTimer");
    //     var resendBtn = document.getElementById("resendBtn");

    //     if (otpExpiry) {
    //         startCountdown(otpExpiry, countdownDisplay);
    //     } else {
    //         console.error('OTP expiry time not set or invalid.');
    //     }

    //     // // Request notification permission
    //     // requestNotificationPermission();

    //     // // Check and show notification if needed
    //     // checkNotification();
    // });

    // // Function to start countdown timer
    // function startCountdown(expiryTime, display) {
    //     var endTime = new Date(expiryTime).getTime(); // Get end time in milliseconds
    //     var now = new Date().getTime(); // Get current time in milliseconds
    //     var duration = endTime - now; // Calculate duration in milliseconds

    //     var intervalId = setInterval(function () {
    //         duration -= 1000; // Subtract 1 second
    //         if (duration <= 0) {
    //             clearInterval(intervalId);
    //             display.textContent = "Expired";
    //             document.getElementById("btn").disabled = true; // Disable verify button after expiry
    //             var resendBtn = document.getElementById("resendBtn");
    //             resendBtn.style.backgroundColor = "red"; // Change resend button color to red

    //             // // Send notification about OTP expiry
    //             // showNotification('OTP Expired', 'The OTP has expired. You can request a new one.');
    //         } else {
    //             var minutes = Math.floor((duration / (1000 * 60)) % 60); // Calculate remaining minutes
    //             var seconds = Math.floor((duration / 1000) % 60); // Calculate remaining seconds

    //             minutes = minutes < 10 ? "0" + minutes : minutes;
    //             seconds = seconds < 10 ? "0" + seconds : seconds;

    //             display.textContent = minutes + ":" + seconds;
    //         }
    //     }, 1000); // Update every 1 second
    // }

    // // // Request permission for notifications
    // // function requestNotificationPermission() {
    // //     if (Notification.permission === "default") {
    // //         Notification.requestPermission().then(function (result) {
    // //             console.log("Notification permission status:", result);
    // //         });
    // //     }
    // // }

    // // // Show a notification
    // // function showNotification(title, body) {
    // //     if (Notification.permission === "granted") {
    // //         new Notification(title, {
    // //             body: body,
    // //             icon: 'path/to/your/icon.png' // Optional icon
    // //         });
    // //     }
    // // }

    // // // Check if email verification notification should be shown
    // // function checkNotification() {
    // //     var notificationFlag = '<?php echo isset($_SESSION['notification']) ? $_SESSION['notification'] : 'false'; ?>';

    // //     if (notificationFlag === 'true') {
    // //         showNotification('Verification Successful', 'Your email has been successfully verified.');
    // //         <?php unset($_SESSION['notification']); ?> // Clear notification flag
    // //     }
    // // }

    // // Enable/disable verify button based on OTP input
    // function checkOTP() {
    //     var otpInput = document.getElementById("otp").value.trim();
    //     document.getElementById("btn").disabled = otpInput === "";
    // }

    </script>

</body>
</html>
