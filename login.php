<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
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
    <div id="contents">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 bg1">
                    <div id="LoginLogo" class="container-fluid">
                        <main class="form-signin px4">
                            <form action="/" method="post" id="loginForm" novalidate>
                                <div class="top px-2 pt-4">
                                    <a href="/"><img id="loginLogo" src="img/LOGO2.png" alt="Logo"></a>
                                    <p class="about pt-1">ABOUT&nbsp;US</p>
                                </div>
                                <hr class="d-block d-lg-none w-100">
                                <div class="form-content px-2">
                                    <h1 class="text">Sign In.</h1>
                                    <div class="reg pb-3">
                                        <p><b>Don't have an account?</b></p>
                                        <a href="/signup" class="text-dark"><u>Register</u></a>
                                    </div>
                                    <div class="form-floating pb-3 position-relative">
                                        <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
                                        <label for="floatingInput"><i class="fa-solid fa-envelope"></i> Email address </label>
                                        <div class="invalid-feedback">Please enter a valid email address.</div>
                                    </div>
                                    <div class="form-floating pb-3">
                                        <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                                        <label for="floatingPassword"><i class="fa-solid fa-lock"></i> Password </label>
                                        <div class="password-wrapper">
                                            <span id="togglePassword" class="toggle-password"><i class="fa-solid fa-eye-slash"></i></span>
                                        </div>
                                        <div class="invalid-feedback">Please enter your password.</div>
                                    </div>
                                    <a href="/forgotPassword" class="href text-dark">Forgot Password?</a>
                                    <button id="login" class="w-100 py-3" name="submit" type="submit"><b>ACCESS MY ACCOUNT</b></button>
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

    function handleBackNavigation() {
        history.pushState(null, null, null);
        window.addEventListener('popstate', function () {
            history.pushState(null, null, null);
            // Optionally redirect to a specific page if needed
            window.location.replace('/'); // Redirect to login or another page
        });
    }

    // Call the function to handle back navigation
    handleBackNavigation();
    </script>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> 
<script src="./js/login.js" type="text/javascript"></script>
</html>
