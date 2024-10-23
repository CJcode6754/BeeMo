<?php
require_once './src/db.php';
require_once './src/profileFunction.php';

$db = new Database();
$conn = $db->getConnection();

if (isset($_POST['logout_btn'])) {
    session_destroy();
    header('Location: /');
    exit();
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
    <link rel="stylesheet" href="./css/reports.css">
    <link rel="stylesheet" href="./css/reusable.css">
    <link rel="stylesheet" href="./css/profile.css">
    <link rel="icon" href="img/beemo-ico.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b4ce5ff90a.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@latest"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body class="overflow-x-hidden">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar position-fixed top-0 bottom-0 bg-white border-end offcanvass">

        <div class="d-flex align-items-center p-3 py-5">
            <a href="#" class="sidebar-logo fw-bold text-dark text-decoration-none fs-4"><img
                    src="img/BeeMo Logo Side.png" width="173px" height="75px" alt="BeeMo Logo"></a>
        </div>
        <ul class="sidebar-menu p-3 py-1 m-0 mb-0">
            <li class="sidebar-menu-item ">
                <a href="/dashboard">
                    <i class="fa-solid fa-house sidebar-menu-item-icon"></i>
                    Home
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="/parameterMonitoring">
                    <i class="fa-solid fa-temperature-three-quarters sidebar-menu-item-icon"></i>
                    Parameters Monitoring
                </a>
            </li>
            <li class="sidebar-menu-item active">
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
            <!-- Navbar -->
            <nav class="px-3 py-3 rounded-4">
                <div>
                    <p class="d-none d-lg-block mt-3 mx-3 fw-semibold">Welcome to Reports</p>
                </div>
                <i class="fa-solid fa-bars sidebar-toggle me-3 d-block d-lg-none" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasNav-Menu" aria-controls="offcanvasRight"
                    aria-expanded="false" aria-label="Toggle navigation"></i>
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
                                <form action="/reports" method="post">
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
                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#Profile-Modal"">
                                <i class=" fa-solid fa-user"></i>
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
                        <form id="logoutForm" action="/reports" method="post" style="display: none;">
                            <input type="hidden" name="logout_btn" value="true">
                        </form>
                        <li class="dropdown-item" onclick="document.getElementById('logoutForm').submit();">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Logout
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- Content -->
            <div class="reports-page py-3 mt-4 border border-2 rounded-4 border-dark">
                <div class="px-4 py-3  text-center content-wrapper" style="max-height: 450px; overflow-y: auto; scroll-behavior: smooth;">
                <p class="fs-4 mb-3 fw-bold reports-highlight">Reports</p>
                    <div class="container-top">
                        <div class="date-pick-size d-flex justify-content-center mb-4 mt-3 ">
                            <div class="input-group">
                                <input id="start-date-picker" type="text" class="form-control" placeholder="Select Date">
                                <span class="input-group-text" id="calendar-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                            </div>
                        </div>

                        <div class="container-label d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mb-2">
                            <!-- Label Container (Start) -->
                            <div class="label-container btn-group d-flex justify-content-center mb-3 mb-md-0">
                                <a href="#/temperature" class="btn btn-label label-current" data-type="temperature">Temperature</a>
                                <a href="#/humidity" class="btn btn-label" data-type="humidity">Humidity</a>
                                <a href="#/weight" class="btn btn-label" data-type="weight">Weight</a>
                            </div>

                            <!-- Filter Container (End) -->
                            <div class="filter-container d-flex justify-content-center justify-content-md-end align-items-center gap-2">
                                <!-- Select Harvest Cycle -->
                                <div class="dropdown">
                                    <button class="select-harvest-btn dropdown-toggle" type="button" id="harvestCycleDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        Admin Harvest Cycle
                                    </button>
                                    <ul class="dropdown-menu" id="harvestCycleList">
                                        <!-- Options will be populated via JavaScript -->
                                    </ul>
                                </div>

                                <!-- Filter by Month -->
                                <div class="dropdown">
                                    <button class="filter-btn dropdown-toggle" type="button" id="monthlyFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                        Filter by Month
                                    </button>
                                    <ul class="dropdown-menu" id="monthDropdown" aria-labelledby="monthlyFilter">

                                    </ul>
                                </div>

                                <div class="dropdown">
                                    <button class="select-harvest-btn dropdown-toggle" type="button" id="userHarvestCycleDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        Worker Harvest Cycle
                                    </button>
                                    <ul class="dropdown-menu" id="userHarvestCycleList">
                                        <!-- Options will be populated via JavaScript -->
                                    </ul>
                                </div>

                                <!-- Filter by Month -->
                                <div class="dropdown">
                                    <button class="filter-btn dropdown-toggle" type="button" id="userMonthlyFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                        Filter by Month
                                    </button>
                                    <ul class="dropdown-menu" id="userMonthDropdown" aria-labelledby="monthlyFilter">
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="container-chart">
                            <div class="chart-container">
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="legends d-flex justify-content-center gap-3 mt-4">
                        <span class="badge" style="background-color: rgba(0, 255, 0, 0.2); color: #2B2B2B;">Optimal Range</span>
                        <span class="badge" style="background-color: rgba(255, 127, 127, 0.4); color: #2B2B2B;">Out of Optimal Range</span>
                    </div>

                    <div class="descriptive-analytics-container d-flex justify-content-center mt-2 row row-cols-2 row-cols-lg-5 g-2 g-lg-3">
                        <p>Type: <span id="date-range-label">-</span></p>
                        <p>Average: <span id="average-value">-</span></p>
                        <p>Minimum: <span id="min-value">-</span></p>
                        <p>Maximum: <span id="max-value">-</span></p>
                    </div>
                </div>
            </div>
            <div class="space mt-1 d-md-none p-0 m-0"></div>
        <div class="yellow mt-1 d-md-none fixed-bottom p-0 m-0"></div>
    </main>

    <!-- Side Bar Mobile View -->
    <div class="offcanvas offcanvas-start sidebar2 overflow-x-hidden overflow-y-hidden" tabindex="-1"
            id="offcanvasNav-Menu" aria-labelledby="staticBackdropLabel">
            <div class="d-flex align-items-center p-3 py-5">
                <a href="#" class="sidebar-logo fw-bold text-dark text-decoration-none fs-4" data-bs-dismiss="offcanvas"
                    aria-label="Close">
                    <img src="img/BeeMo Logo Side.png" width="173px" height="75px" alt="BeeMo Logo">
                </a>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <ul class="sidebar-menu p-2 py-2 m-0 mb-0">
                <li class="sidebar-menu-item2 ">
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
                <li class="sidebar-menu-item2 active">
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

                        while ($row = $getQuery->fetch_assoc()) {
                            echo "
                            <h5>" . $row['admin_name'] . "</h5>
                            <h6 style='text-decoration: underline; font-weight: 350;'><small class='text-body-secondary'>" . $row['email'] . "</small></h6>
                            ";
                        }
                        ?>
                        <hr class="mx-auto" width="80%">
                    </div>

                    <div class="Options mx-auto py-4">

                        <button type="button" id="edit-profile-button" data-bs-toggle="modal" data-bs-target="#Edit-Profile-Modal" style="padding: 10px;">
                            <p><i class="fa-solid fa-user"></i> <span>My Profile</span><i class="fa-solid fa-angle-right"></i></p>
                        </button>

                        <button type="button" id="edit-profile-button" data-bs-toggle="modal" data-bs-target="#Change-Pass-Modal" style="padding: 10px;">
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

                        while ($row = $getQuery->fetch_assoc()){
                            $currentName = $row['admin_name'];
                            $currentEmail = $row['email'];
                            $currentPhoneNumber = $row['number'];
                            echo "
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

                        while ($row = $getQuery->fetch_assoc()) {
                            echo "
                            <h5>" . $row['admin_name'] . "</h5>
                            <h6 style='text-decoration: underline; font-weight: 350;'><small class='text-body-secondary'>" . $row['email'] . "</small></h6>
                            ";
                        }
                        ?>
                        <hr class="mx-auto" width="90%">
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
    </div>
    <script src="/js/notification.js"></script>
    <script src="./js/reusable.js"></script>
    <script src="/js/reports.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
</body>

</html>