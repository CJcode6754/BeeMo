<!-- head -->
<?php require 'views/partials/head.php' ?>
  <!-- Sidebar -->
    <?php require"views/partials/sidebar.php" ?>

    <!-- Main -->
    <main class="bg-light">
        <div class="p-2">
            <!-- Navbar -->
            <?php require"views/partials/nav.php" ?>

            <div class="monitoring-page py-4 mt-4 border border-2 rounded-4 border-dark">
                <div class="px-4 py-2 my-4 text-center content-wrapper">
                    <p class="monitoring-text fs-4 mb-5 fw-bold monitoring-highlight">
                        <?php echo "Hive " . htmlspecialchars($_SESSION['hiveID'] ?? 'Not Set'); ?>
                    </p>
                    <div class="d-flex justify-content-end mt-2 mb-4">
                        
                    </div>
                    <div class="column-container row g-3 mt-4 two-column">
                        <div class="col-md-4">
                            <div class="container1">
                                <div class="d-flex justify-content-between m-md-4 m-3">
                                    <div class="d-block">
                                        <p class="temp fw-bold">Temperature</p>
                                        <p class ="temp-based">Based: 32-35 °C</p>
                                        <p class="temp-degree" style="color: black;">N/A °C</p> <!-- Default text -->
                                        <p class="temp-interpretation" style="color: green;">Normal</p>
                                    </div>
                                    <i class="param-icon fa-solid fa-temperature-arrow-up align-content-center" ></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="container2">
                                <div class="d-flex justify-content-between m-md-4 m-3">
                                    <div class="d-block">
                                        <p class="humid">Humidity</p>
                                        <p class="humid-based">Based: 50-60%</p>
                                        <p class="humid-percent" style="color: black;">N/A %</p> <!-- Default text -->
                                        <p class="humid-interpretation" style="color: green;">Normal</p>
                                    </div>
                                    <i class="param-icon fa-solid fa-droplet align-content-center"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="container3">
                                <div class="d-flex justify-content-between m-md-4 m-3">
                                    <div class="d-block weight-block">
                                        <p class="weight">Weight</p>
                                        <p class="initial-weight">Based: 2 kg</p>
                                        <p class="weight-value">N/A g</p> <!-- Default text -->
                                    </div>
                                    <i class="param-icon fa-solid fa-box-archive align-content-center"></i>
                                </div>
                            </div>
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
    <script src="/js/fetchdata.js"></script>
    <!-- footer -->
    <?php require 'views/partials/footer.php' ?>