<?php
    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once './src/db.php';
    require_once './src/profileFunction.php';

    $db = new Database();
    $conn = $db->getConnection();

    $query = "SELECT temperature, humidity, weight FROM hive1 ORDER BY id DESC LIMIT 1";
    $fetch_data = mysqli_query($conn, $query);

    if(mysqli_num_rows($fetch_data) > 0) {
        $data = mysqli_fetch_assoc($fetch_data);
    } else {
        $data = ['temperature' => 'N/A', 'humidity' => 'N/A', 'weight' => 'N/A'];
    }

    if (isset($_POST['logout_btn'])) {
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        header('Location: /');
        exit;
    }

    $profile = new Profile($conn, $_SESSION['adminID']);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['editProfile'])) {
            $name = $_POST['editName'];
            $email = $_POST['editEmail'];
            $phoneNumber = $_POST['editNumber'];

            $profile->updateProfile($name, $email, $phoneNumber);
        }

        if (isset($_POST['changePass'])) {
            $oldPass = $_POST['OldPass'];
            $newPass = $_POST['newPass'];
            $conNewPass = $_POST['conNewPass'];
            $profile->changePassword($oldPass, $newPass, $conNewPass);
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeeMo</title>
    <link rel="stylesheet" href="./css/parameter_monitoring.css">
    <link rel="stylesheet" href="./css/reusable.css">
    <link rel="stylesheet" href="./css/profile.css">
    <link rel="icon" href="img/beemo-ico.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b4ce5ff90a.js" crossorigin="anonymous"></script>
</head>

<body class="overflow-x-hidden ">
  <!-- Sidebar -->
    <div id="sidebar" class="sidebar position-fixed top-0 bottom-0 bg-white border-end offcanvass">
        <div class="d-flex align-items-center p-3 py-5">
            <a href="/dashboard" class="sidebar-logo fw-bold text-dark text-decoration-none fs-4"><img src="img/BeeMo Logo Side.png" width="173px" height="75px" alt=""></a>
        </div>
        <ul class="sidebar-menu p-3 py-1 m-0 mb-0">
            <li class="sidebar-menu-item">
                <a href="/dashboard">
                    <i class="fa-solid fa-house sidebar-menu-item-icon"></i>
                    Home
                </a>
            </li>
            <li class="sidebar-menu-item active">
                <a href="/parameterMonitoring">
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
            <li class="sidebar-menu-item">
                <a href="/Worker">
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
                                <form action="/parameterMonitoring" method="post">
                                    <button class="clearNotif" name="clearNotif">Clear all</button>
                                </form>
                            </div>
                        </div>
                        <div id="notifications">
                            <!-- Notifications will be dynamically inserted here -->
                        </div>
                    </div>
                </div>

                <div class="dropdown me-3 d-sm-block">
                    <div class="navbar-link border border-1 border-black rounded-5" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li>
                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#Profile-Modal">
                                <i class="fa-solid fa-user"></i>
                                Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fa-solid fa-gear"></i>
                                Settings
                            </a>
                        </li>
                        <!-- Logout -->
                        <form id="logoutForm" action="/parameterMonitoring" method="post" style="display: none;">
                            <input type="hidden" name="logout_btn" value="true">
                        </form>
                        <li class="dropdown-item" onclick="document.getElementById('logoutForm').submit();">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Logout
                        </li>
                    </ul>
                </div>
                </nav>
            <div class="monitoring-page py-4 mt-4 border border-2 rounded-4 border-dark">
                <div class="px-4 py-2 my-4 text-center content-wrapper">
                    <p class="monitoring-text fs-4 mb-5 fw-bold monitoring-highlight">Hive 1</p>
                    <div class="d-flex justify-content-end">
                        <p class="auto-manual">Auto <i class="fa-solid fa-toggle-on toggle-button"></i></p>
                    </div>
                    <div class="d-grid d-sm-flex justify-content-sm-center gap-3 mb-1">
                        <div class="col-md-6">
                            <div class="container1">
                                <div class="d-flex justify-content-between m-4">
                                    <div class="d-block">
                                        <p class="fw-bold">Temperature</p>
                                        <p>Based: 32 - 35 °C</p>
                                        <p class="temp-degree"><span class="temp-degree"><?php echo htmlspecialchars($data['temperature']); ?> °C</span></p>
                                    </div>
                                    <i class="fa-solid fa-temperature-low align-content-center" style="font-size: 40px;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="container2">
                                        <div class="d-flex justify-content-between m-3">
                                            <div class="d-block lh-1">
                                                <p class="humid">Humidity</p>
                                                <p class="humid-based">Based: 50 - 60%</p>
                                                <p class="humid-percent "><span class="humid-percent"><?php echo htmlspecialchars($data['humidity']); ?>%</span></p>
                                            </div>
                                            <i class="fa-solid fa-droplet align-content-center" style="font-size: 25px;"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="container3">
                                        <div class="d-flex justify-content-between m-3">
                                            <div class="d-block lh-1 weight-block">
                                                <p class="weight">Weight</p>
                                                <p class="initial">Initial weight: 5-7 kg</p>
                                                <p class="weight-value">
                                                <?php 
                                                    $weight = is_numeric($data['weight']) ? $data['weight'] / 1000 : 'N/A';
                                                    echo htmlspecialchars($weight); 
                                                ?> kg
                                                </p>
                                            </div>
                                            <i class="fa-solid fa-box-archive align-content-center" style="font-size: 25px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

        <!-- Profile Modal -->
        <div class="modal fade " id="Profile-Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable profile-dialog">

            <div class="modal-content border-2 border-dark" style="border-radius: 20px; box-shadow: 0 7px #2B2B2B; max-width: 400px;">

                <div class="modal-header profile-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Contents -->
                <div class="modal-body" style="color: #292929;">
                    <div class="icon text-center my-3"><img src="img/Profile icon.png" alt="" width="80" height="80"></div>
                    <div class="text-center">
                    <?php
                        require_once './src/db.php';
                        $db = new Database();
                        $conn = $db->getConnection();
                        $adminID = $_SESSION['adminID'];
                        $get = "SELECT admin_name, email FROM admin_table WHERE adminID = '$adminID'";
                        $getQuery = mysqli_query($conn, $get);

                        while($row = $getQuery->fetch_assoc()){
                            echo"
                            <h5>". $row['admin_name'] ."</h5>
                            <h6 style='text-decoration: underline; font-weight: 350;'><small class='text-body-secondary'>". $row['email']."</small></h6>
                            ";
                        }
                    ?>
                    <hr class="mx-auto" width = "80%">
                    </div>

                    <div class="Options mx-auto py-4">

                        <button type="button" id="edit-profile-button" data-bs-toggle="modal" data-bs-target="#Edit-Profile-Modal" style="padding: 10px;">
                            <p><i class="fa-solid fa-user"></i> <span>My Profile</span><i class="fa-solid fa-angle-right"></i></p>
                        </button>

                        <button type="button" id="edit-profile-button" data-bs-toggle="modal" data-bs-target="#Change-Pass-Modal" style="padding: 10px;" >
                            <p><i class="fa-solid fa-lock"></i> <span>Change Password</span><i class="fa-solid fa-angle-right"></i></p>
                        </button>

                        <button style="padding: 10px;">
                            <p><i class="fa-solid fa-bell"></i> <span>Notification</span>allow</p>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ----------------------------------------------------------------------------------------------------------- -->

    <!-- Edit Profile -->

        <div class="modal fade " id="Edit-Profile-Modal" tabindex="0" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable profile-dialog">

                <div class="modal-content border-2 border-dark" style="border-radius: 20px; box-shadow: 0 7px #2B2B2B; max-width: 450px;">
                    <div class="modal-header profile-header" style="padding: 5px;">

                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#Profile-Modal" data-bs-dismiss="modal" aria-label="Back">
                            <i class="fa-solid fa-angle-left fa-lg"></i>
                        </button>

                        <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark fa-lg" style="margin-left: 360px;"></i>
                        </button>

                    </div>

                    <!-- Modal Contents -->
                    <div class="modal-body" style="color: #292929;">
                    <div class="icon text-center my-3"><img src="img/Profile icon.png" alt="" width="80" height="80"></div>
                    <div class="text-center">
                    <?php
                        require_once './src/db.php';
                        $db = new Database();
                        $conn = $db->getConnection();
                        $adminID = $_SESSION['adminID'];
                        $get = "SELECT admin_name, email, number FROM admin_table WHERE adminID = '$adminID'";
                        $getQuery = mysqli_query($conn, $get);

                        while($row = $getQuery->fetch_assoc()){
                            $currentName = $row['admin_name'];
                            $currentEmail = $row['email'];
                            $currentPhoneNumber = $row['number'];
                            echo"
                                <h5>$currentName</h5>
                                <h6 style='text-decoration: underline; font-weight: 350;'><small class='text-body-secondary'>$currentEmail</small></h6>
                                <div class='Field-inputs mx-4' style='font-size: small;'>
                                <form method='POST' action=''>

                                <div class='form-floating pb-4'>
                                    <input name='editName' type='text' class='form-control' id='fullName' placeholder='Full Name' value='$currentName'>
                                    <label for='fullName'><i class='fa-solid fa-user'></i> Full Name</label>
                                </div>

                                <div class='form-floating pb-4'>
                                    <input name='editEmail' type='email' class='form-control' id='email' placeholder='name@example.com' value='$currentEmail'>
                                    <label for='email'><i class='fa-solid fa-envelope'></i> Email</label>
                                </div>

                                <div class='form-floating pb-4'>
                                    <input name='editNumber' type='number' class='form-control' id='mobileNumber' placeholder='Mobile Number' value='$currentPhoneNumber'>
                                    <label for='mobileNumber'><i class='fa-solid fa-mobile'></i> Mobile Number</label>
                                </div>

                                <div class='save-changes'>
                                    <button name='editProfile' type='submit' class='mt-4 mb-5' id='save-btn'>Save Changes</button>
                                </div>
                            </form>
                        </div>
                            ";
                        }
                    ?>
                    </div>
                </div>

                </div>
            </div>
        </div>

    <!-- ----------------------------------------------------------------------------------------------------------- -->

    <!-- Change Pass -->

    <div class="modal fade " id="Change-Pass-Modal" tabindex="0" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable profile-dialog">

            <div class="modal-content border-2 border-dark" style="border-radius: 20px; box-shadow: 0 7px #2B2B2B; max-width: 450px;">
                <div class="modal-header profile-header" style="padding: 5px;">

                        <button type="button" data-bs-toggle="modal" data-bs-target="#Profile-Modal" class="btn" data-bs-dismiss="modal" aria-label="Back">
                            <i class="fa-solid fa-angle-left fa-lg"></i>
                        </button>
                        <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark fa-lg" style="margin-left: 360px;"></i>
                        </button>

                </div>

                <!-- Modal Contents -->
                <div class="modal-body" style="color: #292929;">
                    <div class="icon text-center my-3"><img src="img/Profile icon.png" alt="" width="80" height="80"></div>

                    <div class="text-center">
                    <?php
                        require_once './src/db.php';
                        $db = new Database();
                        $conn = $db->getConnection();
                        $adminID = $_SESSION['adminID'];
                        $get = "SELECT admin_name, email FROM admin_table WHERE adminID = '$adminID'";
                        $getQuery = mysqli_query($conn, $get);

                        while($row = $getQuery->fetch_assoc()){
                            echo"
                            <h5>". $row['admin_name'] ."</h5>
                            <h6 style='text-decoration: underline; font-weight: 350;'><small class='text-body-secondary'>". $row['email']."</small></h6>
                            ";
                        }
                    ?>
                    <hr class="mx-auto" width = "90%" >
                    </div>

                    <div class="My-Profile text-center pb-4 pt-3">
                        <h4 style="background-color: #FAEF9B; display: inline-block; border-radius: 5px;">
                            Change Password
                        </h4>
                    </div>
                    <form action="" method="post">
                        <div class="Field-inputs mx-4" style="font-size: small;">
                            <div class="form-floating pb-4">
                                <input name="OldPass" type="password" class="form-control" id="password" placeholder="Password" required>
                                <label for="password"><i class="fa-solid fa-lock"></i> Current Password</label>
                                <div class="password-wrapper">
                                <span id="togglePassword" class="toggle-password"><i class="fa-solid fa-eye-slash fa-lg"></i></span>
                                </div>
                            </div>

                            <div class="form-floating pb-4">
                                <input name="newPass" type="password" class="form-control" id="new-password" placeholder="Password" required>
                                <label for="password"><i class="fa-solid fa-lock"></i> New Password</label>
                                <div class="password-wrapper">
                                <span id="togglePassword" class="toggle-password"><i class="fa-solid fa-eye-slash fa-lg"></i></span>
                                </div>
                            </div>

                            <div class="form-floating pb-4">
                                <input name="conNewPass" type="password" class="form-control" id="confirm-password" placeholder="Password" required>
                                <label for="password"><i class="fa-solid fa-lock"></i> Confirm Password</label>
                                <div class="password-wrapper">
                                <span id="togglePassword" class="toggle-password"><i class="fa-solid fa-eye-slash fa-lg"></i></span>
                                </div>
                            </div>

                        </div>
                        <div class="save-changes">
                            <button name="changePass" type="submit" class="mt-4 mb-5" id="save-btn1">
                                Save Change
                            </button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
     <!-- Side Bar Mobile View -->

    <div class="offcanvas offcanvas-start sidebar2 overflow-x-hidden overflow-y-hidden" tabindex="-1" id="offcanvasNav-Menu" aria-labelledby="staticBackdropLabel">
        <div class="d-flex align-items-center p-3 py-5">
            <a href="/dashboard" class="sidebar-logo fw-bold text-dark text-decoration-none fs-4" data-bs-dismiss="offcanvas" aria-label="Close">
                <img src="img/BeeMo Logo Side.png" width="173px" height="75px" alt="">
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
            <li class="sidebar-menu-item2 active">
                <a href="/parameterMonitoring">
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
            <li class="sidebar-menu-item2 ">
                <a href="/beeGuide">
                    <i class="fa-solid fa-book-open sidebar-menu-item-icon2"></i>
                    Bee Guide
                </a>
            </li>
            <li class="sidebar-menu-item2">
                <a href="/Worker">
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

    <script src="./js/fetchData.js"></script>
    <script src="./js/notification.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>