<!-- head -->
<?php require 'views/partials/head.php' ?>
    <!-- Sidebar -->
    <?php require"views/partials/sidebar.php" ?>

    <!-- Main -->
    <main class="bg-light">
        <div class="p-2">
            <!-- Navbar -->
            <?php require"views/partials/nav.php" ?>
            
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

    <script src="/js/manage_hive.js"></script>
<!-- footer -->
<?php require 'views/partials/footer.php' ?>