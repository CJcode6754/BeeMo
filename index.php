<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Router.php';
require_once './src/db.php';
require_once './src/users.php';
require_once './src/harvest_function.php';
require_once './src/notification_handler.php';

// Initialize Router
$router = new Router();

// // Define GET routes
$router->get('/', function() {
    include 'login.php';  // Load login page
});

$router->get('/index.php', function() {
    header('Location: /');  // Redirect /index.php to root
    exit();
});

$router->get('/dashboard', function() {
    require_once 'dashboard.php';  // Load dashboard
});

$router->get('/user_page', function() {
    if (!isset($_SESSION['userID'])) {
        header('Location: /');
        exit();
    }
    require_once 'user_page.php';  // Load user page
});

// Additional routes
$router->get('/chooseHive', function() {
    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
        exit();
    }

    require_once 'chooseHive.php';  // Load chooseHive page
});

$router->get('/parameterMonitoring', function() {
    function season_start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    season_start();

    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
        exit();
    }

    if (!isset($_SESSION['hiveID'])) {
        header('Location: /chooseHive');  // Redirect without destroying session
        exit();
    }

    require_once 'parameterMonitoring.php';  // Load Parameter Monitoring page
});


$router->get('/reports', function() {
    function season_start1() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    season_start1();

    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
        exit();
    }

    if (!isset($_SESSION['hiveID'])) {
        header('Location: /chooseHive');  // Redirect without destroying session
        exit();
    }

    require_once 'reports.php';  // Load reports page
});

$router->get('/harvestCycle', function() {
    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
        exit();
    }

    if (!isset($_SESSION['hiveID'])) {
        header('Location: /chooseHive');  // Redirect without destroying session
        exit();
    }

    require_once 'harvestCycle.php';  // Load harvestCycle page
});

$router->get('/beeGuide', function() {
    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
        exit();
    }
    require_once 'beeguide.php';  // Load beeGuide page
});

$router->get('/Worker', function() {
    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
        exit();
    }
    require_once 'addWorker.php';  // Load addWorker page
});

$router->get('/about', function() {
    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
        exit();
    }
    require_once 'about.php';  // Load about page
});

$router->get('/TermsAndConditions', function() {
    require_once 'TermsAndConditions.php';  // Load Terms and Conditions page
});

$router->get('/profile', function() {
    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
        exit();
    }
    require_once 'profile.php';  // Load Terms and Conditions page
});

$router->get('/resendEmail', function() {
    require_once 'resendEmail.php';  // Load Resebd Email page
});

$router->get('/forgotPassword', function() {
    require_once 'forgotPassword.php';  // Load Forgot Password page
});

$router->get('/resetPassword', function() {
    require_once 'resetPassword.php';  // Load Forgot Password page
});

$router->get('/verify', function() {
    require_once 'verify.php';  // Load Forgot Password page
});

$router->get('/Verify', function() {
    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
        exit();
    }
    require_once 'verifyWorker.php';  // Load Forgot Password page
});

$router->get('/verifyProfile', function() {
    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
        exit();
    }
    require_once 'verifyProfile.php';
});

$router->get('/signup', function() {
    require_once 'signup.php';
});


$router->get('/verifyEmail', function() {
    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
        exit();
    }
    require_once 'verifyEditWorker.php';
});

$router->post('/verifyEmail', function() {
    require_once './src/db.php';
    require_once './src/notification_handler.php';
    $db = new Database();
    $conn = $db->getConnection();

    if (isset($_POST['submit'])) { // Handle OTP verification
        $user_ID = $_SESSION['userID'] ?? $_POST['userID']; // Get userID from session or POST
        $otp = $_POST['otp'] ?? '';

        // Fetch the stored OTP and expiry from the database
        $stmt = $conn->prepare("SELECT otp, otp_expiry FROM user_table WHERE userID = ?");
        $stmt->bind_param('i', $user_ID);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $adminID = $_SESSION['adminID'];
        if ($userData) {
            // Check if the entered OTP matches the stored OTP
            if ($otp === $userData['otp']) {
                // Check if OTP is still valid
                if (strtotime($userData['otp_expiry']) > time()) {
                    // Mark email as verified
                    $stmt = $conn->prepare("UPDATE user_table SET otp = NULL, otp_expiry = NULL WHERE userID = ?");
                    $stmt->bind_param('i', $user_ID);
                    $stmt->execute();

                    // Send notification for successful email verification
                    $notificationHandler = new NotificationHandler($conn);
                    $notificationHandler->insertNotification($adminID, 'active', 'Email verified successfully!', 'email_verified', '/Worker', 'unseen');

                    header('Location: /Worker');
                    exit();
                } else {
                    $_SESSION['error'] = 'OTP expired. Please request a new one.';
                }
            } else {
                $_SESSION['error'] = 'Invalid OTP. Please try again.';
            }
        } else {
            $_SESSION['error'] = 'No OTP found. Please request a new one.';
        }

        // Redirect back to the verify page with error message
        header('Location: /verifyEmail');
        exit();
    }

    if (isset($_POST['resend_otp'])) { // Handle OTP resend
        if (isset($_SESSION['email']) && isset($_SESSION['user_ID'])) {
            $email = $_SESSION['email'];
            $user_ID = $_SESSION['user_ID'];
            $otpHandler = new OTP($conn);  // Assuming $conn is available in this scope
            $mailer = new Mailer();
            $current_name = $_SESSION['user_name'];

            // Generate and send new OTP
            $otpData = $otpHandler->generateOTPUser($email);

            if ($otpData && $mailer->sendOTPEmail($current_name, $email, $otpData['otp'])) {
                $_SESSION['status'] = 'New OTP sent! Check your email.';
                header('Location: /verifyEmail');
                exit();
            } else {
                $_SESSION['error'] = 'Failed to resend OTP. Try again.';
                header('Location: /verifyEmail');
                exit();
            }
        } else {
            $_SESSION['error'] = 'Session expired. Login again.';
            header('Location: /');
            exit();
        }
    }
});


$router->post('/', function() {
    $db = new Database();
    $conn = $db->getConnection();
    $user = new UserIndex($conn);

    if (isset($_POST['submit'])) {
        $user->authenticate();
    }
});

$router->post('/signup', function() {
    $db = new Database();
    $conn = $db->getConnection();

    $name = filter_var($_POST['admin_name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $M_number = isset($_POST['number']) ? filter_var($_POST['number'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {
        $user = new User($conn);
        if ($user->register($name, $email, $M_number, $password)) {
            $_SESSION['email'] = $email;
            header('Location: /verify.php');
            exit();
        } else {
            header('Location: /signup');
            exit();
        }
    } else {
        $_SESSION['error'] = 'Passwords do not match';
        header('Location: /signup');
        exit();
    }
});


$router->post('/chooseHive', function() {
    $db = new Database();
    $conn = $db->getConnection();

    if (isset($_POST['clearNotif'])) {
        $clearNotif = "DELETE FROM tblNotification WHERE adminID = '" . $_SESSION['adminID'] . "'";
        mysqli_query($conn, $clearNotif);

        header("Location: /chooseHive");
        exit();
    }
    // Reload the Choose Hive page with the filtered results
    require_once 'chooseHive.php';
});

$router->post('/reports', function() {
    $db = new Database();
    $conn = $db->getConnection();

    if (isset($_POST['clearNotif'])) {
        $clearNotif = "DELETE FROM tblNotification WHERE adminID = '" . $_SESSION['adminID'] . "'";
        mysqli_query($conn, $clearNotif);

        header("Location: /reports");
        exit();
    }
    // Reload the reports page with the filtered results
    require_once 'reports.php';
});

$router->post('/setHive', function() {
    session_start();

    if (isset($_POST['hiveID'])) {
        $hiveID = $_POST['hiveID'];

        // Create a new database instance and get the connection
        $db = new Database();
        $conn = $db->getConnection();
        $notificationHandler = new NotificationHandler($conn);
        $adminID = $_SESSION['adminID'];

        // Check if the hiveNum exists in the database
        $stmt = $conn->prepare("SELECT hiveID FROM hivenumber WHERE hiveID = ?");
        $stmt->bind_param("i", $hiveID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            // If hiveNum exists, set it in the session
            $_SESSION['hiveID'] = $hiveID;
            header('Location: /parameterMonitoring');  // Redirect to parameterMonitoring
            exit();
        } else {
            // Handle hive not found case
            $notificationHandler->insertNotification($adminID, 'active', 'Hive not recorded.', 'emptyHiveNum', '/dashboard', 'unseen');
            header('Location: /chooseHive');  // Redirect back to chooseHive
            exit();
        }
    } else {
        // If no hiveNum is provided, redirect back to chooseHive
        header('Location: /chooseHive');
        exit();
    }
});

// Dispatch the route
$router->dispatch();
?>
