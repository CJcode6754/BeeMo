    <!-- head -->
    <?php require 'views/partials/head.php' ?>
    <!-- Sidebar -->
    <?php require "views/partials/sidebar.php" ?>

    <!-- Main -->
    <main class="bg-light">
        <div class="p-2">
            <!-- Navbar -->
            <?php require"views/partials/nav.php" ?>
            
            <!-- Content -->
            <div class="worker-page py-3 mt-4 border border-2 rounded-4 border-dark">
                <div class="px-4 py-4 my-4 text-center content-wrapper">
                    <p class="fs-4 mb-5 fw-bold worker-highlight">Workers</p>
                    <div class="container-worker">
                        <div class="table-responsive mt-5" style="max-height: 165px; overflow-y: auto;">
                            <table class="table worker-table border-dark" name="worker_list">
                                <thead>
                                    <tr>
                                        <th style="background-color: #FAEF9B;">Full Name</th>
                                        <th style="background-color: #FAEF9B;">Email</th>
                                        <th style="background-color: #FAEF9B">Contact Number</th>
                                        <th style="background-color: #FAEF9B;">Password</th>
                                        <th style="background-color: #FAEF9B;">Edit</th>
                                        <th style="background-color: #FAEF9B;">Remove</th>
                                    </tr>
                                </thead>
                                <tbody id="workerTableBody">
                                    <tr>
                                        <td>" . $row['user_name'] . "</td>
                                        <td>" . $row['email'] . "</td>
                                        <td>" . $row['number'] . "</td>
                                        <td>" . $row['password'] . " </td>
                                        <td>
                                            <button name='btn_edit' class='btn edit-btn' data-bs-toggle='modal' type='button' data-bs-target='#$editModalID'>
                                                <i class='fa-regular fa-pen-to-square'></i>
                                            </button>
                                            <div class='yellow mt-1 d-md-none fixed-bottom p-0 m-0'></div>
                                            <div class='modal fade' id='$editModalID' tabindex='-1' aria-labelledby='Edit_WorkerLabel' aria-hidden='true'>
                                                <div class='modal-dialog modal-lg modal-dialog-centered rounded-3'>
                                                    <div class='modal-content' style='border: 2px solid #2B2B2B;'>
                                                        <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                                            <h5 class='modal-title fw-semibold mx-4' id='Edit_WorkerLabel'>Edit Worker</h5>
                                                            <button name='closeBtn' type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                        </div>
                                                        <div class='modal-body m-5'>
                                                            <form action='addWorker.php' method='post' novalidate>
                                                                <div class='d-grid d-sm-flex justify-content-sm-center gap-4 mb-1'>
                                                                    <div class='col-md-6'>
                                                                        <label for='FullName' class='form-label' style='font-size: 13px;'>Full Name</label>
                                                                        <input name='edit_user_name' type='text' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='Edit_FullName_$editModalID' value='" . htmlspecialchars($row[' user_name'], ENT_QUOTES) . "' required>
                                                                        <div class='invalid-feedback'>Please enter your full name.</div>
                                                                    </div>
                                                                    <div class='mb-3 col-md-6'>
                                                                        <label for='Email' class='form-label' style='font-size: 13px;'>Email</label>
                                                                        <input name='edit_email' type='email' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='Edit_Email_$editModalID' value='" . htmlspecialchars($row['email'], ENT_QUOTES) . "' required>
                                                                        <div class='invalid-feedback'>Please enter a valid email address.</div>
                                                                    </div>
                                                                </div>
                                                                <div class='d-grid mt-3 d-sm-flex justify-content-sm-center gap-4'>
                                                                    <div class='col-md-6'>
                                                                        <label for='PhoneNumber' class='form-label' style='font-size: 13px;'>Phone Number</label>
                                                                        <input name='edit_number' type='number' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='Edit_PhoneNumber_$editModalID' value='" . htmlspecialchars($row['number'], ENT_QUOTES) . "' required>
                                                                        <div class='invalid-feedback'>Please enter a valid mobile number.</div>
                                                                    </div>
                                                                    <div class='col-md-6 mb-2'>
                                                                        <label for='Password' class='form-label' style='font-size: 13px;'>Password</label>
                                                                        <input name='edit_password' type='password' class='form-control rounded-3 py-2' style='border: 1.8px solid #2B2B2B; font-size: 13px;' id='Edit_Password_$editModalID' value='" . htmlspecialchars($row['password'], ENT_QUOTES) . "' required>
                                                                        <div class='invalid-feedback' id = 'passwordErrorMessage'></div>
                                                                    </div>
                                                                </div>
                                                                <div class='mt-5 d-flex justify-content-center'>
                                                                    <input type='hidden' name='userID' value='" . $row['userID'] . "'>
                                                                    <button id='Edit_btn_$editModalID' name='edit_btn' type='submit' class='save-button px-4 border border-1 border-black fw-semibold'><span class='fw-bold'>+</span> Edit Info</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </td>
                                        <td>
                                             <button class='btn delete-btn'><i class='fa-regular fa-trash-can' style='color: red;' data-bs-toggle='modal' type='button' data-bs-target='#$deleteModalID'></i></button>
                                                 <!-- Edit Modal -->
                                                 <div class='modal fade' id='$deleteModalID' tabindex='-1' aria-labelledby='Delete_WorkerModal' aria-hidden='true'>
                                                    <div class='modal-dialog modal-lg modal-dialog-centered rounded d-flex justify-content-center'>
                                                        <div class='modal-content' style='border: 2px solid #2B2B2B; width: 450px; height: 180px;'>
                                                            <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                                                <h5 class='modal-title fw-semibold mx-4' id='Delete_WorkerModal_'>Are you sure you want to delete this cycle? </h5>
                                                            </div>
                                                            <div class='modal-body m-2 d-flex justify-content-center'>
                                                                <form action='addWorker.php' method='post' class='row mt-2 g-1'>
                                                                    <div class='col-md-4 me-5'>
                                                                        <button type='button' class='btn btn-dark' data-bs-dismiss='modal' aria-label='Close'>No</button>
                                                                    </div>
                                                                    <div class='col-md-4'>
                                                                        <button name='btn_delete' type='submit' class='btn btn-success' >Yes</button>
                                                                        <input type='hidden' name='userID' value='" . $row['userID'] . "'>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class=" mt-5 d-flex justify-content-end pe-3">
                                                                        <button class="add-button px-4 border border-1 border-black  fw-semibold" type="button" data-bs-toggle="modal" data-bs-target="#addWorkerModal">
                                                                            <span class="fw-bold">+ </span> Add Worker
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="yellow mt-1 d-md-none fixed-bottom p-0 m-0"></div>
                                                <div class="modal fade" id="addWorkerModal" tabindex="-1" aria-labelledby="addWorkerModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered rounded-3">
                                                        <div class="modal-content" style="border: 2px solid #2B2B2B;">
                                                            <div class="modal-header border-dark border-2" style="background-color: #FCF4B9;">
                                                                <h5 class="modal-title fw-semibold mx-4" id="addWorkerModalLabel">Add Worker</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body m-5">
                                                                <form action="addWorker.php" method="post" id="workerForm" novalidate>
                                                                    <div class="d-grid d-sm-flex justify-content-sm-center gap-4 mb-1">
                                                                        <!-- Full Name -->
                                                                        <div class="col-md-6">
                                                                            <label for="FullName" class="form-label" style="font-size: 13px;">
                                                                                Full Name
                                                                            </label>
                                                                            <input
                                                                                name="user_name"
                                                                                type="text"
                                                                                class="form-control rounded-3 py-2"
                                                                                style="border: 1.8px solid #2B2B2B; font-size: 13px;"
                                                                                id="FullName"
                                                                                placeholder="Enter full name"
                                                                                required>
                                                                            <div class="invalid-feedback">Only alphabet characters are allowed.</div>
                                                                        </div>

                                                                        <!-- Email -->
                                                                        <div class="mb-3 col-md-6">
                                                                            <label for="Email" class="form-label" style="font-size: 13px;">
                                                                                Email
                                                                            </label>
                                                                            <input
                                                                                name="email"
                                                                                type="email"
                                                                                class="form-control rounded-3 py-2"
                                                                                style="border: 1.8px solid #2B2B2B; font-size: 13px;"
                                                                                id="Email"
                                                                                placeholder="Enter email address"
                                                                                required>
                                                                            <div class="invalid-feedback">Please enter a valid email address.</div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="d-grid mt-3 d-sm-flex justify-content-sm-center gap-4">
                                                                        <!-- Phone Number -->
                                                                        <div class="col-md-6">
                                                                            <label for="PhoneNumber" class="form-label" style="font-size: 13px;">
                                                                                Phone Number
                                                                            </label>
                                                                            <input
                                                                                name="number"
                                                                                type="text"
                                                                                class="form-control rounded-3 py-2"
                                                                                style="border: 1.8px solid #2B2B2B; font-size: 13px;"
                                                                                id="PhoneNumber"
                                                                                placeholder="Enter phone number"
                                                                                required>
                                                                            <div class="invalid-feedback">Please enter a valid mobile number starting with 09.</div>
                                                                        </div>

                                                                        <!-- Password -->
                                                                        <div class="col-md-6 mb-2">
                                                                            <label for="Password" class="form-label" style="font-size: 13px;">
                                                                                Password
                                                                            </label>
                                                                            <div class="input-group">
                                                                                <input
                                                                                    name="password"
                                                                                    type="password"
                                                                                    class="form-control rounded-3 py-2"
                                                                                    style="border: 1.8px solid #2B2B2B; font-size: 13px;"
                                                                                    id="Password"
                                                                                    placeholder="Enter password"
                                                                                    required>
                                                                                <div class="invalid-feedback">Password: 8-32 chars, uppercase, lowercase, numbers.</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Submit Button -->
                                                                    <div class="mt-5 d-flex justify-content-center">
                                                                        <button
                                                                            id="btn"
                                                                            name="submit"
                                                                            type="submit"
                                                                            class="save-button px-4 border border-1 border-black fw-semibold">
                                                                            <span class="fw-bold">+</span> Add Worker
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

    </main>

    <!-- Side Bar Mobile View -->

    <?php require "views/partials/sidebarMobile.php" ?>

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

    <div id="notification" class="notification"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notification = document.getElementById('notification');

            // Show notification function
            function showNotification(message) {
                notification.textContent = message;
                notification.classList.add('show');
                setTimeout(function() {
                    notification.classList.remove('show');
                }, 6000);
            }
        });
    </script>
    <script src="./js/addWorker8.js"></script>
<!-- footer -->
<?php require 'views/partials/footer.php' ?>