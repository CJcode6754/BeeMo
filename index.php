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

$router->post('/harvestCycle', function() {
    $db = new Database();
    $conn = $db->getConnection();
    $harvestCycle = new HarvestCycle($conn);

    // Get the filter value from the POST request, default to 'all'
    $filter = isset($_POST['filter_value']) ? $_POST['filter_value'] : 'all';
    $adminID = $_SESSION['adminID'];

    // Base query to select all cycles for the logged-in admin
    $select_cycle = "SELECT cycle_number, start_of_cycle, honey_kg, end_of_cycle, status FROM harvest_cycle WHERE adminID = '$adminID'";

    // Modify the query based on the selected filter
    if ($filter == 'pending') {
        $select_cycle .= " AND status = 0";
    } elseif ($filter == 'complete') {
        $select_cycle .= " AND status = 1";
    }

    // Execute the query and store the results in the session
    $query_select_cycle = mysqli_query($conn, $select_cycle);
    $_SESSION['filtered_cycles'] = mysqli_fetch_all($query_select_cycle, MYSQLI_ASSOC);

    if (isset($_POST['btn_delete'])) {
        // Delete the specified harvest cycle
        $cycleNumber = $_POST['cycle_number'];
        $harvestCycle->deleteCycle($cycleNumber, $adminID);

        // Redirect back to the harvestCycle page
        header('Location: /harvestCycle');
        exit();
    }

    // Reload the harvestCycle page with the filtered results
    require_once 'harvestCycle.php';
});


// // Define POST for clear notification
// $router->post('/notif', function() {
//     $db = new Database();
//     $conn = $db->getConnection();

//     // Clear all notifications for the current admin
//     $adminID = $_SESSION['adminID'];
//     $clearNotif = "DELETE FROM tblNotification WHERE adminID = '$adminID'";
//     mysqli_query($conn, $clearNotif);

//     // Redirect back to the notification page or reload the page
//     header("Location: /notif");
//     exit();
// });


// Dispatch the route
$router->dispatch();
?>
