<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeeMo</title>
    <link rel="stylesheet" href="./css/choose_hive21.css">
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

<body class="overflow-x-hidden ">
    <!-- Sidebar -->
    <?php require"views/partials/sidebar.php" ?>

    <!-- Main -->
    <main class="bg-light">
        <div class="p-2">
            <!-- Navbar -->
            <nav class="px-3 py-3 rounded-4">
                <div>
                    <p class="d-none d-lg-block mt-3 mx-4 fw-bold" style="font-size: 17px;">Welcome to BeeMo</p>
                </div>
                <i class="fa-solid fa-bars sidebar-toggle me-3 d-block d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNav-Menu" aria-controls="offcanvasRight" aria-expanded="false" aria-label="Toggle navigation"></i>
                <h5 class="fw-bold mb-0 me-auto"></h5>
                <div class="dropdown me-3 d-sm-block">
                    <div id="nf-btn" class="navbar-link border border-1 border-black rounded-5" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-bell"></i>
                        <span id="nf-count"></span>
                    </div>
                    <div class="notif-container  dropdown-menu dropdown-menu-start border-dark border-2 rounded-3" style="width: 320px;">
                        <div class="d-flex justify-content-between dropdown-header border-dark border-2">
                            <div>
                                <p class="fs-6 text-dark pt-3">Notifications
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
                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#Profile-Modal">
                                <i class="fa-solid fa-user"></i>
                                Profile
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
                    <p class="choosehive-text fs-4 mb-5 fw-bold choosehive-highlight">Choose Hive</p>
                    <div class="col-lg-6 mx-auto" style="max-height: 400px; overflow-y:auto;">

                        <!-- Container for dynamically generated hive buttons -->
                        <div id="hive-button-container" class="mt-5 gap-2 d-block justify-content-sm-center">
                            <!-- Hive buttons will be inserted here dynamically -->
                        </div>
                        <div class="space2 mt-1 p-0 m-0"></div>
                    </div>
                    
                   <div class="col-lg-6 mx-auto mt-3 d-flex justify-content-end justify-content-md-center justify-content-lg-end pe-3 gap-lg-3 gap-2">
                         <button id="add-hive-button" class="edit-button mt-5 px-4 border border-1 border-black fw-semibold" type="button" data-bs-toggle="modal" data-bs-target="#addHiveModal">
                            Add Hive
                        </button>
                        <button id="delete-hive-button" class="edit-button mt-5 px-4 border border-1 border-black fw-semibold" type="button" data-bs-toggle="modal" data-bs-target="#deleteHiveModal">
                            Delete Hive
                        </button>
                    </div>
                    <!-- Modal for Adding a New Hive -->
                    <div class="modal fade" id="addHiveModal" tabindex="-1" aria-labelledby="addHiveModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Form to add a new hive -->
                                <form id="add-hive-form">
                                    <div class="modal-header" style="background-color: #FCF4B9;">
                                        <h5 class="modal-title" id="addHiveModalLabel">Add New Hive</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Hive ID (auto-generated and readonly) -->
                                        <div class="mb-3">
                                            <label for="hiveID" class="form-label">Hive ID</label>
                                            <input type="text" class="form-control" id="hiveID" name="hiveID" readonly>
                                        </div>
                                        <!-- Hive Number (auto-generated and readonly) -->
                                        <div class="mb-3">
                                            <label for="hiveNum" class="form-label">Hive Number</label>
                                            <input type="text" class="form-control" id="hiveNum" name="hiveNum" readonly>
                                        </div>
                                        <!-- Admin ID (hidden field from session) -->
                                        <input type="hidden" id="adminID" name="adminID" value="<?php echo $_SESSION['adminID'] ?? ''; ?>">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="add-hive-btn">Add Hive</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal fade" id="deleteHiveModal" tabindex="-1" aria-labelledby="deleteHiveModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Form to delete a hive -->
                                <form action="chooseHive.php" method="post" id="delete-hive-form">
                                    <div class="modal-header" style="background-color: #FCF4B9;">
                                        <h5 class="modal-title" id="deleteHiveModalLabel">Delete Hive</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="deleteHive" class="form-label">Input Hive Number to Delete</label>
                                            <input type="number" class="form-control" id="deleteHive" name="deleteHiveNum" required>
                                        </div>
                                        <input type="hidden" id="adminID1" name="adminID" value="<?php echo $_SESSION['adminID'] ?? ''; ?>">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="delete-btn">Delete Hive</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space mt-1 d-md-none p-0 m-0"></div>
            <div class="yellow mt-1 d-md-none fixed-bottom p-0 m-0"></div>
        </div>
    </main>

    <!-- Side Bar Mobile View -->

    <?php require"views/partials/sidebarMobile.php" ?>

    <script src="./js/manage_hive2.js"></script>
    <script src="./js/notification9.js"></script>
    <script src="./js/reusable.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>