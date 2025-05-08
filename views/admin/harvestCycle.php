<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeeMo</title>
    <link rel="stylesheet" href="./css/harvest_cycle8.css">
    <link rel="stylesheet" href="./css/reusable1.css">
    <link rel="stylesheet" href="./css/profile.css">
    <link rel="icon" href="img/beemo-ico.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b4ce5ff90a.js" crossorigin="anonymous"></script>
</head>

<body class="overflow-x-hidden">
    <!-- Sidebar -->
    <?php require"views/partials/sidebar.php" ?>

    <!-- Main -->
    <main class="bg-light">
        <div class="p-2">
            <!-- Navbar -->
            <?php require"views/partials/nav.php" ?>
            
            <!-- Content -->
            <div class="cycle-page py-3 mt-4 border border-2 rounded-4 border-dark">
                <div id="preloader">
                    <div class="container"></div>
                </div>
                <div class="px-4 py-3 my-4 text-center content-wrapper">
                    <p class="fs-4 mb-5 fw-bold cycle-highlight">Harvest Cycle</p>
                    <div class="container-cycle">
                        <!-- FORM TO RECORD HARVEST CYCLE -->
                        <form action="harvestCycle.php" method="post" class="row mt-2 g-3">
                            <div class="col-md-12">
                                <label for="autoCycleToggle" class="form-label d-flex justify-content-center" style="font-size: 13px;">Enable Auto Cycle Dates</label>
                                <label class="switch">
                                    <input type="checkbox" id="autoCycleToggle">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label for="cycleNumber" class="form-label d-flex justify-content-start" style="font-size: 13px;">Cycle Number</label>
                                <input name="cycle_num" type="number" class="form-control rounded-3 py-2" style="border: 1.8px solid #2B2B2B; font-size: 13px;" id="cycleNumber" required="This is required" value="" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="cycleStart" class="form-label d-flex justify-content-start" style="font-size: 13px;">Start of Cycle</label>
                                <input name="start_date" type="date" class="form-control rounded-3 py-2" style="border: 1.8px solid #2B2B2B; font-size: 13px;" id="cycleStart" required="This is required">
                            </div>
                            <div class="col-md-4">
                                <label for="cycleEnd" class="form-label d-flex justify-content-start" style="font-size: 13px;">End of Cycle</label>
                                <input name="end_date" type="date" class="form-control rounded-3 py-2" style="border: 1.8px solid #2B2B2B; font-size: 13px;" id="cycleEnd" required="This is required">
                            </div>
                            <div class="mt-4 d-flex justify-content-end">
                                <button name="submit" type="submit" class="save-button px-4 border border-1 border-black fw-semibold">Save</button>
                            </div>
                        </form>

                        <!-- Filter -->
                        <div class="container-btn d-flex flex-column flex-md-row justify-content-between align-items-center w-100 gap-0 gap-md-2 my-3">
                            <!-- Show Tables (aligned with buttons on start and end) -->
                            <div class="d-flex justify-content-start">
                                <div class="show-table-container d-flex justify-content-center w-100 mb-2 mb-md-0">
                                    <div class="show-container-one">
                                    <button id="showTable1" class="show-table-one">Admin Cycle</button>
                                    </div>
                                    <div class="show-container-two">
                                    <button id="showTable2" class="show-table-two">Worker Cycle</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Filter (aligned with filter on start and view all on end) -->

                            <div class="d-flex justify-content-end">
                            <div class="filter-container d-flex justify-content-center w-100">
                            <button class="filter-button dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Filter
                            </button>
                            <ul class=" dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item filter-option" data-value="all" href="#">All Harvest Cycle</a></li>
                                <li><a class="dropdown-item filter-option" data-value="pending" href="#">Pending</a></li>
                                <li><a class="dropdown-item filter-option" data-value="complete" href="#">Complete</a></li>
                            </ul>
                            <form id="filterForm" action="/harvestCycle" method="post" style="display: none;">
                                <input type="hidden" name="filter_value" value="">
                            </form>

                            <button type="button" class="view-button px-4" data-bs-toggle='modal' data-bs-target='#viewAllModal'>View All</button>
                        </div>
                        </div>
                        </div>
                        <div class='modal fade' id='viewAllModal' tabindex='-1' aria-labelledby='ViewAllLabel' aria-hidden='true'>
                            <div class='modal-dialog modal-lg modal-dialog-centered rounded-3'>
                                <div class='modal-content' style='border: 2px solid #2B2B2B;'>
                                    <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                        <h5 class='modal-title fw-semibold mx-4' id='ViewAllLabel'>Harvest Cycle</h5>
                                        <button name='closeBtn' type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body m-5'>
                                        <div class="button-group my-3 text-center">
                                            <!-- Button 1 to show Table 1 -->
                                            <button id="showViewAllTable1"  class="show-table-one">Admin Cycle</button>
                                            <!-- Button 2 to show Table 2 -->
                                            <button id="showViewAllTable2"  class="show-table-two">Worker Cycle</button>
                                        </div>


                                        <div id="viewAllTable1" class="table-responsive mt-2" style="max-height: 400px; overflow-y: auto;">
                                            <table class="table cycle-table border-dark">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color: #FAEF9B;">Cycle Number</th>
                                                        <th style="background-color: #FAEF9B;">Start of Cycle</th>
                                                        <th style="background-color: #FAEF9B">Hive Weight Gain(kg)</th>
                                                        <th style="background-color: #FAEF9B;">End of Harvest</th>
                                                        <th style="background-color: #FAEF9B;">Status</th>
                                                        <th style="background-color: #FAEF9B;">Hive Number</th>
                                                    </tr>
                                                </thead>
                                                <!-- <tbody id="viewAllTableBody">
                                                    <?php foreach ($filtered_cycles as $row): ?>
                                                        <?php
                                                        $start_date = new DateTime($row['start_of_cycle']);
                                                        $end_date = new DateTime($row['end_of_cycle']);
                                                        $now = new DateTime();
                                                        
                                                        // Set the time for the end of the current day (23:59:59)
                                                        $end_of_day = new DateTime();
                                                        $end_of_day->setTime(23, 59, 59);
                                                        
                                                        // Format the dates as strings in 'Y-m-d' format
                                                        $start_date_str = $start_date->format('Y-m-d');
                                                        $end_date_str = $end_date->format('Y-m-d');
                                                        $end_of_day_str = $end_of_day->format('Y-m-d H:i:s'); // End of current day (23:59:59)
                                                        
                                                        // Determine the upper bound for the cycle range
                                                        $end_bound_str = ($now >= $end_date) ? $end_date_str : $end_of_day->format('Y-m-d');
                                                        
                                                        // Prepare the SQL query to fetch average weights
                                                        $weightSql = "
                                                            SELECT 
                                                                AVG(CASE WHEN DATE(timestamp) = ? THEN weight END) AS start_cycle_weight,
                                                                AVG(CASE WHEN DATE(timestamp) = ? THEN weight END) AS end_cycle_weight
                                                            FROM subdata
                                                            WHERE 
                                                                DATE(timestamp) IN (?, ?)
                                                                AND adminID = ?
                                                                AND hiveID = ?
                                                        ";
                                                        
                                                        // Use prepared statements to prevent SQL injection
                                                        $stmt = $conn->prepare($weightSql);
                                                        if (!$stmt) {
                                                            echo json_encode(['error' => 'SQL prepare error: ' . $conn->error]);
                                                            exit;
                                                        }
                                                        
                                                        $adminID = $_SESSION['adminID'];
                                                        $hiveID = $_SESSION['hiveID'];
                                                        
                                                        $stmt->bind_param(
                                                            'ssssii',
                                                            $start_date_str,  // Start of cycle date
                                                            $end_bound_str,   // End date (can be the cycle end date or today)
                                                            $start_date_str,  // Start date again for query
                                                            $end_bound_str,   // End date again for query
                                                            $adminID,
                                                            $hiveID
                                                        );
                                                        
                                                        if (!$stmt->execute()) {
                                                            echo json_encode(['error' => 'SQL execute error: ' . $stmt->error]);
                                                            exit;
                                                        }
                                                        
                                                        // Fetch results
                                                        $result = $stmt->get_result()->fetch_assoc();
                                                        
                                                        $start_cycle_weight = $result['start_cycle_weight'] ?? 0;
                                                        $end_cycle_weight = $result['end_cycle_weight'] ?? 0;
                                                        
                                                        // Convert to kilograms if necessary (assuming the original weight is in grams)
                                                        $start_cycle_weight /= 1000;
                                                        $end_cycle_weight /= 1000;
                                                        
                                                        // Calculate weight gain
                                                        $weight_gain = $end_cycle_weight - $start_cycle_weight;
                                                        $weight_gain = number_format($weight_gain, 2);

                                                        // Calculate total and elapsed duration in seconds
                                                        $total_duration = $end_date->getTimestamp() - $start_date->getTimestamp();
                                                        $elapsed_duration = $now->getTimestamp() - $start_date->getTimestamp();

                                                        // If current date has passed the end date or the start and end dates are the same, set progress to 100%
                                                        if ($now >= $end_date) {
                                                            $progress_percentage = 100;
                                                        } else if ($total_duration > 0) {
                                                            // Calculate progress if within cycle duration
                                                            $progress_percentage = ($elapsed_duration / $total_duration) * 100;
                                                        } else {
                                                            // If the start and end dates are the same, set progress to 100%
                                                            $progress_percentage = 100;
                                                        }

                                                        // Clamp progress between 0 and 100%
                                                        $progress_percentage = min(max($progress_percentage, 0), 100);

                                                        // If progress is 100% and status is not updated, update the status to 1 (complete)
                                                        if ($progress_percentage == 100 && $row['status'] != 1) {
                                                            $cycle_number = $row['cycle_number'];
                                                            $update_status_query = "UPDATE harvest_cycle SET status = 1 WHERE cycle_number = '$cycle_number'";

                                                            // Execute the query and check for errors
                                                            if (mysqli_query($conn, $update_status_query)) {
                                                                // Update local status in the row
                                                                $row['status'] = 1;
                                                            } else {
                                                                // Debugging: Print error message if query fails
                                                                echo "Error updating record: " . mysqli_error($conn);
                                                            }
                                                        }

                                                        $progress_color = $row['status'] == 1 ? '#4caf50' : ($progress_percentage >= 100 ? '#4caf50' : '#F9E37F');
                                                        $editModalID = 'Edit_HarvestModal_' . $row['cycle_number'];
                                                        $deleteModalID = 'Delete_HarvestModal_' . $row['cycle_number'];
                                                        ?>

                                                        <tr>
                                                            <td><?= htmlspecialchars($row['cycle_number']) ?></td>
                                                            <td><?= htmlspecialchars($row['start_of_cycle']) ?></td>
                                                            <td><?= htmlspecialchars($weight_gain) ?> kg</td>
                                                            <td><?= htmlspecialchars($row['end_of_cycle']) ?></td>
                                                            <td>
                                                                <div class='status_pending'>
                                                                    <div class='progress-circle' style='background: conic-gradient(
                                                                    <?= $progress_color ?> <?= $progress_percentage ?>%,
                                                                    #f3f3f3 <?= $progress_percentage ?>%
                                                                )'></div>
                                                                </div>
                                                            </td>
                                                            <td><?= htmlspecialchars($row['hiveID']) ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody> -->
                                            </table>
                                        </div>
                                        <div id="viewAllTable2" class="table-responsive mt-2" style="max-height: 400px; overflow-y: auto;">
                                            <table class="table cycle-table1 border-dark">
                                                <thead>
                                                    <tr>
                                                        <th colspan="7" style="background-color: #FAEF9B;">Cycle created by worker</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="background-color: #FAEF9B;">Worker Name</th>
                                                        <th style="background-color: #FAEF9B;">Hive Number</th>
                                                        <th style="background-color: #FAEF9B;">Cycle Number</th>
                                                        <th style="background-color: #FAEF9B;">Start of Cycle</th>
                                                        <th style="background-color: #FAEF9B">Hive Weight Gain(kg)</th>
                                                        <th style="background-color: #FAEF9B;">End of Harvest</th>
                                                        <th style="background-color: #FAEF9B;">Status</th>
                                                    </tr>
                                                </thead>
                                                <!-- <tbody id="viewAllTableBody">
                                                <?php foreach ($filtered_cycles1 as $row): ?>
                                                        <?php
                                                        $start_date = new DateTime($row['user_start_of_cycle']);
                                                        $end_date = new DateTime($row['user_end_of_cycle']);
                                                        $now = new DateTime();
                                                        
                                                        // Set the time for the end of the current day (23:59:59)
                                                        $end_of_day = new DateTime();
                                                        $end_of_day->setTime(23, 59, 59);
                                                        
                                                        // Format the dates as strings in 'Y-m-d' format
                                                        $start_date_str = $start_date->format('Y-m-d');
                                                        $end_date_str = $end_date->format('Y-m-d');
                                                        $end_of_day_str = $end_of_day->format('Y-m-d H:i:s');
                                                        
                                                        // Determine the upper bound for the cycle range
                                                        $end_bound_str = ($now >= $end_date) ? $end_date_str : $end_of_day->format('Y-m-d');
                                                        
                                                        // Prepare the SQL query to fetch average weights
                                                        $weightSql = "
                                                            SELECT 
                                                                AVG(CASE WHEN DATE(timestamp) = ? THEN weight END) AS start_cycle_weight,
                                                                AVG(CASE WHEN DATE(timestamp) = ? THEN weight END) AS end_cycle_weight
                                                            FROM subdata
                                                            WHERE 
                                                                DATE(timestamp) IN (?, ?)
                                                                AND adminID = ?
                                                                AND hiveID = ?
                                                        ";
                                                        
                                                        // Use prepared statements to prevent SQL injection
                                                        $stmt = $conn->prepare($weightSql);
                                                        if (!$stmt) {
                                                            echo json_encode(['error' => 'SQL prepare error: ' . $conn->error]);
                                                            exit;
                                                        }
                                                        
                                                        $adminID = $_SESSION['adminID'];
                                                        $hiveID = $_SESSION['hiveID'];
                                                        
                                                        $stmt->bind_param(
                                                            'ssssii',
                                                            $start_date_str,  // Start of cycle date
                                                            $end_bound_str,   // End date (can be the cycle end date or today)
                                                            $start_date_str,  // Start date again for query
                                                            $end_bound_str,   // End date again for query
                                                            $adminID,
                                                            $hiveID
                                                        );
                                                        
                                                        if (!$stmt->execute()) {
                                                            echo json_encode(['error' => 'SQL execute error: ' . $stmt->error]);
                                                            exit;
                                                        }
                                                        
                                                        // Fetch results
                                                        $result = $stmt->get_result()->fetch_assoc();
                                                        
                                                        $start_cycle_weight = $result['start_cycle_weight'] ?? 0;
                                                        $end_cycle_weight = $result['end_cycle_weight'] ?? 0;
                                                        
                                                        // Convert to kilograms if necessary (assuming the original weight is in grams)
                                                        $start_cycle_weight /= 1000;
                                                        $end_cycle_weight /= 1000;
                                                        
                                                        // Calculate weight gain
                                                        $weight_gain = $end_cycle_weight - $start_cycle_weight;
                                                        $weight_gain = number_format($weight_gain, 2);
                                                        
                                                        // Assign weight stats
                                                        $stats['weight']['cycle_start'] = $start_cycle_weight;
                                                        $stats['weight']['cycle_end'] = $end_cycle_weight;
                                                        $stats['weight']['gain'] = $weight_gain;


                                                        // Calculate total and elapsed duration in seconds
                                                        $total_duration = $end_date->getTimestamp() - $start_date->getTimestamp();
                                                        $elapsed_duration = $now->getTimestamp() - $start_date->getTimestamp();

                                                        // If current date has passed the end date or the start and end dates are the same, set progress to 100%
                                                        if ($now >= $end_date) {
                                                            $progress_percentage = 100;
                                                        } else if ($total_duration > 0) {
                                                            // Calculate progress if within cycle duration
                                                            $progress_percentage = ($elapsed_duration / $total_duration) * 100;
                                                        } else {
                                                            // If the start and end dates are the same, set progress to 100%
                                                            $progress_percentage = 100;
                                                        }

                                                        // Clamp progress between 0 and 100%
                                                        $progress_percentage = min(max($progress_percentage, 0), 100);

                                                        // If progress is 100% and status is not updated, update the status to 1 (complete)
                                                        if ($progress_percentage == 100 && $row['status'] != 1) {
                                                            $cycle_number = $row['userCycleNumber'];
                                                            $update_status_query = "UPDATE user_harvest_cycle SET status = 1 WHERE userCycleNumber = '$cycle_number'";

                                                            // Execute the query and check for errors
                                                            if (mysqli_query($conn, $update_status_query)) {
                                                                // Update local status in the row
                                                                $row['status'] = 1;
                                                            } else {
                                                                // Debugging: Print error message if query fails
                                                                echo "Error updating record: " . mysqli_error($conn);
                                                            }
                                                        }

                                                        // Set the color and icon based on the status
                                                        $progress_color = $row['status'] == 1 ? '#4caf50' : ($progress_percentage >= 100 ? '#4caf50' : '#F9E37F');
                                                        $editModalID = 'Edit_HarvestModal_' . $row['userCycleNumber'];
                                                        $deleteModalID = 'Delete_HarvestModal_' . $row['userCycleNumber'];
                                                        ?>
                                                        <tr>
                                                            <th><?= htmlspecialchars($row['user_name']) ?></th>
                                                            <th><?= htmlspecialchars($row['hiveID']) ?></th>
                                                            <td><?= htmlspecialchars($row['userCycleNumber']) ?></td>
                                                            <td><?= htmlspecialchars($row['user_start_of_cycle']) ?></td>
                                                            <td><?= htmlspecialchars($weight_gain) ?> kg</td>
                                                            <td><?= htmlspecialchars($row['user_end_of_cycle']) ?></td>
                                                            <td>
                                                                <div class='status_pending'>
                                                                    <div class='progress-circle' style='background: conic-gradient(
                                                                    <?= $progress_color ?> <?= $progress_percentage ?>%,
                                                                    #f3f3f3 <?= $progress_percentage ?>%
                                                                )'></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody> -->
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- //TABLE 1 -->
                        <div id="table1Container" class="table-responsive mt-2" style="max-height: 130px; overflow-y: auto;">
                            <table class="table cycle-table border-dark">
                                <thead>
                                    <tr>
                                        <th style="background-color: #FAEF9B;">Cycle Number</th>
                                        <th style="background-color: #FAEF9B;">Start of Cycle</th>
                                        <th style="background-color: #FAEF9B">Hive Weight Gain(kg)</th>
                                        <th style="background-color: #FAEF9B;">End of Harvest</th>
                                        <th style="background-color: #FAEF9B;">Status</th>
                                        <th style="background-color: #FAEF9B;">Hive ID</th>
                                        <th style="background-color: #FAEF9B;">Edit</th>
                                        <th style="background-color: #FAEF9B;">Remove</th>
                                        <th style="background-color: #FAEF9B;">End Cycle?</th>
                                    </tr>
                                </thead>
                                <!-- <tbody id="cycleTableBody">
                                    <?php foreach ($filtered_cycles as $row): ?>
                                        <?php
                                        require_once './src/db.php';
                                        require_once './src/notification_handler.php';
                                        
                                        // $db = new Database();
                                        // $conn = $db->getConnection();
                                        // $notification = new NotificationHandler($conn);
    
                                        $start_date = new DateTime($row['start_of_cycle']);
                                        $end_date = new DateTime($row['end_of_cycle']);
                                        $now = new DateTime();
                                        
                                        // Set the time for the end of the current day (23:59:59)
                                        $end_of_day = new DateTime();
                                        $end_of_day->setTime(23, 59, 59);
                                        
                                        // Format the dates as strings in 'Y-m-d' format
                                        $start_date_str = $start_date->format('Y-m-d');
                                        $end_date_str = $end_date->format('Y-m-d');
                                        $end_of_day_str = $end_of_day->format('Y-m-d H:i:s'); // End of current day (23:59:59)
                                        
                                        // Determine the upper bound for the cycle range
                                        $end_bound_str = ($now >= $end_date) ? $end_date_str : $end_of_day->format('Y-m-d');
                                        
                                        // Prepare the SQL query to fetch average weights
                                        $weightSql = "
                                            SELECT 
                                                AVG(CASE WHEN DATE(timestamp) = ? THEN weight END) AS start_cycle_weight,
                                                AVG(CASE WHEN DATE(timestamp) = ? THEN weight END) AS end_cycle_weight
                                            FROM subdata
                                            WHERE 
                                                DATE(timestamp) IN (?, ?)
                                                AND adminID = ?
                                                AND hiveID = ?
                                        ";
                                        
                                        // Use prepared statements to prevent SQL injection
                                        $stmt = $conn->prepare($weightSql);
                                        if (!$stmt) {
                                            echo json_encode(['error' => 'SQL prepare error: ' . $conn->error]);
                                            exit;
                                        }
                                        
                                        $adminID = $_SESSION['adminID'];
                                        $hiveID = $_SESSION['hiveID'];
                                        
                                        $stmt->bind_param(
                                            'ssssii',
                                            $start_date_str,  // Start of cycle date
                                            $end_bound_str,   // End date (can be the cycle end date or today)
                                            $start_date_str,  // Start date again for query
                                            $end_bound_str,   // End date again for query
                                            $adminID,
                                            $hiveID
                                        );
                                        
                                        if (!$stmt->execute()) {
                                            echo json_encode(['error' => 'SQL execute error: ' . $stmt->error]);
                                            exit;
                                        }
                                        
                                        // Fetch results
                                        $result = $stmt->get_result()->fetch_assoc();
                                        
                                        $start_cycle_weight = $result['start_cycle_weight'] ?? 0;
                                        $end_cycle_weight = $result['end_cycle_weight'] ?? 0;
                                        
                                        // Convert to kilograms if necessary (assuming the original weight is in grams)
                                        $start_cycle_weight /= 1000;
                                        $end_cycle_weight /= 1000;
                                        
                                        // Calculate weight gain
                                        $weight_gain = $end_cycle_weight - $start_cycle_weight;
                                        $weight_gain = number_format($weight_gain, 2);

                                        // Calculate total and elapsed duration in seconds
                                        $total_duration = $end_date->getTimestamp() - $start_date->getTimestamp();
                                        $elapsed_duration = $now->getTimestamp() - $start_date->getTimestamp();

                                        // If current date has passed the end date or the start and end dates are the same, set progress to 100%
                                        if ($now >= $end_date) {
                                            $progress_percentage = 100;
                                        } else if ($total_duration > 0) {
                                            // Calculate progress if within cycle duration
                                            $progress_percentage = ($elapsed_duration / $total_duration) * 100;
                                        } else {
                                            // If the start and end dates are the same, set progress to 100%
                                            $progress_percentage = 100;
                                        }

                                        // Clamp progress between 0 and 100%
                                        $progress_percentage = min(max($progress_percentage, 0), 100);

                                        if ($progress_percentage == 100 && $row['status'] != 1) {
                                            $cycle_number = $row['cycle_number'];
                                            $update_status_query = "UPDATE harvest_cycle SET status = 1 WHERE cycle_number = '$cycle_number'";
                                        
                                            if (mysqli_query($conn, $update_status_query)) {
                                                $row['status'] = 1;
                                                
                                                // Define $notif as the cycle number
                                                $notif = $cycle_number;
                                        
                                                // Check if the notification has been sent before (using session to store flag)
                                                if (!isset($_SESSION['cycle_completed_notified']) || $_SESSION['cycle_completed_notified'] != $notif) {
                                                    $notification->insertNotification($adminID, 'active', "Cycle has been completed.", 'complete_cycle', '/harvest', 'unseen');
                                                    $_SESSION['cycle_completed_notified'] = $notif; 
                                                }
                                            }
                                        }
                                        
                                        // Check if the cycle is 3 days away from completion and the notification hasn't been sent
                                        $three_days_before_end = $end_date->modify('-3 days');  // Get the date 3 days before the end of cycle
                                        
                                        if ($now >= $three_days_before_end && $row['status'] != 1) {
                                            $cycle_number = $row['cycle_number'];
                                            // Define $notif as the cycle number for consistency
                                            $notif = $cycle_number;
                                        
                                            // Check if the 3-day warning notification has been sent before
                                            if (!isset($_SESSION['three_days_warning_notified']) || $_SESSION['three_days_warning_notified'] != $notif) {
                                                $notification->insertNotification($adminID, 'active', "Cycle is 3 days away from completion.", 'three_days_away', '/harvest', 'unseen');
                                                $_SESSION['three_days_warning_notified'] = $notif; // Store the cycle number to prevent resending
                                            }
                                        }

                                        // Set the color and icon based on the status
                                        $progress_color = $row['status'] == 1 ? '#4caf50' : ($progress_percentage >= 100 ? '#4caf50' : '#F9E37F');
                                        $editModalID = 'Edit_HarvestModal_' . $row['cycle_number'];
                                        $deleteModalID = 'Delete_HarvestModal_' . $row['cycle_number'];
                                        //$completeCycleID = 'Complete_HarvestModal' . $row['cycle_number'];
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['cycle_number']) ?></td>
                                            <td><?= htmlspecialchars($row['start_of_cycle']) ?></td>
                                            <td><?= htmlspecialchars($weight_gain) ?> kg</td>
                                            <td><?= htmlspecialchars($row['end_of_cycle']) ?></td>
                                            <td>
                                                <div class='status_pending'>
                                                    <div class='progress-circle' style='background: conic-gradient(
                                                    <?= $progress_color ?> <?= $progress_percentage ?>%,
                                                    #f3f3f3 <?= $progress_percentage ?>%
                                                )'></div>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($row['hiveID']) ?></td>
                                            <td>
                                                <!-- <?php 
                                                    $isCycleCompleted = $row['status'] == 1;
                                                ?> -->
                                                <button name='btn_edit' class='btn edit-btn' data-bs-toggle='modal' type='button' data-bs-target='#<?= $editModalID ?>' <?= $isCycleCompleted ? 'disabled' : '' ?> >
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
                                                                <form action='harvestCycle.php' method='post' class='row mt-2 g-3'>
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
                                                                        <input name='edit_end_date' type='date' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='cycleEnd_<?= $editModalID ?>' value='<?= htmlspecialchars($row['end_of_cycle']) ?>' required min='<?= $currentDate ?>'>
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
                                                <!-- Delete Modal -->
                                                <div class='modal fade' id='<?= $deleteModalID ?>' tabindex='-1' aria-labelledby='Delete_CycleLabel_<?= $deleteModalID ?>' aria-hidden='true'>
                                                    <div class='modal-dialog modal-lg modal-dialog-centered rounded d-flex justify-content-center'>
                                                        <div class='modal-content' style='border: 2px solid #2B2B2B; width: 500px; height: 180px;'>
                                                            <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                                                <h5 class='modal-title mx-5 d-flex justify-content-center' id='Delete_CycleLabel_<?= $deleteModalID ?>'>Are you sure you want to delete this cycle? </h5>
                                                            </div>
                                                            <div class='modal-body m-2 d-flex justify-content-center'>
                                                                <form action='harvestCycle.php' method='post' class='row mt-2 g-1'>
                                                                    <div class='col-md-4 me-5'>
                                                                        <button name='btn_delete' type="submit" class="btn-yes px-4 py-2">Yes</button>
                                                                        <input type='hidden' name='CycleID' value='<?= $row['id'] ?>'>
                                                                    </div>
                                                                    <div class='col-md-4'>
                                                                        <button type="button" class="btn-no px-4 py-2" data-bs-dismiss='modal' aria-label='Close'>No</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php 
                                                    $isCycleCompleted = $row['status'] == 1;
                                                ?>
                                                <button class='btn edit-btn' <?= $isCycleCompleted ? 'disabled' : '' ?>>
                                                    <i class='fa-regular fa-circle-stop' style='color: red;' data-bs-toggle='modal' type='button' data-bs-target='#<?= $completeCycleID ?>'></i>
                                                </button>

                                                <!-- Complete the Cycle Modal -->
                                                <div class='modal fade' id='<?= $completeCycleID ?>' tabindex='-1' aria-labelledby='Complete_CycleLabel_<?= $$completeCycleID ?>' aria-hidden='true'>
                                                    <div class='modal-dialog modal-lg modal-dialog-centered rounded d-flex justify-content-center'>
                                                        <div class='modal-content' style='border: 2px solid #2B2B2B; width: 500px; height: 180px;'>
                                                            <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                                                <h5 class='modal-title mx-5 d-flex justify-content-center' id='Complete_CycleLabel_<?= $completeCycleID ?>'>Are you sure you want to end this cycle? </h5>
                                                            </div>
                                                            <div class='modal-body m-2 d-flex justify-content-center'>
                                                                <form action='harvestCycle.php' method='post' class='row mt-2 g-1'>
                                                                    <div class='col-md-4 me-5'>
                                                                        <button name='btn_end' type="submit" class="btn-yes px-4 py-2">Yes</button>
                                                                        <input type='hidden' name='CycleID' value='<?= $row['id'] ?>'>
                                                                    </div>
                                                                    <div class='col-md-4'>
                                                                        <button type="button" class="btn-no px-4 py-2" data-bs-dismiss='modal' aria-label='Close'>No</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody> -->
                            </table>
                        </div>
                        
                        <!-- //TABLE 2 -->
                        <div id="table2Container" class="table-responsive mt-2" style="max-height: 130px; overflow-y: auto;">
                            <table class="table cycle-table border-dark">
                                <thead>
                                    <tr>
                                        <th style="background-color: #FAEF9B;">Worker Name</th>
                                        <th style="background-color: #FAEF9B;">Hive Number</th>
                                        <th style="background-color: #FAEF9B;">Cycle Number</th>
                                        <th style="background-color: #FAEF9B;">Start of Cycle</th>
                                        <th style="background-color: #FAEF9B">Hive Weight Gain(kg)</th>
                                        <th style="background-color: #FAEF9B;">End of Harvest</th>
                                        <th style="background-color: #FAEF9B;">Status</th>
                                        <th style="background-color: #FAEF9B;">Hive ID</th>
                                        <th style="background-color: #FAEF9B;">Edit</th>
                                        <th style="background-color: #FAEF9B;">Remove</th>
                                    </tr>
                                </thead>
                                <!-- <tbody id="cycleTableBody">
                                    <?php foreach ($filtered_cycles1 as $row): ?>
                                        <?php
                                        $start_date = new DateTime($row['user_start_of_cycle']);
                                        $end_date = new DateTime($row['user_end_of_cycle']);
                                        $now = new DateTime();
                                        
                                        // Set the time for the end of the current day (23:59:59)
                                        $end_of_day = new DateTime();
                                        $end_of_day->setTime(23, 59, 59);
                                        
                                        // Format the dates as strings in 'Y-m-d' format
                                        $start_date_str = $start_date->format('Y-m-d');
                                        $end_date_str = $end_date->format('Y-m-d');
                                        $end_of_day_str = $end_of_day->format('Y-m-d H:i:s');
                                        
                                        // Determine the upper bound for the cycle range
                                        $end_bound_str = ($now >= $end_date) ? $end_date_str : $end_of_day->format('Y-m-d');
                                        
                                        // Prepare the SQL query to fetch average weights
                                        $weightSql = "
                                            SELECT 
                                                AVG(CASE WHEN DATE(timestamp) = ? THEN weight END) AS start_cycle_weight,
                                                AVG(CASE WHEN DATE(timestamp) = ? THEN weight END) AS end_cycle_weight
                                            FROM subdata
                                            WHERE 
                                                DATE(timestamp) IN (?, ?)
                                                AND adminID = ?
                                                AND hiveID = ?
                                        ";
                                        
                                        // Use prepared statements to prevent SQL injection
                                        $stmt = $conn->prepare($weightSql);
                                        if (!$stmt) {
                                            echo json_encode(['error' => 'SQL prepare error: ' . $conn->error]);
                                            exit;
                                        }
                                        
                                        $adminID = $_SESSION['adminID'];
                                        $hiveID = $_SESSION['hiveID'];
                                        
                                        $stmt->bind_param(
                                            'ssssii',
                                            $start_date_str,  // Start of cycle date
                                            $end_bound_str,   // End date (can be the cycle end date or today)
                                            $start_date_str,  // Start date again for query
                                            $end_bound_str,   // End date again for query
                                            $adminID,
                                            $hiveID
                                        );
                                        
                                        if (!$stmt->execute()) {
                                            echo json_encode(['error' => 'SQL execute error: ' . $stmt->error]);
                                            exit;
                                        }
                                        
                                        // Fetch results
                                        $result = $stmt->get_result()->fetch_assoc();
                                        
                                        $start_cycle_weight = $result['start_cycle_weight'] ?? 0;
                                        $end_cycle_weight = $result['end_cycle_weight'] ?? 0;
                                        
                                        // Convert to kilograms if necessary (assuming the original weight is in grams)
                                        $start_cycle_weight /= 1000;
                                        $end_cycle_weight /= 1000;
                                        
                                        // Calculate weight gain
                                        $weight_gain = $end_cycle_weight - $start_cycle_weight;
                                        $weight_gain = number_format($weight_gain, 2);

                                        // Calculate total and elapsed duration in seconds
                                        $total_duration = $end_date->getTimestamp() - $start_date->getTimestamp();
                                        $elapsed_duration = $now->getTimestamp() - $start_date->getTimestamp();

                                        // If current date has passed the end date or the start and end dates are the same, set progress to 100%
                                        if ($now >= $end_date) {
                                            $progress_percentage = 100;
                                        } else if ($total_duration > 0) {
                                            // Calculate progress if within cycle duration
                                            $progress_percentage = ($elapsed_duration / $total_duration) * 100;
                                        } else {
                                            // If the start and end dates are the same, set progress to 100%
                                            $progress_percentage = 100;
                                        }

                                        // Clamp progress between 0 and 100%
                                        $progress_percentage = min(max($progress_percentage, 0), 100);

                                        if ($progress_percentage == 100 && $row['status'] != 1) {
                                            $cycle_number = $row['userCycleNumber'];
                                            $update_status_query = "UPDATE user_harvest_cycle SET status = 1 WHERE userCycleNumber = '$cycle_number'";
                                        
                                            if (mysqli_query($conn, $update_status_query)) {
                                                $row['status'] = 1;
                                                
                                                // Define $notif as the cycle number
                                                $notif = $cycle_number;
                                        
                                                // Check if the notification has been sent before (using session to store flag)
                                                if (!isset($_SESSION['cycle_completed_notified']) || $_SESSION['cycle_completed_notified'] != $notif) {
                                                    $notification->insertNotification($adminID, 'active', "Cycle has been completed.", 'complete_cycle', '/harvest', 'unseen');
                                                    $_SESSION['cycle_completed_notified'] = $notif; // Store the cycle number to prevent resending
                                                }
                                            }
                                        }
                                        
                                        // Check if the cycle is 3 days away from completion and the notification hasn't been sent
                                        $three_days_before_end = $end_date->modify('-3 days');  // Get the date 3 days before the end of cycle
                                        
                                        if ($now >= $three_days_before_end && $row['status'] != 1) {
                                            $cycle_number = $row['cycle_number'];
                                            // Define $notif as the cycle number for consistency
                                            $notif = $cycle_number;
                                        
                                            // Check if the 3-day warning notification has been sent before
                                            if (!isset($_SESSION['three_days_warning_notified']) || $_SESSION['three_days_warning_notified'] != $notif) {
                                                $notification->insertNotification($adminID, 'active', "Cycle is 3 days away from completion.", 'three_days_away', '/harvest', 'unseen');
                                                $_SESSION['three_days_warning_notified'] = $notif; // Store the cycle number to prevent resending
                                            }
                                        }
                    
                                        // Set the color and icon based on the status
                                        $progress_color = $row['status'] == 1 ? '#4caf50' : ($progress_percentage >= 100 ? '#4caf50' : '#F9E37F');
                                        $editModalID1 = 'Edit_HarvestModal1_' . $row['userCycleID'];
                                        $deleteModalID1 = 'Delete_HarvestModal1_' . $row['userCycleNumber'];
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['user_name']) ?></td>
                                            <td><?= htmlspecialchars($row['hiveID']) ?></td>
                                            <td><?= htmlspecialchars($row['userCycleNumber']) ?></td>
                                            <td><?= htmlspecialchars($row['user_start_of_cycle']) ?></td>
                                            <td><?= htmlspecialchars($weight_gain) ?> kg</td>
                                            <td><?= htmlspecialchars($row['user_end_of_cycle']) ?></td>
                                            <td>
                                                <div class='status_pending'>
                                                    <div class='progress-circle' style='background: conic-gradient(
                                                    <?= $progress_color ?> <?= $progress_percentage ?>%,
                                                    #f3f3f3 <?= $progress_percentage ?>%
                                                )'></div>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($row['hiveID']) ?></td>
                                            <td>
                                                <?php 
                                                    $isCycleCompleted = $row['status'] == 1;
                                                ?>
                                                <button name='btn_edit' class='btn edit-btn' data-bs-toggle='modal' type='button' data-bs-target='#<?= $editModalID1 ?>' <?= $isCycleCompleted ? 'disabled' : '' ?>>
                                                    <i class='fa-regular fa-pen-to-square'></i>
                                                </button>
                                                <!-- Edit Modal -->
                                                <div class='modal fade' id='<?= $editModalID1 ?>' tabindex='-1' aria-labelledby='Edit_CycleLabel_<?= $editModalID1 ?>' aria-hidden='true'>
                                                    <div class='modal-dialog modal-lg modal-dialog-centered rounded-3'>
                                                        <div class='modal-content' style='border: 2px solid #2B2B2B;'>
                                                            <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                                                <h5 class='modal-title fw-semibold mx-4' id='Edit_CycleLabel_<?= $editModalID1 ?>'>Edit Harvest Cycle</h5>
                                                                <button name='close' type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                            </div>
                                                            <div class='modal-body m-5'>
                                                                <form action='harvestCycle.php' method='post' class='row mt-2 g-3'>
                                                                    <div class='col-md-4'>
                                                                        <label for='cycleNumber_<?= $editModalID1 ?>' class='form-label d-flex justify-content-start' style='font-size: 13px;'>Cycle Number</label>
                                                                        <input name='edit_cycle_num' type='text' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='cycleNumber_<?= $editModalID1 ?>' value='<?= htmlspecialchars($row['userCycleNumber']) ?>' readonly>
                                                                    </div>
                                                                    <div class='col-md-4'>
                                                                        <label for='cycleStart_<?= $editModalID1 ?>' class='form-label d-flex justify-content-start' style='font-size: 13px;'>Start of Cycle</label>
                                                                        <input name='edit_start_date' type='date' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='cycleStart_<?= $editModalID1 ?>' value='<?= htmlspecialchars($row['user_start_of_cycle']) ?>' required min='<?= $currentDate ?>'>
                                                                    </div>
                                                                    <div class='col-md-4'>
                                                                        <label for='cycleEnd_<?= $editModalID1 ?>' class='form-label d-flex justify-content-start' style='font-size: 13px;'>End of Cycle</label>
                                                                        <input name='edit_end_date' type='date' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='cycleEnd_<?= $editModalID1 ?>' value='<?= htmlspecialchars($row['user_end_of_cycle']) ?>' required min='<?= $currentDate ?>'>
                                                                    </div>
                                                                    <div class='mt-4 d-flex justify-content-end'>
                                                                        <input type='hidden' name='userCycleID' value='<?= $row['userCycleID'] ?>'>
                                                                        <button name='btn_edit1' type='submit' class='save-button px-4 border border-1 border-black fw-semibold'>Save</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <button class='btn delete-btn'><i class='fa-regular fa-trash-can' style='color: red;' data-bs-toggle='modal' type='button' data-bs-target='#<?= $deleteModalID1 ?>'></i></button>
                                                <!-- Delete Modal -->
                                                <div class='modal fade' id='<?= $deleteModalID1 ?>' tabindex='-1' aria-labelledby='Delete_CycleLabel_<?= $deleteModalID1 ?>' aria-hidden='true'>
                                                    <div class='modal-dialog modal-lg modal-dialog-centered rounded d-flex justify-content-center'>
                                                        <div class='modal-content' style='border: 2px solid #2B2B2B; width: 450px; height: 180px;'>
                                                            <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                                                <h5 class='modal-title fw-semibold mx-4' id='Delete_CycleLabel_<?= $deleteModalID1 ?>'>Are you sure you want to delete this cycle? </h5>
                                                            </div>
                                                            <div class='modal-body m-2 d-flex justify-content-center'>
                                                                <form action='harvestCycle.php' method='post' class='row mt-2 g-1'>
                                                                    <div class='col-md-4 me-5'>
                                                                        <button name='btn_delete1' type="submit" class="btn-yes px-4 py-2">Yes</button>
                                                                        <input type='hidden' name='userCycleID' value='<?= $row['userCycleID'] ?>'>
                                                                    </div>
                                                                    <div class='col-md-4'>
                                                                        <button type="button" class="btn-no px-4 py-2" data-bs-dismiss='modal' aria-label='Close'>No</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody> -->
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
        </div>
        </div>
        </div>
        </div>
        <div class="space mt-1 d-md-none p-0 m-0"></div>
        <div class="yellow mt-1 d-md-none fixed-bottom p-0 m-0"></div>
    </main>


    <!-- Side Bar Mobile View -->
    <?php require"views/partials/sidebarMobile.php" ?>

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
                        // $db = new Database();
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
                        // $db = new Database();
                        $conn = $db->getConnection();
                        $adminID = $_SESSION['adminID'];
                        $get = "SELECT admin_name, email, number FROM admin_table WHERE adminID = '$adminID'";
                        $getQuery = mysqli_query($conn, $get);

                        while ($row = $getQuery->fetch_assoc()) {
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
                        // $db = new Database();
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

            function fetchDataAgain() {
                location.reload(); // For simplicity, reload the page
            }


            // JavaScript to toggle between tables
            document.getElementById('showTable1').addEventListener('click', function() {
                document.getElementById('table1Container').style.display = 'block';
                document.getElementById('table2Container').style.display = 'none';
            });

            document.getElementById('showTable2').addEventListener('click', function() {
                document.getElementById('table1Container').style.display = 'none';
                document.getElementById('table2Container').style.display = 'block';
            });

            // Initially show only the first table
            window.onload = function() {
                document.getElementById('table1Container').style.display = 'block';  // Only Table 1 is visible on page load
                document.getElementById('table2Container').style.display = 'none';   // Ensure Table 2 is hidden
            };

            /// JavaScript to toggle between tables when buttons are clicked
            document.getElementById('showViewAllTable1').addEventListener('click', function() {
                document.getElementById('viewAllTable1').style.display = 'block';
                document.getElementById('viewAllTable2').style.display = 'none';
            });

            document.getElementById('showViewAllTable2').addEventListener('click', function() {
                document.getElementById('viewAllTable1').style.display = 'none';
                document.getElementById('viewAllTable2').style.display = 'block';
            });

            // Event listener to run when the modal opens
            document.getElementById('viewAllModal').addEventListener('show.bs.modal', function() {
                // Ensure the first table is shown by default
                document.getElementById('viewAllTable1').style.display = 'block';
                document.getElementById('viewAllTable2').style.display = 'none';
            });
            
            window.addEventListener('load', function () {
                setTimeout(function () {
                    document.getElementById('preloader').style.display = 'none';
                }, 500);
            });
            
            const autoToggle = document.getElementById('autoCycleToggle');
            const cycleStart = document.getElementById('cycleStart');
            const cycleEnd = document.getElementById('cycleEnd');
        
            autoToggle.addEventListener('change', async function () {
                if (this.checked) {
                    try {
                        const response = await fetch('getCycleDates.php');
                        const data = await response.json();
                        cycleStart.value = data.start_date;
                        cycleEnd.value = data.end_date;
                        cycleStart.setAttribute('readonly', true);
                        cycleEnd.setAttribute('readonly', true);
                    } catch (error) {
                        console.error('Error fetching auto dates:', error);
                    }
                } else {
                    cycleStart.removeAttribute('readonly');
                    cycleEnd.removeAttribute('readonly');
                    cycleStart.value = '';
                    cycleEnd.value = '';
                }
            });
        </script>
        <script src="./js/notification.js"></script>
        <script src="./js/reusable.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>