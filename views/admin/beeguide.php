<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeeMo</title>
    <link rel="stylesheet" href="./css/webTutorial.css">
    <link rel="stylesheet" href="./css/hardwareTutorial.css">
    <link rel="stylesheet" href="./css/reusable1.css">
    <link rel="stylesheet" href="./css/profile.css">
    <link rel="icon" href="img/beemo-ico.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b4ce5ff90a.js" crossorigin="anonymous"></script>
    
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }
    </style>
</head>

<body class="overflow-x-hidden">
  <!-- Sidebar -->
    <?php require"views/partials/sidebar.php" ?>

    <!-- Main -->
    <main class="bg-light">
        <div class="p-2">
            <!-- Navbar -->
            <nav class="px-3 py-3 rounded-4">
                <div>
                    <p class="d-none d-lg-block mt-3 mx-4 fw-bold" style="font-size: 17px;">Welcome to Bee Guide</p>
                </div>
                <i class="fa-solid fa-bars sidebar-toggle me-3 d-block d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNav-Menu" aria-controls="offcanvasRight" aria-expanded="false" aria-label="Toggle navigation"></i>
                <h5 class="fw-bold mb-0 me-auto"></h5>
                <div class="dropdown me-3 d-sm-block">
                    <div id="nf-btn" class="navbar-link border border-1 border-black rounded-5" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-bell"></i>
                        <span id="nf-count"></span>
                    </div>
                    <div class="notif-container dropdown-menu dropdown-menu-start border-dark border-2 rounded-3" style="width: 320px;">
                        <div class="d-flex justify-content-between dropdown-header border-dark border-2">
                            <div>
                                <p class="fs-6 text-dark pt-3">Notifications
                                    <span class="badge text-dark bg-warning-subtle rounded-pill" id="nf-count-badge">0</span>
                                </p>
                            </div>
                            <div>
                                <form action="/beeGuide" method="post">
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
                        <!-- Logout -->
                        <form id="logoutForm" action="/beeGuide" method="post" style="display: none;">
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
            <div class="beeguide-page mt-4 border border-2 rounded-4 border-dark">
                <div class="text-center content-wrapper scrollable-content container" style="max-height: 580px; overflow-y: auto; scroll-behavior: smooth;">
                    
                    <!-- Scrollable Wrapper -->
                    <p class="beeguideWeb-text fs-4 beeguide-highlight text-centers" style="color: #292929;">Bee Guide</p>

                    <div class="row justify-content-center px-5">
                        <!-- First Image (Left) -->
                        <div class="col-xl-7 col-lg-12 mb-2 d-flex justify-content-center">
                            <img src="img/SystemArch1.png" class="system-img" alt="Left Image">
                        </div>

                        <!-- Right Column -->
                        <div class="right-text col-xl-4 col-lg-12 mt-3">
                        <strong><h3 class="text-center py-3">How does the system work?</h3></strong>
                        <p class="right-p">&nbsp; &nbsp; The BeeMo system operates as an integrated solution for monitoring and managing 
                            the environment within artificial beehives. Utilizing an array of sensors—including the DHT22 for temperature 
                            and humidity, and HX711 for weight measurement—the system continuously gathers real-time data on critical 
                            parameters affecting bee health and productivity. This data is transmitted via the NodeMCU ESP8266 to a central 
                            processing unit, where it is analyzed to detect any deviations from optimal conditions. In response to this 
                            analysis, regulation components such as the TEC-12706 thermoelectric cooler, PTC heater, and DC fan are activated 
                            to adjust the hive's microclimate. Powered by a reliable power supply, the system ensures uninterrupted operation 
                            and data monitoring. By maintaining a stable environment, BeeMo promotes bee well-being, enhancing honey production 
                            and overall farm efficiency.</p>
                        </div>
                        
                        <!-- Lower Column -->
                        
                        <div class="lower-text mt-3 pt-3 d-flex flex-column justify-content-center mb-4">
                            <p class="lower-p text-container">
                            <strong>"BeeMo: An IoT-Enabled Web-Based Stingless Beehive Management System with Real-Time Temperature, Humidity, 
                            Weight Monitoring"</strong> aims to modernize and improve stingless bee farming. By introducing an innovative Internet of 
                            Things (IoT) monitoring system, BeeMo is designed to transform traditional methods into advanced, efficient practices. 
                            This system is tailored to monitor and regulate critical parameters within artificial beehives, such as temperature, 
                            humidity, and weight, thereby optimizing bee health and honey production.
                            <br>
                            <br>
                            Note: If there seems to be a problem in the system, please do check if all the wires are connected, and if still did not work,
                            contact the maintenance worker.
                            </p>
                        </div>
                        
                    </div>

                    <p class="beeguideWeb-text fs-4 beeguide-highlight text-centers" style="color: #292929;">Web-System Tutorial</p>

                    <div>
                        <!-- Parameters Monitoring -->
                        <div class="d-flex justify-content-center">
                            <img src="img/Web Img/Param.png" id="ParamImage" class="param-img pt-3" alt="Parameters Monitoring Image">
                        </div>

                        <div class="lower-text mt-3 d-flex flex-column justify-content-center mb-4">
                            <p class="lower-p text-container">
                            <strong>Parameters Monitoring</strong> allows users to monitor the temperature, humidity, and weight of the beehive. 
                            The users can manually calibrate these parameters by disabling the automatic regulation feature and 
                            using a slider to set specific temperature or humidity values. Additionally, if any parameter falls 
                            outside its recommended threshold, a notification will be triggered and can be viewed via the 
                            notification icon on the website.
                            </p>
                        </div>
                        
                        <hr class="custom-hr pt-1">

                        <!-- Reports -->
                        <div class="text-center pt-2 pb-3">
                            <h4 style="color: #292929; font-weight: 400;">&nbsp;Reports&nbsp;</h4>
                        </div>

                        <div class="d-flex justify-content-center">
                            <img src="img/Web Img/Reports.png" id="reportsImage" class="reports-img pt-4" alt="Reports Image">
                        </div>

                        <div class="lower-text mt-3 d-flex flex-column justify-content-center mb-4">
                            <p class="lower-p text-container">
                            The <strong>Reports</strong> feature enables users to monitor historical parameter records, including date and time, with descriptive analytics to summarize and interpret past production data. It includes a line graph displaying temperature, humidity, and hive weight, with color indicators—red for values outside the optimal range and green for optimal values—to assist with interpretation. Dropdown menus allow filtering reports by Harvest Cycle, categorized into Admin Cycle and Worker Cycle, tailored to administrator or worker roles. Further refinement is possible with monthly and daily filters (Admin Filter or Worker Filter) to view data for specific periods within the selected cycle.
                            </p>
                        </div>

                        <hr class="custom-hr pt-1">

                        <!-- Harvest Cycle -->
                        <div class="text-center pt-2 pb-3">
                            <h4 style="color: #292929; font-weight: 400;">&nbsp;Harvest Cycle&nbsp;</h4>
                        </div>

                        <div class="d-flex justify-content-center">
                            <img src="img/Web Img/HarvestCycleTable.png" id="HarvestCycleImage" class="harvest-img pt-4" alt="Workers Image">
                        </div>

                        <div class="lower-text mt-3 d-flex flex-column justify-content-center mb-4">
                            <p class="lower-p text-container">
                            The <strong>Harvest Cycle</strong> feature records honey harvested per kilogram , allowing users to edit or delete entries if needed. The Reports feature connects to the Harvest Cycle feature, using cycle data to generate relevant reports for tracking hive data and honey production. It includes Admin Cycle and Worker Cycle buttons for viewing cycles created by administrators or workers. A filter dropdown streamlines management by categorizing cycles into All, Pending, or Completed. Icons indicate cycle status (done or pending), and the system sends web and SMS notifications near the harvest cycle's end, ensuring timely hive management and preparation.
                            </p>
                        </div>


                        <hr class="custom-hr pt-1">

                        <!-- Workers -->
                        <div class="text-center pt-2 pb-3">
                            <h4 style="color: #292929; font-weight: 400;">&nbsp;Workers&nbsp;</h4>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            <img src="img/Web Img/Workers.png" id="workersImage" class="workers-img pt-4" alt="Workers Image">
                        </div>

                        <div class="lower-text mt-3 d-flex flex-column justify-content-center mb-4">
                            <p class="lower-p text-container">
                            The <strong>Worker</strong> option is exclusively accessible by the administrator for managing user accounts. 
                            Within this section, the administrator can add or remove worker accounts and assign permissions. 
                            Additionally, the admin can edit worker credentials by clicking the edit button, either modifying 
                            all information or specific details for a particular worker. Workers assigned by the administrator 
                            can log in to the web system using the provided credentials.
                            </p>
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
                        // $db = new Database();
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
                        // $db = new Database();
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
    </div>
    
    <script src="./js/WebTutorial5.js"></script>
    <script src="./js/reusable.js"></script>
    <script src="./js/notification8.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>