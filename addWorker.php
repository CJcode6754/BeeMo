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
require_once './src/notification_handler.php';

$db = new Database();
$conn = $db->getConnection();

$mailer = new Mailer();
$otpHandler = new OTP($conn);
$notificationHandler = new NotificationHandler($conn);

if (isset($_POST['submit'])) {
    $name = filter_var($_POST['user_name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_SPECIAL_CHARS);
    $password = $_POST['password'];
    $adminID = $_SESSION['adminID'];

    $check_email = "SELECT * FROM user_table WHERE email = '$email'";
    $check_email_query = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($check_email_query) > 0) {
        $_SESSION['error'] = 'Email Address Already Exists';
        header('Location: /addWorker');
        exit;
    }

    $otpData = $otpHandler->generateOTPUser($email);
    $otp = $otpData['otp'];
    $otp_expiry = $otpData['otp_expiry'];

    $insert_user = "INSERT INTO user_table (user_name, email, number, password, adminID, otp, is_verified, otp_expiry) 
                    VALUES ('$name', '$email', '$number', '$password', '$adminID', '$otp', 0, '$otp_expiry')";
    $insert_user_run = mysqli_query($conn, $insert_user);

    if ($insert_user_run) {
        if ($mailer->sendOTP($email, $otp, $name)) {
            $_SESSION['status'] = 'Verify your email with the OTP sent.';
            $_SESSION['email'] = $email;
            $_SESSION['user_name'] = $name;
            $_SESSION['adminID'] = $adminID;

            $notificationHandler->insertNotification($adminID, 'active', 'User was added successfully.', 'add_user', '/addWorker', 'unseen');
            header('Location: /verifyWorker');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to send OTP. Try again.';
            header('Location: /addWorker');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Error: ' . mysqli_error($conn);
        header('Location: /addWorker');
        exit;
    }
}

if (isset($_POST['btn_delete'])) {
    $user_ID = $_POST['userID'];
    $adminID = $_SESSION['adminID'];
    $delete_user = "DELETE FROM user_table WHERE userID = '$user_ID' AND adminID = '$adminID'";
    $delete_query = mysqli_query($conn, $delete_user);

    if ($delete_query) {
        $notificationHandler->insertNotification($adminID, 'active', 'User was deleted successfully.', 'delete_user', '/addWorker', 'unseen');
    } else {
        $notificationHandler->insertNotification($adminID, 'active', 'Failed to delete user.', 'failed_to_delete_user', '/addWorker', 'unseen');
    }
    header('Location: /addWorker');
    exit;
}

if (isset($_POST['edit_btn'])) {
    $adminID = $_SESSION['adminID'];
    $user_ID = $_POST['userID'];
    $worker_list = "SELECT userID, user_name, email, number, password FROM user_table WHERE userID = '$user_ID' AND adminID = '$adminID'";
    $list_query = mysqli_query($conn, $worker_list);
    $row = $list_query->fetch_assoc();
    $current_name = $row['user_name'];
    $current_email = $row['email'];
    $current_number = $row['number'];
    $current_password = $row['password'];

    $edit_name = filter_var($_POST['edit_user_name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $edit_email = filter_var($_POST['edit_email'], FILTER_SANITIZE_EMAIL);
    $edit_number = filter_var($_POST['edit_number'], FILTER_SANITIZE_SPECIAL_CHARS);
    $edit_password = $_POST['edit_password'];

    $update_success = false;

    if ($edit_name !== $current_name) {
        $edit_name_query = "UPDATE user_table SET user_name = '$edit_name' WHERE userID = '$user_ID'";
        $edit_name_result = mysqli_query($conn, $edit_name_query);
        if ($edit_name_result) {
            $update_success = true;
        }
    }

    if ($edit_email !== $current_email) {
        $edit_email_query = "UPDATE user_table SET email = '$edit_email' WHERE userID = '$user_ID'";
        $edit_email_result = mysqli_query($conn, $edit_email_query);
        if ($edit_email_result) {
            $update_success = true;
        }
    }

    if ($edit_number !== $current_number) {
        $edit_number_query = "UPDATE user_table SET number = '$edit_number' WHERE userID = '$user_ID'";
        $edit_number_result = mysqli_query($conn, $edit_number_query);
        if ($edit_number_result) {
            $update_success = true;
        }
    }

    if ($edit_password !== $current_password) {
        $edit_password_query = "UPDATE user_table SET password = '$edit_password' WHERE userID = '$user_ID'";
        $edit_password_result = mysqli_query($conn, $edit_password_query);
        if ($edit_password_result) {
            $update_success = true;
        }
    }

    if ($update_success) {
        $notificationHandler->insertNotification($adminID, 'active', 'User was edited successfully.', 'edit_user', '/addWorker', 'unseen');
    } else {
        $notificationHandler->insertNotification($adminID, 'active', 'Failed to edit user.', 'failed_to_edit_user', '/addWorker', 'unseen');
    }

    header('Location: /addWorker');
    exit;
}

if (isset($_POST['logout_btn'])) {
    session_destroy();
    header('Location: /');
    exit();
}

if (isset($_POST['clearNotif'])) {
    $clearNotif = "DELETE FROM tblNotification WHERE adminID = '" . $_SESSION['adminID'] . "'";
    $clearNotifResult = mysqli_query($conn, $clearNotif);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeeMo</title>
    <link rel="stylesheet" href="./css/add_worker.css">
    <link rel="stylesheet" href="./css/reusable.css">
    <link rel="icon" href="img/beemo-ico.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b4ce5ff90a.js" crossorigin="anonymous"></script>
</head>

<body class="overflow-x-hidden">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar position-fixed top-0 bottom-0 bg-white border-end offcanvass">

        <div class="d-flex align-items-center p-3 py-5">
            <a href="/dashboard" class="sidebar-logo fw-bold text-dark text-decoration-none fs-4"><img src="img/BeeMo Logo Side.png" width="173px" height="75px" alt="BeeMo Logo"></a>
        </div>
        <ul class="sidebar-menu p-3 py-1 m-0 mb-0">
            <li class="sidebar-menu-item">
                <a href="/dashboard">
                    <i class="fa-solid fa-house sidebar-menu-item-icon"></i>
                    Home
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="/chooseHive">
                    <i class="fa-solid fa-temperature-three-quarters sidebar-menu-item-icon"></i>
                    Parameters Monitoring
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="/reports">
                    <i class="fa-solid fa-newspaper sidebar-menu-item-icon"></i>
                    Reports
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="/harvestCycle">
                    <i class="fa-solid fa-arrows-spin sidebar-menu-item-icon"></i>
                    Harvest Cycle
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="/beeGuide">
                    <i class="fa-solid fa-book-open sidebar-menu-item-icon"></i>
                    Bee Guide
                </a>
            </li>
            <li class="sidebar-menu-item active">
                <a href="/addWorker">
                    <i class="fa-solid fa-user sidebar-menu-item-icon"></i>
                    Worker
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="/about">
                    <i class="fa-solid fa-circle-info sidebar-menu-item-icon"></i>
                    About
                </a>
            </li>
        </ul>
    </div>

    <!-- Main -->
    <main class="bg-light">
        <div class="p-2">
            <!-- Navbar -->
            <nav class="px-3 py-3 rounded-4">
                <div>
                    <p class="d-none d-lg-block mt-3 mx-3 fw-semibold">Welcome to BeeMo</p>
                </div>
                <i class="fa-solid fa-bars sidebar-toggle me-3 d-block d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNav-Menu" aria-controls="offcanvasRight" aria-expanded="false" aria-label="Toggle navigation"></i>
                <h5 class="fw-bold mb-0 me-auto"></h5>
                <div class="dropdown me-3 d-sm-block">
                    <div id="nf-btn" class="navbar-link border border-1 border-black rounded-5" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-bell"></i>
                        <span id="nf-count"></span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-start border-dark border-2 rounded-3" style="width: 320px;">
                        <div class="d-flex justify-content-between dropdown-header border-dark border-2">
                            <div>
                                <p class="fs-5 text-dark text-uppercase pt-3">Notifications
                                    <span class="badge text-dark bg-warning-subtle rounded-pill" id="nf-count-badge">0</span>
                                </p>
                            </div>
                            <div>
                                <form action="/addWorker" method="post">
                                    <button class="clearNotif" name="clearNotif">Clear all</button>
                                </form>
                            </div>
                        </div>
                        <div id="notifications">
                            <!-- Notifications will be dynamically inserted here -->
                        </div>
                    </div>
                </div>

                <div class="dropdown me-3  d-sm-block">
                    <div class="navbar-link  border border-1 border-black rounded-5" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="/TermsAndConditions">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <form id="logoutForm" action="addWorker.php" method="post" style="display: none;">
                            <input type="hidden" name="logout_btn" value="true">
                        </form>
                        <li class="dropdown-item" onclick="document.getElementById('logoutForm').submit();">Logout</li>
                    </ul>
                </div>
            </nav>
            <!-- Content -->
            <div class="worker-page py-3 mt-4 border border-2 rounded-4 border-dark">
                <div class="px-4 py-4 my-4 text-center content-wrapper">
                    <p class="fs-4 mb-5 fw-bold worker-highlight">Workers</p>
                    <div class="container-worker">
                        <div class="table-responsive mt-5" style="max-height: 165px; overflow-y: auto;">
                            <table class="table worker-table border-dark" name="worker_list">
                                <thead>
                                    <tr>
                                        <th style="background-color: #FAEF9B;">Full Name</th>
                                        <th style="background-color: #FAEF9B;">Email</th>
                                        <th style="background-color: #FAEF9B">Contact Number</th>
                                        <th style="background-color: #FAEF9B;">Password</th>
                                        <th style="background-color: #FAEF9B;">Edit</th>
                                        <th style="background-color: #FAEF9B;">Remove</th>
                                    </tr>
                                </thead>
                                <tbody id="workerTableBody">
                                    <?php
                                    $adminID = $_SESSION['adminID'];
                                    $worker_list = "SELECT userID, user_name, email, number, password FROM user_table WHERE adminID = '$adminID' AND is_verified = 1";
                                    $list_query = mysqli_query($conn, $worker_list);
                                    while ($row = $list_query->fetch_assoc()) {
                                        // Generate a unique modal ID for each row
                                        $modalID = 'Edit_WorkerModal_' . $row['userID'];

                                        echo "
                                    <tr>
                                        <td>" . $row['user_name'] . "</td>
                                        <td>" . $row['email'] . "</td>
                                        <td>" . $row['number'] . "</td>
                                        <td>" . $row['password'] . " </td>
                                        <td>
                                            <button name='btn_edit' class='btn edit-btn' data-bs-toggle='modal' type='button' data-bs-target='#$modalID'>
                                                <i class='fa-regular fa-pen-to-square'></i>
                                            </button>
                                            <div class='yellow mt-1 d-md-none fixed-bottom p-0 m-0'></div>
                                            <div class='modal fade' id='$modalID' tabindex='-1' aria-labelledby='Edit_WorkerLabel' aria-hidden='true'>
                                                <div class='modal-dialog modal-lg modal-dialog-centered rounded-3'>
                                                    <div class='modal-content' style='border: 2px solid #2B2B2B;'>
                                                        <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                                            <h5 class='modal-title fw-semibold mx-4' id='Edit_WorkerLabel'>Edit Worker</h5>
                                                            <button name='closeBtn' type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                        </div>
                                                        <div class='modal-body m-5'>
                                                            <form action='addWorker.php' method='post' id='edit_workerForm' novalidate>
                                                                <div class='d-grid d-sm-flex justify-content-sm-center gap-4 mb-1'>
                                                                    <div class='col-md-6'>
                                                                        <label for='FullName' class='form-label' style='font-size: 13px;'>Full Name</label>
                                                                        <input name='edit_user_name' type='text' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='Edit_FullName_$modalID' value='" . htmlspecialchars($row['user_name'], ENT_QUOTES) . "' required>
                                                                        <div class='invalid-feedback'>Please enter your full name.</div>
                                                                    </div>
                                                                    <div class='mb-3 col-md-6'>
                                                                        <label for='Email' class='form-label' style='font-size: 13px;'>Email</label>
                                                                        <input name='edit_email' type='email' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='Edit_Email_$modalID' value='" . htmlspecialchars($row['email'], ENT_QUOTES) . "' required>
                                                                        <div class='invalid-feedback'>Please enter a valid email address.</div>
                                                                    </div>
                                                                </div>
                                                                <div class='d-grid mt-3 d-sm-flex justify-content-sm-center gap-4'>
                                                                    <div class='col-md-6'>
                                                                        <label for='PhoneNumber' class='form-label' style='font-size: 13px;'>Phone Number</label>
                                                                        <input name='edit_number' type='number' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='Edit_PhoneNumber_$modalID' value='" . htmlspecialchars($row['number'], ENT_QUOTES) . "' required>
                                                                        <div class='invalid-feedback'>Please enter a valid mobile number.</div>
                                                                    </div>
                                                                    <div class='col-md-6 mb-2'>
                                                                        <label for='Password' class='form-label' style='font-size: 13px;'>Password</label>
                                                                        <input name='edit_password' type='password' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='Edit_Password_$modalID' value='" . htmlspecialchars($row['password'], ENT_QUOTES) . "' required>
                                                                        <div class='invalid-feedback'>Password must be 8-32 characters long.</div>
                                                                    </div>
                                                                </div>
                                                                <div class='mt-5 d-flex justify-content-center'>
                                                                    <input type='hidden' name='userID' value='" . $row['userID'] . "'>
                                                                    <button id='Edit_btn_$modalID' name='edit_btn' type='submit' class='save-button px-4 border border-1 border-black fw-semibold'><span class='fw-bold'>+</span> Edit Info</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <form method='post' action='addWorker.php'>
                                                <input type='hidden' name='userID' value='" . $row['userID'] . "'>
                                                <button type='submit' name='btn_delete' class='btn delete-btn'><i class='fa-regular fa-trash-can' style='color: red;'></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    ";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-5 d-flex justify-content-end pe-3">
                            <button class="add-button px-4 border border-1 border-black  fw-semibold" type="button" data-bs-toggle="modal" data-bs-target="#addWorkerModal">
                                <span class="fw-bold">+ </span> Add Worker
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="yellow mt-1 d-md-none fixed-bottom p-0 m-0"></div>
        <div class="modal fade" id="addWorkerModal" tabindex="-1" aria-labelledby="addWorkerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered rounded-3">
                <div class="modal-content" style="border: 2px solid #2B2B2B;">
                    <div class="modal-header border-dark border-2" style="background-color: #FCF4B9;">
                        <h5 class="modal-title fw-semibold mx-4" id="addWorkerModalLabel">Add Worker</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body m-5">
                        <form action="addWorker.php" method="post" id="workerForm" novalidate>
                            <div class="d-grid d-sm-flex justify-content-sm-center gap-4 mb-1">
                                <div class="col-md-6">
                                    <label for="FullName" class="form-label" style="font-size: 13px;">Full Name</label>
                                    <input name="user_name" type="text" class="form-control rounded-3 py-2" style="border: 1.8px solid #2B2B2B; font-size: 13px;" id="FullName" required>
                                    <div class="invalid-feedback">Please enter your full name.</div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="Email" class="form-label" style="font-size: 13px;">Email</label>
                                    <input name="email" type="email" class="form-control rounded-3 py-2" style="border: 1.8px solid #2B2B2B; font-size: 13px;" id="Email" required>
                                    <div class="invalid-feedback">Please enter a valid email address.</div>
                                </div>
                            </div>
                            <div class="d-grid mt-3 d-sm-flex justify-content-sm-center gap-4">
                                <div class="col-md-6">
                                    <label for="PhoneNumber" class="form-label" style="font-size: 13px;">Phone Number</label>
                                    <input name="number" type="number" class="form-control rounded-3 py-2" style="border: 1.8px solid #2B2B2B; font-size: 13px;" id="PhoneNumber" required>
                                    <div class="invalid-feedback">Please enter a valid mobile number.</div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="Password" class="form-label" style="font-size: 13px;">Password</label>
                                    <input name="password" type="password" class="form-control rounded-3 py-2" style="border: 1.8px solid #2B2B2B; font-size: 13px;" id="Password" required>
                                    <div class="invalid-feedback">Password must be 8-32 characters long.</div>
                                </div>
                            </div>
                            <div class="mt-5 d-flex justify-content-center">
                                <button id="btn" name="submit" type="submit" class="save-button px-4 border border-1 border-black fw-semibold"><span class="fw-bold">+</span> Add Worker</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Side Bar Mobile View -->

    <div class="offcanvas offcanvas-start sidebar2 overflow-x-hidden overflow-y-hidden" tabindex="-1" id="offcanvasNav-Menu" aria-labelledby="staticBackdropLabel">
        <div class="d-flex align-items-center p-3 py-5">
            <a href="/dashboard" class="sidebar-logo fw-bold text-dark text-decoration-none fs-4" data-bs-dismiss="offcanvas" aria-label="Close">
                <img src="img/BeeMo Logo Side.png" width="173px" height="75px" alt="BeeMo Logo">
            </a>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <ul class="sidebar-menu p-2 py-2 m-0 mb-0">
            <li class="sidebar-menu-item2">
                <a href="/dashboard">
                    <i class="fa-solid fa-house sidebar-menu-item-icon2"></i>
                    Home
                </a>
            </li>
            <li class="sidebar-menu-item2 py-1">
                <a href="/chooseHive">
                    <i class="fa-solid fa-temperature-three-quarters sidebar-menu-item-icon2"></i>
                    Parameters Monitoring
                </a>
            </li>
            <li class="sidebar-menu-item2">
                <a href="/reports">
                    <i class="fa-solid fa-newspaper sidebar-menu-item-icon2"></i>
                    Reports
                </a>
            </li>
            <li class="sidebar-menu-item2">
                <a href="/harvestCycle">
                    <i class="fa-solid fa-arrows-spin sidebar-menu-item-icon2"></i>
                    Harvest Cycle
                </a>
            </li>
            <li class="sidebar-menu-item2">
                <a href="/beeGuide">
                    <i class="fa-solid fa-book-open sidebar-menu-item-icon2"></i>
                    Bee Guide
                </a>
            </li>
            <li class="sidebar-menu-item2 active">
                <a href="/addWorker">
                    <i class="fa-solid fa-user sidebar-menu-item-icon2"></i>
                    Worker
                </a>
            </li>
            <li class="sidebar-menu-item2">
                <a href="/about">
                    <i class="fa-solid fa-circle-info sidebar-menu-item-icon2"></i>
                    About
                </a>
            </li>
        </ul>
    </div>
    </div>

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
    <script src="./js/addWorker.js"></script>
    <script src="./js/notification.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>