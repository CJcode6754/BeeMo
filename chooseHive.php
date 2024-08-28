<?php
    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    function season_start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['season_started'])) {
            $_SESSION['season_started'] = true;
        }
    }

    season_start();

    if (isset($_POST['logout_btn'])) {
        // Unset all session variables
        $_SESSION = array();
        session_destroy();
        header('Location: /');
        exit;
    }

    if(isset($_POST['clearNotif'])){
        $clearNotif = "DELETE FROM tblNotification WHERE adminID = '".$_SESSION['adminID']."'";
        $clearNotifResult = mysqli_query($conn, $clearNotif);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeeMo</title>
    <link rel="stylesheet" href="./css/choose_hive.css">
    <link rel="stylesheet" href="./css/reusable.css">
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
                                <form action="/chooseHive" method="post">
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
                            <a class="dropdown-item" href="termsandconditions.html">
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
                        <form id="logoutForm" action="/chooseHive" method="post" style="display: none;">
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
            <div class="choosehive-page py-4 mt-4 border border-2 rounded-4 border-dark">
                <div class="px-4 py-2 my-4 text-center content-wrapper">
                    <div class="col-lg-6 mx-auto">
                        <p class="choosehive-text fs-4 mb-5 fw-bold choosehive-highlight">Choose Hive</p>
                        <div class=" mt-4 gap-2 d-block justify-content-sm-center">
                            <a href="/parameterMonitoring"><button type="button" class="hive-button mt-4 px-5 fs-5 fw-semibold">
                                Hive 1</button></a>
                            <button type="button" class="hive-button mt-5 px-5 fs-5 fw-semibold">
                                Hive 2</button>
                          </div>
                    </div>
                    <div class="mt-4 d-flex justify-content-end pe-3">
                        <button class="edit-button mt-5 px-4 border border-1 border-black fw-semibold" type="button">
                            <i class="fa-solid fa-pen edit-icon"></i> Edit
                        </button>
                    </div>
                </div>
            <div class="yellow mt-1 d-md-none fixed-bottom p-0 m-0"></div>
        </div>
    </main>

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

    <script src="./js/notification.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>