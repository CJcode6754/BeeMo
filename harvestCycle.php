<?php
function season_start() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

season_start();
require_once './src/db.php';
require_once './src/harvest_function.php';

$db = new Database();
$conn = $db->getConnection();
$harvestCycle = new HarvestCycle($conn);

// Check if the admin is logged in
if (!isset($_SESSION['adminID'])) {
    header('Location: /'); // Redirect to login page if not logged in
    exit;
}

if(isset($_POST['dashboard'])){
    $_SESSION['adminID'] = $adminID;
    exit;
}
// Handle logout
if (isset($_POST['logout_btn'])) {
    $_SESSION = array();
    session_destroy();
    header('Location: /');
    exit;
}

// Handle form submissions
if (isset($_POST['submit'])) {
    $harvestCycle->insertCycle($_POST['cycle_num'], $_POST['start_date'], $_POST['end_date'], $_SESSION['adminID']);
    header('Location: /harvestCycle');
    exit;
}

if (isset($_POST['btn_edit'])) {
    $current_cycle_num = $_POST['cycle_number'];
    $new_cycle_num = $_POST['edit_cycle_num'];

    $harvestCycle->editCycle($current_cycle_num, $new_cycle_num, $_POST['edit_start_date'], $_POST['edit_end_date'], $_SESSION['adminID']);
    header('Location: /harvestCycle');
    exit;
}

// Fetch the next cycle number
$nextCycleNumber = 1; // Default to 1 if no cycles exist
$query_next_cycle = "SELECT MAX(cycle_number) AS max_cycle_num FROM harvest_cycle WHERE adminID = '".$_SESSION['adminID']."'";
$result_next_cycle = mysqli_query($conn, $query_next_cycle);
if ($row = mysqli_fetch_assoc($result_next_cycle)) {
    $nextCycleNumber = $row['max_cycle_num'] + 1;
}

// Get the current date in YYYY-MM-DD format
$currentDate = date('Y-m-d');

$adminID = $_SESSION['adminID'];
$filter = isset($_POST['filter_value']) ? $_POST['filter_value'] : 'all'; // Default to 'all'

$select_cycle = "SELECT cycle_number, start_of_cycle, honey_kg, end_of_cycle, status FROM harvest_cycle WHERE adminID = '$adminID'";

if ($filter == 'pending') {
    $select_cycle .= " AND status = 0";
} elseif ($filter == 'complete') {
    $select_cycle .= " AND status = 1";
}

$query_select_cycle = mysqli_query($conn, $select_cycle);
$filtered_cycles = mysqli_fetch_all($query_select_cycle, MYSQLI_ASSOC);
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
            <a href="/dashboard" class="sidebar-logo fw-bold text-dark text-decoration-none fs-4"><img src="img/BeeMo Logo Side.png" width="173px" height="75px" alt="BeeMo Logo"></a>
        </div>
        <ul class="sidebar-menu p-3 py-1 m-0 mb-0">
            <li class="sidebar-menu-item">
                <a href="/dashboard" name="dashboard">
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
            <li class="sidebar-menu-item active">
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
                            <div>
                                <form action="/harvestCycle" method="post">
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
                        <form id="logoutForm" action="/harvestCycle" method="post" style="display: none;">
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
            <div class="cycle-page py-3 mt-4 border border-2 rounded-4 border-dark">
                <div class="px-4 py-3 my-4 text-center content-wrapper">
                    <p class="fs-4 mb-5 fw-bold cycle-highlight">Harvest Cycle</p>
                    <div class="container-cycle">

                    <!-- FORM TO RECORD HARVEST CYCLE -->
                    <form action="harvestCycle.php" method="post" class="row mt-2 g-3">
                        <div class="col-md-4">
                            <label for="cycleNumber" class="form-label d-flex justify-content-start" style="font-size: 13px;">Cycle Number</label>
                            <input name="cycle_num" type="number" class="form-control rounded-3 py-2" style="border: 1.8px solid #2B2B2B; font-size: 13px;" id="cycleNumber" required="This is required" value="<?php echo $nextCycleNumber; ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="cycleStart" class="form-label d-flex justify-content-start" style="font-size: 13px;">Start of Cycle</label>
                            <input name="start_date" type="date" class="form-control rounded-3 py-2" style="border: 1.8px solid #2B2B2B; font-size: 13px;" id="cycleStart" required="This is required" min="<?php echo $currentDate; ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="cycleEnd" class="form-label d-flex justify-content-start"  style="font-size: 13px;">End of Cycle</label>
                            <input name="end_date" type="date" class="form-control rounded-3 py-2" style="border: 1.8px solid #2B2B2B; font-size: 13px;" id="cycleEnd" required="This is required" min="<?php echo $currentDate; ?>">
                        </div>
                        <div class="mt-4 d-flex justify-content-end">
                            <button name="submit" type="submit" class="save-button px-4 border border-1 border-black fw-semibold">Save</button>
                        </div>
                    </form>

                    <div class="table-responsive mt-2" style="max-height: 130px; overflow-y: auto;">
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
                                <?php foreach ($filtered_cycles as $row): ?>
                                    <?php
                                        $start_date = new DateTime($row['start_of_cycle']);
                                        $end_date = new DateTime($row['end_of_cycle']);
                                        $now = new DateTime();

                                        $total_duration = $end_date->getTimestamp() - $start_date->getTimestamp();
                                        $elapsed_duration = $now->getTimestamp() - $start_date->getTimestamp();

                                        $progress_percentage = $total_duration > 0 ? ($elapsed_duration / $total_duration) * 100 : 0;
                                        $progress_percentage = min(max($progress_percentage, 0), 100);

                                        $progress_color = $row['status'] == 1 ? '#F9E37F' : ($progress_percentage >= 100 ? '#F9E37F' : '#4caf50');
                                        $icon = $row['status'] == 1 ? "<i class='fa-solid fa-check'></i>" : "";
                                        $editModalID = 'Edit_HarvestModal_' . $row['cycle_number'];
                                        $deleteModalID = 'Delete_HarvestModal_' . $row['cycle_number'];
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['cycle_number']) ?></td>
                                            <td><?= htmlspecialchars($row['start_of_cycle']) ?></td>
                                            <td><?= htmlspecialchars($row['honey_kg']) ?></td>
                                            <td><?= htmlspecialchars($row['end_of_cycle']) ?></td>
                                            <td>
                                                <div class='status_pending'>
                                                    <div class='progress-circle' style='background: conic-gradient(
                                                        <?= $progress_color ?> <?= $progress_percentage ?>%,
                                                        #f3f3f3 <?= $progress_percentage ?>%
                                                    )'></div>
                                                </div>
                                                <div class='status-icon'><?= $icon ?></div>
                                            </td>
                                            <td>
                                                <button name='btn_edit' class='btn edit-btn' data-bs-toggle='modal' type='button' data-bs-target='#<?= $editModalID ?>'>
                                                    <i class='fa-regular fa-pen-to-square'></i>
                                                </button>
                                                <!-- Edit Modal -->
                                                <div class='modal fade' id='<?= $editModalID ?>' tabindex='-1' aria-labelledby='Edit_CycleLabel_<?= $editModalID ?>' aria-hidden='true'>
                                                    <div class='modal-dialog modal-lg modal-dialog-centered rounded-3'>
                                                        <div class='modal-content' style='border: 2px solid #2B2B2B;'>
                                                            <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                                                <h5 class='modal-title fw-semibold mx-4' id='Edit_CycleLabel_<?= $editModalID ?>'>Edit Harvest Cycle</h5>
                                                                <button name='close' type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                            </div>
                                                            <div class='modal-body m-5'>
                                                                <form action='/harvestCycle' method='post' class='row mt-2 g-3'>
                                                                    <div class='col-md-4'>
                                                                        <label for='cycleNumber_<?= $editModalID ?>' class='form-label d-flex justify-content-start' style='font-size: 13px;'>Cycle Number</label>
                                                                        <input name='edit_cycle_num' type='text' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='cycleNumber_<?= $editModalID ?>' value='<?= htmlspecialchars($row['cycle_number']) ?>' readonly>
                                                                    </div>
                                                                    <div class='col-md-4'>
                                                                        <label for='cycleStart_<?= $editModalID ?>' class='form-label d-flex justify-content-start' style='font-size: 13px;'>Start of Cycle</label>
                                                                        <input name='edit_start_date' type='date' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='cycleStart_<?= $editModalID ?>' value='<?= htmlspecialchars($row['start_of_cycle']) ?>' required min='<?= $currentDate ?>'>
                                                                    </div>
                                                                    <div class='col-md-4'>
                                                                        <label for='cycleEnd_<?= $editModalID ?>' class='form-label d-flex justify-content-start' style='font-size: 13px;'>End of Cycle</label>
                                                                        <input name='edit_end_date' type='date' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='cycleEnd_<?= $editModalID ?>' value='<?= htmlspecialchars($row['end_of_cycle']) ?>' required>
                                                                    </div>
                                                                    <div class='mt-4 d-flex justify-content-end'>
                                                                        <input type='hidden' name='cycle_number' value='<?= $row['cycle_number'] ?>'>
                                                                        <button name='btn_edit' type='submit' class='save-button px-4 border border-1 border-black fw-semibold'>Save</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <button class='btn delete-btn'><i class='fa-regular fa-trash-can' style='color: red;' data-bs-toggle='modal' type='button' data-bs-target='#<?= $deleteModalID ?>'></i></button>
                                                 <!-- Edit Modal -->
                                                 <div class='modal fade' id='<?= $deleteModalID ?>' tabindex='-1' aria-labelledby='Delete_CycleLabel_<?= $deleteModalID ?>' aria-hidden='true'>
                                                    <div class='modal-dialog modal-lg modal-dialog-centered rounded d-flex justify-content-center'>
                                                        <div class='modal-content' style='border: 2px solid #2B2B2B; width: 450px; height: 180px;'>
                                                            <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                                                <h5 class='modal-title fw-semibold mx-4' id='Delete_CycleLabel_<?= $deleteModalID ?>'>Are you sure you want to delete this cycle? </h5>
                                                            </div>
                                                            <div class='modal-body m-2 d-flex justify-content-center'>
                                                                <form action='/harvestCycle' method='post' class='row mt-2 g-1'>
                                                                    <div class='col-md-4 me-5'>
                                                                        <button type="button" class="btn btn-dark" data-bs-dismiss='modal' aria-label='Close'>No</button>
                                                                    </div>
                                                                    <div class='col-md-4'>
                                                                        <button name='btn_delete' type="submit" class="btn btn-success" >Yes</button>
                                                                        <input type='hidden' name='cycle_number' value='<?= $row['cycle_number'] ?>'>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                                        <!-- Filter -->
                                        <div class="dropdown mb-0" data-bs-toggle="dropdown" aria-expanded="false">
                                            <button class="filter-button btn= dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Filter
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item filter-option" data-value="all" href="#">All Harvest Cycle</a></li>
                                                <li><a class="dropdown-item filter-option" data-value="pending" href="#">Pending</a></li>
                                                <li><a class="dropdown-item filter-option" data-value="complete" href="#">Complete</a></li>
                                            </ul>
                                        </div>
                                        <form id="filterForm" action="/harvestCycle" method="post" style="display: none;">
                                            <input type="hidden" name="filter_value" value="">
                                        </form>

                                        <!-- View All Modal -->
                                        <button type="button" class="view-button px-4 border border-1 border-black fw-semibold" data-bs-toggle='modal' data-bs-target='#viewAllModal'>View All</button>
                                        <div class='modal fade' id='viewAllModal' tabindex='-1' aria-labelledby='ViewAllLabel' aria-hidden='true'>
                                            <div class='modal-dialog modal-lg modal-dialog-centered rounded-3'>
                                                <div class='modal-content' style='border: 2px solid #2B2B2B;'>
                                                    <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                                        <h5 class='modal-title fw-semibold mx-4' id='ViewAllLabel'>Harvest Cycle</h5>
                                                        <button name='closeBtn' type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                    </div>
                                                    <div class='modal-body m-5'>
                                                        <div class="table-responsive mt-2" style="max-height: 400px; overflow-y: auto;">
                                                            <table class="table cycle-table border-dark">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="background-color: #FAEF9B;">Cycle Number</th>
                                                                        <th style="background-color: #FAEF9B;">Start of Cycle</th>
                                                                        <th style="background-color: #FAEF9B">Honey (kg)</th>
                                                                        <th style="background-color: #FAEF9B;">End of Harvest</th>
                                                                        <th style="background-color: #FAEF9B;">Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="viewAllTableBody">
                                                                    <?php foreach ($filtered_cycles as $row): ?>
                                                                        <?php
                                                                        $start_date = new DateTime($row['start_of_cycle']);
                                                                        $end_date = new DateTime($row['end_of_cycle']);
                                                                        $now = new DateTime();

                                                                        $total_duration = $end_date->getTimestamp() - $start_date->getTimestamp();
                                                                        $elapsed_duration = $now->getTimestamp() - $start_date->getTimestamp();

                                                                        $progress_percentage = $total_duration > 0 ? ($elapsed_duration / $total_duration) * 100 : 0;
                                                                        $progress_percentage = min(max($progress_percentage, 0), 100);

                                                                        $progress_color = $row['status'] == 1 ? '#F9E37F' : ($progress_percentage >= 100 ? '#F9E37F' : '#4caf50');
                                                                        $icon = $row['status'] == 1 ? "<i class='fa-solid fa-check'></i>" : "";
                                                                        ?>
                                                                        <tr>
                                                                            <td><?= htmlspecialchars($row['cycle_number']) ?></td>
                                                                            <td><?= htmlspecialchars($row['start_of_cycle']) ?></td>
                                                                            <td><?= htmlspecialchars($row['honey_kg']) ?></td>
                                                                            <td><?= htmlspecialchars($row['end_of_cycle']) ?></td>
                                                                            <td>
                                                                                <div class='status_pending'>
                                                                                    <div class='progress-circle' style='background: conic-gradient(
                                                                                        <?= $progress_color ?> <?= $progress_percentage ?>%,
                                                                                        #f3f3f3 <?= $progress_percentage ?>%
                                                                                    )'></div>
                                                                                </div>
                                                                                <div class='status-icon'><?= $icon ?></div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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

                </div>
                </div>
            </div>
        </div>
            <div class="yellow mt-1 d-md-none fixed-bottom p-0 m-0"></div>
    </main>
    <!-- Side Bar Mobile View -->
    <div id="offcanvasNav-Menu" class="offcanvas offcanvas-end sidebar-mobile bg-white p-3 border border-dark" tabindex="-1" aria-labelledby="offcanvasRightLabel">
        <i class="fa-solid fa-xmark sidebar-close" data-bs-dismiss="offcanvas" aria-label="Close"></i>
        <div class="d-flex align-items-center">
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
            <li class="sidebar-menu-item active">
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

    <script>
        document.querySelectorAll('.filter-option').forEach(item => {
            item.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior
                const filterValue = this.getAttribute('data-value');
                const form = document.getElementById('filterForm');
                form.filter_value.value = filterValue;
                form.submit();
            });
        });

        const filterValue = "<?php echo $filter; ?>";
        document.querySelectorAll('.filter-option').forEach(item => {
            if (item.getAttribute('data-value') === filterValue) {
                document.querySelector('.dropdown-toggle').textContent = item.textContent;
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
                            const filterSelect = document.getElementById('filterSelect');
                            const viewAllTableBody = document.getElementById('viewAllTableBody');

                            // Filter function
                            filterSelect.addEventListener('change', function() {
                                const filterValue = this.value;

                                fetch('/harvestCycle', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: `filter_value=${filterValue}`
                                })
                                .then(response => response.text())
                                .then(data => {
                                    viewAllTableBody.innerHTML = data; // Update the View All modal table body with new data
                                })
                                .catch(error => console.error('Error:', error));
                            });
                        });
    </script>
    <script src="./js/notification.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>