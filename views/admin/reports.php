<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeeMo</title>
    <link rel="stylesheet" href="./css/reports29.css">
    <link rel="stylesheet" href="./css/reusable1.css">
    <link rel="stylesheet" href="./css/profile.css">
    <link rel="icon" href="img/beemo-ico.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b4ce5ff90a.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@latest"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
<!-- <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }
#preloader {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #FFFFFF;
    display: flex;
    align-items: center; /* Centers vertically within cycle-page */
    justify-content: center;
    z-index: 9999;
    width: 100%;
}

.container {
    --uib-size: 100%; /* Sets full width */
    --uib-color: #F9E37F;
    --uib-speed: 1.4s;
    --uib-stroke: 5px;
    --uib-bg-opacity: .1;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    height: var(--uib-stroke);
    width: 10%; /* Adjusts width for better fit within cycle-page */
    border-radius: calc(var(--uib-stroke) / 2);
    overflow: hidden;
    transform: translate3d(0, 0, 0);
}

.container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background-color: var(--uib-color);
    opacity: var(--uib-bg-opacity);
    transition: background-color 0.3s ease;
}

.container::after {
    content: '';
    height: 100%;
    width: 100%;
    border-radius: calc(var(--uib-stroke) / 2);
    animation: zoom var(--uib-speed) ease-in-out infinite;
    transform: translateX(-100%);
    background-color: var(--uib-color);
    transition: background-color 0.3s ease;
}

@keyframes zoom {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(100%);
    }
}

input[type="date"]::-webkit-inner-spin-button,
input[type="date"]::-webkit-calendar-picker-indicator {
    display: none;
    -webkit-appearance: none;
}
</style> -->
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
            <div class="reports-page py-3 mt-4 border border-2 rounded-4 border-dark">
                <div id="preloader">
                    <div class="container"></div>
                </div>
                <div class="container-cont">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="container1 p-1">
                                <div class="px-4 py-2 text-center content-wrapper">
                                    <!-- <p class="reports-text fs-4 mb-3 fw-bold reports-highlight">Reports</p> -->
                                    <div class="container-top">
                                        <div class="date-parameter-container d-flex justify-content-between mb-3 mt-2">
                                            <div class="label-container btn-group d-flex justify-content-center">
                                                <a href="#/temperature" class="btn btn-label label-current" data-type="temperature">Temperature</a>
                                                <a href="#/humidity" class="btn btn-label label-not" data-type="humidity">Humidity</a>
                                                <a href="#/weight" class="btn btn-label label-not" data-type="weight">Weight</a>
                                            </div>
                                            <div class="date-pick-size">
                                                <div class="input-group">
                                                    <input id="start-date-picker" type="date" class="form-control" placeholder="Select Date">
                                                    <span class="input-group-text" id="calendar-icon">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Filter Container -->
                                        <div class="container-label d-flex flex-column flex-md-row justify-content-between align-items-center w-100 gap-0 gap-md-2 my-2">
                                            <!-- Cycle Container (Admin & Worker Cycle) -->
                                            <div class="d-flex justify-content-start">
                                                <div class="cycle-container d-flex justify-content-center w-100 mb-2 mb-md-0">
                                                    <div class="dropdown">
                                                        <button class="select-harvest-btn dropdown-toggle" type="button" id="harvestCycleDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Admin Cycle
                                                        </button>
                                                        <ul class="dropdown-menu" id="harvestCycleList">
                                                            <!-- Options will be populated via JavaScript -->
                                                        </ul>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="select-harvest-btn dropdown-toggle" type="button" id="userHarvestCycleDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Worker Cycle
                                                        </button>
                                                        <ul class="dropdown-menu" id="userHarvestCycleList">
                                                            <!-- Options will be populated via JavaScript -->
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Filter Container (Admin & Worker Filter) -->
                                            <div class="d-flex justify-content-end">
                                                <div class="filter-container d-flex justify-content-center w-100">
                                                    <div class="dropdown">
                                                        <button class="filter-btn dropdown-toggle" type="button" id="monthlyFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Admin Filter
                                                        </button>
                                                        <ul class="dropdown-menu" id="monthDropdown" aria-labelledby="monthlyFilter">
                                                            <!-- Options will be populated via JavaScript -->
                                                        </ul>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="filter-btn dropdown-toggle" type="button" id="userMonthlyFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Worker Filter
                                                        </button>
                                                        <ul class="dropdown-menu" id="userMonthDropdown" aria-labelledby="monthlyFilter">
                                                            <!-- Options will be populated via JavaScript -->
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="container-chart mt-3">
                                            <div class="chart-container">
                                                <canvas id="myChart"></canvas>
                                            </div>
                                            <div class="legends d-flex justify-content-center gap-2 gap-md-4 mt-3">
                                                <span id="rangeContainer" class="badge" style="background-color: rgba(0, 255, 0, 0.2); color: #2B2B2B;">Optimal Range</span>
                                                <span id="rangeContainer1" class="badge" style="background-color: rgba(255, 127, 127, 0.4); color: #2B2B2B;">Out of Optimal Range</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row gap-3 gap-md-0">
                        <div class="col-12 col-md-5">
                            <div class="container2 pt-2 pt-md-3">
                                  <div class="descriptive-analytics-container d-flex justify-content-center row row-cols-2 g-1 p-4 p-md-3">
                                    <div class="col">
                                        <p class=" mx-5 mx-md-4" style="font-size: 12px;">Type: <span class="fw-bold" id="date-range-label" style="font-size: 16px;">-</span></p>
                                    </div>
                                    <div class="col">
                                        <p class="" style="font-size: 12px;">Minimum: <span class="fw-bold" id="min-value" style="font-size: 16px;">-</span></p>
                                    </div>
                                    <div class="col">
                                        <p class="mx-5 mx-md-4" id="previousWeightContainer" style="font-size: 12px;">Previous: <span class="fw-bold"  id="previousWeight" style="font-size: 16px;">-</span></p>
                                        <p class=" mx-5 mx-md-4" id="avgContainer" style="font-size: 12px;">Average: <span class="fw-bold"  id="average-value" style="font-size: 16px;">-</span></p>
                                    </div>
                                    <div class="col">
                                        <p class="" style="font-size: 12px;">Maximum: <span class="fw-bold" id="max-value" style="font-size: 16px;">-</span></p>
                                    </div>
                                    <div class="col">
                                        <p class="mx-5 mx-md-4" style="font-size: 12px;" id="weightGainContainer">Weight Gain: <span class="fw-bold"  id="weightGain" style="font-size: 16px;">-</span></p>
                                    </div>
                                    <div class="col">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-7">
                            <div class="container3 p-3">
                                <p class="fs-5 fw-bold reports-highlight">INTERPRETATION:</p>
                                <p id="insights-container" class="mt-1"></p>
                                <p id="fullcycle_insights" class="mt-1"></p>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="space2 mt-1 p-0 m-0"></div>
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
        window.addEventListener('load', function () {
            setTimeout(function () {
                document.getElementById('preloader').style.display = 'none';
            }, 4200);
        });
    </script>
    <script src="/js/notification9.js"></script>
    <script src="./js/reusable.js"></script>
    <script src="/js/reports57.js"></script>
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