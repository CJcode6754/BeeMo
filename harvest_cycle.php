<?php
// index.php

session_start();
require_once './src/db.php';
require_once './src/harvest_function.php';

$db = new Database();
$conn = $db->getConnection();
$harvestCycle = new HarvestCycle($db);

// Check if the admin is logged in
if (!isset($_SESSION['adminID'])) {
    header('Location: index.php'); // Redirect to login page if not logged in
    exit;
}

// Handle logout
if (isset($_POST['logout_btn'])) {
    $_SESSION = array();
    session_destroy();
    header('Location: index.php');
    exit;
}

// Handle form submissions
if (isset($_POST['submit'])) {
    $harvestCycle->insertCycle($_POST['cycle_num'], $_POST['start_date'], $_POST['end_date'], $_SESSION['adminID']);
    header('Location: harvest_cycle.php');
    exit;
}

if (isset($_POST['btn_delete'])) {
    $harvestCycle->deleteCycle($_POST['cycle_number'], $_SESSION['adminID']);
    header('Location: harvest_cycle.php');
    exit;
}

if (isset($_POST['btn_edit'])) {
    $current_cycle_num = $_POST['cycle_number'];
    $new_cycle_num = $_POST['edit_cycle_num'];

    $harvestCycle->editCycle($current_cycle_num, $new_cycle_num, $_POST['edit_start_date'], $_POST['edit_end_date'], $_SESSION['adminID']);
    header('Location: harvest_cycle.php');
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeeMo</title>
    <link rel="stylesheet" href="./css/harvest_cycle.css">
    <link rel="stylesheet" href="./css/reusable.css">
    <link rel="icon" href="img/beemo-ico.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b4ce5ff90a.js" crossorigin="anonymous"></script>
</head>

<body class="overflow-x-hidden">
  <!-- Sidebar -->
    <div id="sidebar" class="sidebar position-fixed top-0 bottom-0 bg-white border-end offcanvass">

        <div class="d-flex align-items-center p-3 py-5">
            <a href="dashboard.php" class="sidebar-logo fw-bold text-dark text-decoration-none fs-4"><img src="img/BeeMo Logo Side.png" width="173px" height="75px" alt="BeeMo Logo"></a>
        </div>
        <ul class="sidebar-menu p-3 py-1 m-0 mb-0">
            <li class="sidebar-menu-item">
                <a href="dashboard.php">
                    <i class="fa-solid fa-house sidebar-menu-item-icon"></i>
                    Home
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="choose_hive.php">
                    <i class="fa-solid fa-temperature-three-quarters sidebar-menu-item-icon"></i>
                    Parameters Monitoring
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="#">
                    <i class="fa-solid fa-newspaper sidebar-menu-item-icon"></i>
                    Reports
                </a>
            </li>
            <li class="sidebar-menu-item active">
                <a href="harvest_cycle.php">
                    <i class="fa-solid fa-arrows-spin sidebar-menu-item-icon"></i>
                    Harvest Cycle
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="beeguide.php">
                    <i class="fa-solid fa-book-open sidebar-menu-item-icon"></i>
                    Bee Guide
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="add_worker.php">
                    <i class="fa-solid fa-user sidebar-menu-item-icon"></i>
                    Worker
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="about.php">
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
                    <p class="d-none d-lg-block mt-3 mx-3 fw-semibold">Welcome to Harvest Cycle</p>
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
                        <li><a class="dropdown-item" href="termsandconditions.php">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <form id="logoutForm" action="dashboard.php" method="post" style="display: none;">
                        	<input type="hidden" name="logout_btn" value="true">
                        </form>
                        <li class="dropdown-item" onclick="document.getElementById('logoutForm').submit();">Logout</li>
                    </ul>
                </div>
            </nav>
            <!-- Content -->
            <div class="cycle-page py-3 mt-4 border border-2 rounded-4 border-dark">
                <div class="px-4 py-3 my-4 text-center content-wrapper">
                    <p class="fs-4 mb-5 fw-bold cycle-highlight">Harvest Cycle</p>
                    <div class="container-cycle">
                    <form action="harvest_cycle.php" method="post" class="row mt-2 g-3">
                        <div class="col-md-4">
                            <label for="cycleNumber" class="form-label d-flex justify-content-start" style="font-size: 13px;">Cycle Number</label>
                            <input name="cycle_num" type="text" class="form-control rounded-3 py-2" style="border: 1.8px solid #2B2B2B; font-size: 13px;" id="cycleNumber" required="This is required">
                        </div>
                        <div class="col-md-4">
                            <label for="cycleStart" class="form-label d-flex justify-content-start" style="font-size: 13px;">Start of Cycle</label>
                            <input name="start_date" type="date" class="form-control rounded-3 py-2" style="border: 1.8px solid #2B2B2B; font-size: 13px;" id="cycleStart" required="This is required">
                        </div>
                        <div class="col-md-4">
                            <label for="cycleEnd" class="form-label d-flex justify-content-start"  style="font-size: 13px;">End of Cycle</label>
                            <input name="end_date" type="date" class="form-control rounded-3 py-2" style="border: 1.8px solid #2B2B2B; font-size: 13px;" id="cycleEnd" required="This is required">
                        </div>
                        <div class="mt-4 d-flex justify-content-end">
                            <button name="submit" type="submit" class="save-button px-4 border border-1 border-black fw-semibold">Save</button>
                        </div>
                    </form>
                    <div class="table-responsive mt-4" style="max-height: 130px; overflow-y: auto;">
                        <table class="table cycle-table border-dark">
                            <thead>
                                <tr>
                                    <th style="background-color: #FAEF9B;">Cycle Number</th>
                                    <th style="background-color: #FAEF9B;">Start of Cycle</th>
                                    <th style="background-color: #FAEF9B">Honey (kg)</th>
                                    <th style="background-color: #FAEF9B;">End of Harvest</th>
                                    <th style="background-color: #FAEF9B;">Status</th>
                                    <th style="background-color: #FAEF9B;">Edit</th>
                                    <th style="background-color: #FAEF9B;">Remove</th>
                                </tr>
                            </thead>
                            <tbody id="cycleTableBody">
                            <?php
                                $adminID = $_SESSION['adminID'];
                                $select_cycle = "SELECT cycle_number, start_of_cycle, honey_kg, end_of_harvest, status FROM harvest_cycle WHERE adminID = '$adminID'";
                                $query_select_cycle = mysqli_query($conn, $select_cycle);

                                while ($row = $query_select_cycle->fetch_assoc()) {
                                    if ($row) {
                                        $start_date = new DateTime($row['start_of_cycle']);
                                        $end_date = new DateTime($row['end_of_harvest']);
                                        $now = new DateTime();

                                        // Calculate total duration and elapsed duration
                                        $total_duration = $end_date->getTimestamp() - $start_date->getTimestamp();
                                        $elapsed_duration = $now->getTimestamp() - $start_date->getTimestamp();

                                        // Avoid division by zero
                                        if ($total_duration > 0) {
                                            $progress_percentage = ($elapsed_duration / $total_duration) * 100;
                                            $progress_percentage = min(max($progress_percentage, 0), 100); // Ensure value is between 0 and 100
                                        } else {
                                            $progress_percentage = $elapsed_duration > 0 ? 100 : 0; // If total duration is zero and elapsed is positive, progress is 100
                                        }

                                        // Determine progress color
                                        // Change color to #F9E37F if status is 1, otherwise use progress percentage color
                                        $progress_color = $row['status'] == 1 ? '#F9E37F' : ($progress_percentage >= 100 ? '#F9E37F' : '#4caf50');

                                        // Update status if progress is complete and current status is 0
                                        if ($progress_percentage >= 100 && $row['status'] == 0) {
                                            $update_status = "UPDATE harvest_cycle SET status = 1 WHERE cycle_number = '".$row['cycle_number']."' AND adminID = '$adminID'";
                                            mysqli_query($conn, $update_status);
                                        }

                                        $icon = $row['status'] == 1 ? "<i class='fa-solid fa-check'></i>" : "";
                                    } else {
                                        $progress_percentage = 0;
                                        $progress_color = '#4caf50';
                                    }

                                    if ($row['status'] != 1) {
                                        $disable_btn = false;
                                    } else {
                                        $disable_btn = true;
                                    }
                                    if ($row) {
                                        $harvestModalID = 'Edit_HarvestModal_' . $row['cycle_number'];
                                        echo "
                                        <tr>
                                            <td>".$row['cycle_number']."</td>
                                            <td>".$row['start_of_cycle']."</td>
                                            <td>".$row['honey_kg']."</td>
                                            <td>".$row['end_of_harvest']."</td>
                                            <td>
                                                <div class='status_pending'>
                                                    <div class='progress-circle' style='background: conic-gradient(
                                                        $progress_color ".$progress_percentage."%,
                                                        #f3f3f3 ".$progress_percentage."%
                                                    );'></div>
                                                </div>
                                                <div class='status-icon'>$icon</div>
                                            </td>
                                            <td>
                                                <button echo $disable_btn ? 'disabled' : '' name='btn_edit' class='btn edit-btn' data-bs-toggle='modal' type='button' data-bs-target='#$harvestModalID'>
                                                    <i class='fa-regular fa-pen-to-square'></i>
                                                </button>
                                                <div class='modal fade' id='$harvestModalID' tabindex='-1' aria-labelledby='Edit_CycleLabel_$harvestModalID' aria-hidden='true'>
                                                    <div class='modal-dialog modal-lg modal-dialog-centered rounded-3'>
                                                        <div class='modal-content' style='border: 2px solid #2B2B2B;'>
                                                            <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                                                <h5 class='modal-title fw-semibold mx-4' id='Edit_CycleLabel_$harvestModalID'>Edit Harvest Cycle</h5>
                                                                <button name='close' type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                            </div>
                                                            <div class='modal-body m-5'>
                                                                <form action='harvest_cycle.php' method='post' class='row mt-2 g-3'>
                                                                    <div class='col-md-4'>
                                                                        <label for='cycleNumber_$harvestModalID' class='form-label d-flex justify-content-start' style='font-size: 13px;'>Cycle Number</label>
                                                                        <input name='edit_cycle_num' type='text' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='cycleNumber_$harvestModalID' value='".htmlspecialchars($row['cycle_number'], ENT_QUOTES)."' required>
                                                                    </div>
                                                                    <div class='col-md-4'>
                                                                        <label for='cycleStart_$harvestModalID' class='form-label d-flex justify-content-start' style='font-size: 13px;'>Start of Cycle</label>
                                                                        <input name='edit_start_date' type='date' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='cycleStart_$harvestModalID' value='".htmlspecialchars($row['start_of_cycle'], ENT_QUOTES)."' required>
                                                                    </div>
                                                                    <div class='col-md-4'>
                                                                        <label for='cycleEnd_$harvestModalID' class='form-label d-flex justify-content-start' style='font-size: 13px;'>End of Cycle</label>
                                                                        <input name='edit_end_date' type='date' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='cycleEnd_$harvestModalID' value='".htmlspecialchars($row['end_of_harvest'], ENT_QUOTES)."' required>
                                                                    </div>
                                                                    <div class='mt-4 d-flex justify-content-end'>
                                                                        <input type='hidden' name='cycle_number' value='".$row['cycle_number']."'>
                                                                        <button name='btn_edit' type='submit' class='save-button px-4 border border-1 border-black fw-semibold'>Save</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <form method='post' action='harvest_cycle.php'>
                                                <input type='hidden' name='cycle_number' value='". $row['cycle_number'] ."'>
                                                <td><button name='btn_delete' type='submit' class='btn delete-btn'><i class='fa-regular fa-trash-can' style='color: red;'></i></button></td>
                                            </form>
                                        </tr>
                                        ";
                                    }
                                     else {
                                        // Default values if no result
                                        $progress_percentage = 0;
                                        $progress_color = '#4caf50';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="legend">
                        <div class="complete"><i class='fa-solid fa-check'></i> <span class="text_legend1">Completed Cycle</span></div>
                        <div class="pending">
                            <div class="circle"></div>
                        </div>
                        <span class="text_legend2">Pending Cycle</span>
                    </div>
                </div>
                </div>
            </div>
        </div>
            <div class="yellow mt-1 d-md-none fixed-bottom p-0 m-0"></div>
    </main>

     <!-- Side Bar Mobile View -->

    <div class="offcanvas offcanvas-start sidebar2 overflow-x-hidden overflow-y-hidden" tabindex="-1" id="offcanvasNav-Menu" aria-labelledby="staticBackdropLabel">
        <div class="d-flex align-items-center p-3 py-5">
            <a href="dashboard.php" class="sidebar-logo fw-bold text-dark text-decoration-none fs-4" data-bs-dismiss="offcanvas" aria-label="Close">
                <img src="img/BeeMo Logo Side.png" width="173px" height="75px" alt="BeeMo Logo">
            </a>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <ul class="sidebar-menu p-2 py-2 m-0 mb-0">
            <li class="sidebar-menu-item2">
                <a href="dashboard.php">
                    <i class="fa-solid fa-house sidebar-menu-item-icon2"></i>
                    Home
                </a>
            </li>
            <li class="sidebar-menu-item2 py-1">
                <a href="choose_hive.php">
                    <i class="fa-solid fa-temperature-three-quarters sidebar-menu-item-icon2"></i>
                    Parameters Monitoring
                </a>
            </li>
            <li class="sidebar-menu-item2">
                <a href="#">
                    <i class="fa-solid fa-newspaper sidebar-menu-item-icon2"></i>
                    Reports
                </a>
            </li>
            <li class="sidebar-menu-item2 active">
                <a href="harvest_cycle.php">
                    <i class="fa-solid fa-arrows-spin sidebar-menu-item-icon2"></i>
                    Harvest Cycle
                </a>
            </li>
            <li class="sidebar-menu-item2">
                <a href="beeguide.php">
                    <i class="fa-solid fa-book-open sidebar-menu-item-icon2"></i>
                    Bee Guide
                </a>
            </li>
            <li class="sidebar-menu-item2">
                <a href="add_worker.php">
                    <i class="fa-solid fa-user sidebar-menu-item-icon2"></i>
                    Worker
                </a>
            </li>
            <li class="sidebar-menu-item2">
                <a href="about.php">
                    <i class="fa-solid fa-circle-info sidebar-menu-item-icon2"></i>
                    About
                </a>
            </li>
        </ul>
    </div>
    </div>

    <script src="./js/script.js"></script>
    <script src="./js/notification.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>