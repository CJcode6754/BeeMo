<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './src/password_reset.php';
require_once './src/db.php';

$db = new Database();
$conn = $db->getConnection();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $email = $_SESSION['email'] ?? '';

    // Instantiate the PasswordReset class
    $passwordReset = new PasswordReset($conn);

    if (!$passwordReset->resetPassword($password, $confirm_password, $email)) {
        header('Location: /resetPassword');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./css/reset_password.css">
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
                          <form action="resetPassword.php" method="post" id="ResetpwForm" novalidate>
                            <div class="top px-2 pt-4">
                              <a href="/"><img id="loginLogo"  src="img/LOGO2.png" alt="Logo"></a>
                              <p class="about pt-1">ABOUT&nbsp;US</p>
                            </div>
                            <hr class="d-block d-lg-none">

                            <div class="form-content px-2">
                              <h1 class="text">Reset Password.</h1>
                              <div class="form-floating pb-3 position-relative">
                                <input name="password" type="password" class="form-control" id="floatingPassword2" placeholder="Password" required>
                                <label for="floatingPassword2"><i class="fa-solid fa-lock"></i> Password</label>
                                <div class="password-wrapper">
                                  <span id="togglePassword" class="toggle-password"><i class="fa-solid fa-eye-slash"></i></span>
                                </div>
                                <div class="invalid-feedback">
                                    Please enter a valid password.
                                </div>
                            </div>
                            <div class="form-floating pb-3 position-relative">
                                <input name="confirm_password" type="password" class="form-control" id="floatingConfirmPassword2" placeholder="Confirm Password" required>
                                <label for="floatingConfirmPassword2"><i class="fa-solid fa-lock"></i> Confirm Password</label>
                                <div class="password-wrapper">
                                  <span id="toggleConfirmPassword" class="toggle-password"><i class="fa-solid fa-eye-slash"></i></span>
                                </div>
                                <div class="invalid-feedback">
                                    Passwords do not match.
                                </div>
                            </div>
                              <button name="submit" id="btn" class="w-100 py-3" type="submit"><b>RESET PASSWORD</b></button>
                            </div>
                          </form>
                      </main>
                    </div>
                </div>
                <div class="col-lg-8 bg2">
                    <div id="loginImg" class="container-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="notification" class="notification"></div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    const notification = document.getElementById('notification');

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
});
    </script>
</body>
<!-- JQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> 
<script src="./js/resetPassword.js" type="text/javascript"></script>
</html>
