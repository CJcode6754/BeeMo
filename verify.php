<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './src/db.php';
require_once './src/otp.php';
require_once './src/mailer.php';

$db = new Database();
$conn = $db->getConnection();

$otpHandler = new OTP($conn);
$emailHandler = new Mailer();

if (isset($_POST['resend'])) {
    if (isset($_SESSION['email']) && isset($_SESSION['admin_name'])) {
        $email = $_SESSION['email'];
        $otp = $otpHandler->generateOTPResend($email);
        $name = $_SESSION['admin_name'];

        if ($emailHandler->sendOTP($email, $otp['otp'], $name)) {
            $_SESSION['status'] = 'New OTP sent! Check your email.';
            header('Location: /verify');
            exit;
        } else {
            $_SESSION['error'] = 'Resend OTP failed. Try again.';
            header('Location: /verify');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Session expired. Register again.';
        header('Location: /signup');
        exit;
    }
}


if (isset($_POST['submit'])) {
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $otp = $_POST['otp'];

        if ($otpHandler->verifyOTP($email, $otp)) {
            $_SESSION['status'] = 'Account registered successfully.';
            header('Location: /');
            exit;
        } else {
            $_SESSION['error'] = 'OTP verification failed. Try again.';
            header('Location: /verify');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Session expired. Register again.';
        header('Location: /signup');
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
    <link rel="stylesheet" href="./css/reusable.css">
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
                                    <a href="/signup"><img id="loginLogo" src="img/LOGO2.png" alt="Logo"></a>
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
    <div id="notification" class="notification"></div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    const notification = document.getElementById('notification');

    // OTP Countdown function
    function startCountdown(expiryTime, display) {
        const endTime = new Date(expiryTime).getTime();
        const intervalId = setInterval(function () {
            const now = new Date().getTime();
            const duration = endTime - now;

            if (duration <= 0) {
                clearInterval(intervalId);
                display.textContent = "Expired";
                document.getElementById("btn").disabled = true;
                document.getElementById("resendBtn").disabled = false;

                // Show OTP expired notification
                showNotification('OTP expired. Please request a new one.');
            } else {
                const minutes = Math.floor((duration / (1000 * 60)) % 60);
                const seconds = Math.floor((duration / 1000) % 60);

                display.textContent = `${minutes < 10 ? '0' + minutes : minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
            }
        }, 1000);
    }

    // Show notification function
    function showNotification(message) {
        notification.textContent = message;
        notification.classList.add('show');
        setTimeout(function () {
            notification.classList.remove('show');
        }, 6000);
    }

    // Handle the notifications for status and error in the session
    <?php if (isset($_SESSION['status'])): ?>
        showNotification('<?php echo $_SESSION['status']; ?>');
        <?php unset($_SESSION['status']); ?>
    <?php elseif (isset($_SESSION['error'])): ?>
        showNotification('<?php echo $_SESSION['error']; ?>');
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    // Start the OTP countdown if expiry is set
    const otpExpiry = '<?php echo isset($_SESSION['otp_expiry']) ? $_SESSION['otp_expiry'] : ''; ?>';
    if (otpExpiry) {
        const countdownDisplay = document.getElementById('countdownTimer');
        startCountdown(otpExpiry, countdownDisplay);
    } else {
        console.error('OTP expiry time not set or invalid.');
    }

    // Check OTP input
    document.getElementById('otp').addEventListener('input', function () {
        const otpInput = this.value.trim();
        document.getElementById("btn").disabled = otpInput === "";
    });
});
    </script>

</body>
</html>
