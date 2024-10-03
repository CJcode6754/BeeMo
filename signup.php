<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function season_start()
{
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  if (!isset($_SESSION['season_started'])) {
    $_SESSION['season_started'] = true;
  }
}

season_start();

require_once './src/db.php';
require_once './src/mailer.php';
require_once './src/otp.php';
require_once './src/users.php';

$db = new Database();
$conn = $db->getConnection();

if (isset($_POST['submit'])) {
  $name = filter_var($_POST['admin_name'], FILTER_SANITIZE_SPECIAL_CHARS);
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $M_number = isset($_POST['number']) ? filter_var($_POST['number'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  if ($password === $confirm_password) {
    $user = new User($conn);
    if ($user->register($name, $email, $M_number, $password)) {
      $_SESSION['email'] = $email;
      header('Location: /verify');
      exit;
    } else {
      header('Location: /signup');
      exit;
    }
  } else {
    $_SESSION['error'] = 'Passwords do not match';
    header('Location: /signup');
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
  <link rel="stylesheet" href="./css/signup.css">
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
    <div class="container-fluid h-100">
      <div class="row">
        <div class="col-lg-4 bg1">
          <div id="LoginLogo" class="container-fluid">
            <main class="form-signin px4">
              <form action="signup.php" method="post" id="registerForm" novalidate>
                <div class="top px-2 pt-4">
                  <a href="/signup"><img id="loginLogo" src="img/LOGO2.png" alt="Logo"></a>
                  <p class="about pt-1">ABOUT&nbsp;US</p>
                </div>
                <hr class="d-block d-lg-none w-100">

                <div class="form-content px-2">
                  <h1 class="text">Sign Up.</h1>
                  <div class="log pb-3">
                    <p><b>Already have an account?</p>
                    <a href="/" class="text-dark"><u>Login?</u></a></b>
                  </div>

                  <div class="form-floating pb-3">
                    <input name="admin_name" type="text" class="form-control" id="fullName" placeholder="Full Name" required>
                    <label for="fullName"><i class="fa-solid fa-user"></i> Full Name</label>
                    <div class="invalid-feedback">Please enter your full name.</div>
                  </div>

                  <div class="form-floating pb-3">
                    <input name="email" type="email" class="form-control" id="email" placeholder="name@example.com" required>
                    <label for="email"><i class="fa-solid fa-envelope"></i> Email</label>
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                  </div>

                  <div class="form-floating pb-3">
                    <input name="number" type="number" class="form-control" id="mobileNumber" placeholder="Mobile Number" required>
                    <label for="mobileNumber"><i class="fa-solid fa-mobile"></i> Mobile Number</label>
                    <div class="invalid-feedback">Please enter a valid mobile number.</div>
                  </div>

                  <div class="form-floating pb-3">
                    <input name="password" type="password" class="form-control" id="password" placeholder="Password" required>
                    <label for="password"><i class="fa-solid fa-lock"></i> Password</label>
                    <span id="togglePassword" class="toggle-password"><i class="fa-solid fa-eye-slash"></i></span>
                    <div class="invalid-feedback">Password must be 8-32 characters long.</div>
                  </div>

                  <div class="form-floating pb-3">
                    <input name="confirm_password" type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" required>
                    <label for="confirmPassword"><i class="fa-solid fa-lock"></i> Confirm Password</label>
                    <span id="toggleConfirmPassword" class="toggle-password"><i class="fa-solid fa-eye-slash"></i></span>
                    <div class="invalid-feedback">Passwords do not match.</div>
                  </div>

                  <div class="terms form-check text-start my-3">
                    <input name="check" class="form-check-input" style="background-color: #6DA4AA;" type="checkbox" value="remember-me" id="termsCheckbox" required>
                    <label class="form-check-label" for="termsCheckbox">
                      <p>I have read & accept the <a href="#" type="button" data-bs-toggle="modal"
                          data-bs-target="#termsAndConditions" style="background-color: #6DA4AA; color: black; font-weight: bold;">Terms and Conditions</a></p>
                    </label>
                    <div class="invalid-feedback">You must accept the terms and conditions</div>
                  </div>

                  <button name="submit" id="btn" class="w-100 py-3" type="submit"><b>REGISTER</b></button>
                </div>
              </form>
            </main>

          </div>
        </div>

        <div class="col-lg-8 bg2">
          <div id="SignUpImg" class="container-fluid"></div>
        </div>
      </div>
    </div>
  </div>


  <!-- Start Terms and Conditions Modal -->
  <div class="modal fade" id="termsAndConditions" tabindex="-1" aria-labelledby="termsAndConditionsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content border border-4 rounded-5 border-dark">
        <div class="modal-header border-dark border-3" style="background-color: #FCF4B9;">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-5">
          <div class="mx-4">
            <div class="text-center">
              <p class="terms-text fs-4 mb-4 fw-bold terms-highlight">Terms and Conditions</p>
            </div>
            <div class="row flex-lg-row-reverse mt-4 py-4">
              <div class="col-12 col-lg-4 centered-image">
                <img src="img/terms-img.png" class="d-block mx-lg-auto terms-img d-none d-md-block" alt="">
              </div>
              <div class="col-lg-8 justify-text">
                <div style="max-height: 300px; overflow-y: auto;">
                  <p class="scrollable-text ">Welcome to BeeMo: An IoT Web-Based Monitoring System for Stingless
                    Bee Farming. Before using the system, please read and agree to the following terms and
                    conditions:</p>
                  <p class="scrollable-text">Acceptance of Terms
                    These Terms of Service govern your access and use of the services offered by the BeeMo Web
                    System. By accessing or using BeeMo, you certify that you have read and reviewed these
                    Agreement terms and agree to comply with them in accordance with the provisions of the Data
                    Privacy Act of 2012 (RA 10173), Sec. 13(a). If you do not want to be bound by the terms of
                    this Agreement, you are advised to stop using the website accordingly.
                  </p>
                  <p class="scrollable-text">User Responsibilities
                    Account Security: Users are responsible for maintaining the confidentiality of their login
                    credentials and for all activities that occur under their account.
                    User Responsibilities
                    Account Security: Users are responsible for maintaining the confidentiality of their login
                    credentials and for all activities that occur under their account.
                    Account Security: Users are responsible for maintaining the confidentiality of their login
                    credentials and for all activities that occur under their account.
                    User Responsibilities
                    Account Security: Users are responsible for maintaining the confidentiality of their login
                    credentials and for all activities that occur under their account.
                  </p>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
    <!-- End Terms and Conditions Modal -->

  <div id="notification" class="notification"></div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const notification = document.getElementById('notification');

      // Show notification function
      function showNotification(message) {
        notification.textContent = message;
        notification.classList.add('show');
        setTimeout(function() {
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
<script src="./js/signup.js" type="text/javascript"></script>

</html>