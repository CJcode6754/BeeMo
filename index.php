<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Router.php';
require_once './src/db.php';
require_once './src/users.php';

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
    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
        exit();
    }
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

$router->get('/reports', function() {
    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
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
    require_once 'harvestCycle.php';  // Load harvestCycle page
});

$router->get('/beeGuide', function() {
    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
        exit();
    }
    require_once 'beeGuide.php';  // Load beeGuide page
});

$router->get('/addWorker', function() {
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
    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
        exit();
    }
    require_once 'TermsAndConditions.php';  // Load Terms and Conditions page
});

$router->get('/parameterMonitoring', function() {
    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
        exit();
    }
    require_once 'parameterMonitoring.php';  // Load Parameter Monitoring page
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

$router->get('/verifyWorker', function() {
    if (!isset($_SESSION['adminID'])) {
        session_destroy();
        header('Location: /');
        exit();
    }
    require_once 'verifyWorker.php';  // Load Forgot Password page
});

$router->get('/signup', function() {
    require_once 'signup.php';  // Load signup page
});


// Define POST routes
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

// Dispatch the route
$router->dispatch();
?>
