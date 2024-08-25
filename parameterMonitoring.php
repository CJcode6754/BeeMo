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
    require_once './src/db.php';
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
    <link rel="stylesheet" href="./css/parameter_monitoring.css">
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
                                <form action="harvestCycle.php" method="post">
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
                    <div class="navbar-link  border border-1 border-black rounded-5"  data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user"></i>
                    </div>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="/TermsAndConditions">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <form id="logoutForm" action="parameterMonitoring.php" method="post" style="display: none;">
                            	<input type="hidden" name="logout_btn" value="true">
                            </form>
                            <li class="dropdown-item" onclick="document.getElementById('logoutForm').submit();">Logout</li>
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

    <script src="./js/fetchData.js"></script>
    <script src="./js/notification.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>