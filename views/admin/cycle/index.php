    <!-- head -->
    <?php require base_path('views/partials/head.php') ?>
    <!-- Sidebar -->
    <?php require base_path("views/partials/sidebar.php") ?>

    <!-- Main -->
    <main class="bg-light">
        <div class="p-2">
            <!-- Navbar -->
            <?php require base_path("views/partials/nav.php") ?>

            <!-- Content -->
            <div class="cycle-page py-3 mt-4 border border-2 rounded-4 border-dark">
                <div id="preloader">
                    <div class="container"></div>
                </div>
                <div class="px-4 py-3 my-4 text-center content-wrapper">
                    <p class="fs-4 mb-5 fw-bold cycle-highlight">Harvest Cycle</p>
                    <div class="container-cycle">
                        <!-- FORM TO RECORD HARVEST CYCLE -->
                        <form action="/harvestCycle/create" method="POST" class="row mt-2 g-3">
                            <input type="hidden" name="id" value="<?= $_SESSION['user']['id'] ?>">
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
                                            <button id="showViewAllTable1" class="show-table-one">Admin Cycle</button>
                                            <!-- Button 2 to show Table 2 -->
                                            <button id="showViewAllTable2" class="show-table-two">Worker Cycle</button>
                                        </div>


                                        <div id="viewAllTable1" class="table-responsive mt-2" style="display: none; max-height: 400px; overflow-y: auto;">
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
                                                <tbody id="viewAllTableBodyAdmin">
                                                    <?php foreach ($admin_cycles as $admin_cycle): ?>
                                                        <?php $row = $admin_cycle['cycle']; ?>
                                                        <tr>
                                                            <td><?= $row['cycle_number'] ?? '' ?></td>
                                                            <td><?= $row['start_of_cycle'] ?? '' ?></td>
                                                            <td><?= number_format((float)($admin_cycle['honey'] ?? 0), 2) ?> kg</td>
                                                            <td><?= $row['end_of_cycle'] ?? '' ?></td>
                                                            <td><?= $row['status'] ?? '' ?></td>
                                                            <td><?= $row['hiveID'] ?? '' ?></td>
                                                            <!-- <td>
                                                                <button type="button" class="btn edit-btn" data-id="<?= $row['id'] ?>">Edit</button>
                                                            </td> -->
                                                        </tr>
                                                    <?php endforeach; ?>

                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="viewAllTable2" class="table-responsive mt-2" style="display: none; max-height: 400px; overflow-y: auto;">
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
                                                <tbody id="viewAllTableBodyWorker">
                                                    <?php foreach ($worker_cycles as $worker_cycle) : ?>
                                                        <tr>
                                                            <td><?= $worker_cycle['user_name'] ?></td>
                                                            <td><?= $worker_cycle['hiveID'] ?></td>
                                                            <td><?= $worker_cycle['userCycleNumber'] ?></td>
                                                            <td><?= $worker_cycle['user_start_of_cycle'] ?></td>
                                                            <td><?= $worker_cycle['honey_kg'] ?> kg</td>
                                                            <td><?= $worker_cycle['user_end_of_cycle'] ?></td>
                                                            <td>
                                                                <div class='status_pending'>
                                                                    <?= $worker_cycle['status'] ?>
                                                                </div>
                                                            </td>
                                                            <td><?= $worker_cycle['hiveID'] ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- //TABLE 1 -->
                        <div id="table1Container" class="table-responsive mt-2" style="display: none; max-height: 130px; overflow-y: auto;">
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
                                <tbody id="cycleTableBody">
                                    <?php foreach ($admin_cycles as $admin_cycle) : ?>
                                        <?php $row = $admin_cycle['cycle']; ?>
                                        <tr>
                                            <td><?= $row['cycle_number'] ?? '' ?></td>
                                            <td><?= $row['start_of_cycle'] ?? '' ?></td>
                                            <td><?= number_format((float)($admin_cycle['honey'] ?? 0), 2) ?> kg</td>
                                            <td><?= $row['end_of_cycle'] ?? '' ?></td>
                                            <td><?= $row['status'] ?? '' ?></td>
                                            <td><?= $row['hiveID'] ?? '' ?></td>
                                            <td>
                                                <a href="/harvestCycle/edit?id=<?= $HTTP_RAW_POST_DATA['id'] ?>" name='btn_edit' class='btn edit-btn' type='button'>
                                                    <i class='fa-regular fa-pen-to-square'></i>
                                                </a>
                                            </td>
                                            <td>
                                                <button class='btn delete-btn' data-bs-toggle='modal' data-bs-target='#deleteModal_<?= $row['id'] ?>'>
                                                    <i class='fa-regular fa-trash-can' style='color: red;'></i>
                                                </button>
                                                <!-- Delete Modal -->
                                                <div class='modal fade' id='deleteModal_<?= $row['id'] ?>' tabindex='-1' aria-labelledby='Delete_CycleLabel_<?= $row['id'] ?>' aria-hidden='true'>
                                                    <div class='modal-dialog modal-lg modal-dialog-centered rounded d-flex justify-content-center'>
                                                        <div class='modal-content' style='border: 2px solid #2B2B2B; width: 500px; height: 180px;'>
                                                            <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                                                <h5 class='modal-title mx-5 d-flex justify-content-center' id='Delete_CycleLabel_<?= $row['id'] ?>'>Are you sure you want to delete this cycle?</h5>
                                                            </div>
                                                            <div class='modal-body m-2 d-flex justify-content-center'>
                                                                <form action='/harvestCycle/delete' method='post' class='row mt-2 g-1'>
                                                                    <div class='col-md-4 me-5'>
                                                                        <button name='btn_delete' type="submit" class="btn-yes px-4 py-2">Yes</button>
                                                                        <input type="hidden" name="_method" value="DELETE">
                                                                        <input type='hidden' name='id' value='<?= $row['id'] ?>'>
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
                                                <button class='btn edit-btn' data-bs-toggle='modal' data-bs-target='#completeModal_<?= $admin_cycle['cycle_number'] ?>'>
                                                    <i class='fa-regular fa-circle-stop' style='color: red;'></i>
                                                </button>
                                                <!-- Complete the Cycle Modal -->
                                                <div class='modal fade' id='completeModal_<?= $admin_cycle['cycle_number'] ?>' tabindex='-1' aria-labelledby='Complete_CycleLabel_<?= $admin_cycle['cycle_number'] ?>' aria-hidden='true'>
                                                    <div class='modal-dialog modal-lg modal-dialog-centered rounded d-flex justify-content-center'>
                                                        <div class='modal-content' style='border: 2px solid #2B2B2B; width: 500px; height: 180px;'>
                                                            <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                                                <h5 class='modal-title mx-5 d-flex justify-content-center' id='Complete_CycleLabel_<?= $admin_cycle['cycle_number'] ?>'>Are you sure you want to end this cycle?</h5>
                                                            </div>
                                                            <div class='modal-body m-2 d-flex justify-content-center'>
                                                                <form action='/cycle/end' method='post' class='row mt-2 g-1'>
                                                                    <input type="hidden" name="id" value="<?= $_SESSION['user']['id'] ?>">
                                                                    <div class='col-md-4 me-5'>
                                                                        <button name='btn_end' type="submit" class="btn-yes px-4 py-2">Yes</button>
                                                                        <!-- change to cycle id -->
                                                                        <input type='hidden' name='CycleID' value='<?= $admin_cycle['id'] ?>'>
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
                                </tbody>
                            </table>
                        </div>

                        <!-- //TABLE 2 -->
                        <div id="table2Container" class="table-responsive mt-2" style="display: none; max-height: 130px; overflow-y: auto;">
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
                                <tbody id="cycleTableBody">
                                    <?php foreach ($worker_cycles as $worker_cycle) : ?>
                                        <tr>
                                            <td><?= $worker_cycle['user_name'] ?></td>
                                            <td><?= $worker_cycle['hiveID'] ?></td>
                                            <td><?= $worker_cycle['userCycleNumber'] ?></td>
                                            <td><?= $worker_cycle['user_start_of_cycle'] ?></td>
                                            <td><?= $worker_cycle['honey_kg'] ?> kg</td>
                                            <td><?= $worker_cycle['user_end_of_cycle'] ?></td>
                                            <td>
                                                <div class='status_pending'>
                                                    <?= $worker_cycle['status'] ?>
                                                </div>
                                            </td>
                                            <td><?= $worker_cycle['hiveID'] ?></td>
                                            <td>
                                                <button name='btn_edit' class='btn edit-btn' type='button'>
                                                    <i class='fa-regular fa-pen-to-square'></i>
                                                </button>
                                            </td>
                                            <td>
                                                <button class='btn delete-btn' data-bs-toggle='modal' data-bs-target='#deleteModal_<?= $worker_cycle['userCycleNumber'] ?>'>
                                                    <i class='fa-regular fa-trash-can' style='color: red;'></i>
                                                </button>
                                                <!-- Delete Modal -->
                                                <div class='modal fade' id='deleteModal_<?= $worker_cycle['userCycleNumber'] ?>' tabindex='-1' aria-labelledby='Delete_CycleLabel_<?= $worker_cycle['userCycleNumber'] ?>' aria-hidden='true'>
                                                    <div class='modal-dialog modal-lg modal-dialog-centered rounded d-flex justify-content-center'>
                                                        <div class='modal-content' style='border: 2px solid #2B2B2B; width: 450px; height: 180px;'>
                                                            <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                                                <h5 class='modal-title fw-semibold mx-4' id='Delete_CycleLabel_<?= $worker_cycle['userCycleNumber'] ?>'>Are you sure you want to delete this cycle?</h5>
                                                            </div>
                                                            <div class='modal-body m-2 d-flex justify-content-center'>
                                                                <form action='harvestCycle.php' method='post' class='row mt-2 g-1'>
                                                                    <div class='col-md-4 me-5'>
                                                                        <button name='btn_delete1' type="submit" class="btn-yes px-4 py-2">Yes</button>
                                                                        <input type='hidden' name='userCycleID' value='<?= $worker_cycle['userCycleNumber'] ?>'>
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
        </div>
        </div>
        </div>
        </div>
        <div class="space mt-1 d-md-none p-0 m-0"></div>
        <div class="yellow mt-1 d-md-none fixed-bottom p-0 m-0"></div>
    </main>


    <!-- Side Bar Mobile View -->
    <?php require base_path('views/partials/sidebarMobile.php') ?>

    <script>
        function fetchDataAgain() {
            location.reload(); // For simplicity, reload the page
        }

        document.addEventListener("DOMContentLoaded", () => {
            const table1 = document.getElementById("table1Container");
            const table2 = document.getElementById("table2Container");
            const viewAllTable1 = document.getElementById("viewAllTable1");
            const viewAllTable2 = document.getElementById("viewAllTable2");

            // Default: Show Admin Cycle table only
            table1.style.display = "block";
            table2.style.display = "none";

            viewAllTable1.style.display = "block";
            viewAllTable2.style.display = "none";

            // Button clicks
            document.getElementById("showTable1").addEventListener("click", () => {
                table1.style.display = "block";
                table2.style.display = "none";
            });

            document.getElementById("showTable2").addEventListener("click", () => {
                table1.style.display = "none";
                table2.style.display = "block";
            });

            // Modal buttons
            document.getElementById("showViewAllTable1").addEventListener("click", () => {
                viewAllTable1.style.display = "block";
                viewAllTable2.style.display = "none";
            });

            document.getElementById("showViewAllTable2").addEventListener("click", () => {
                viewAllTable1.style.display = "none";
                viewAllTable2.style.display = "block";
            });

            // Filter logic (if needed - optional enhancement)
            const filterOptions = document.querySelectorAll(".filter-option");
            const filterForm = document.getElementById("filterForm");

            filterOptions.forEach(option => {
                option.addEventListener("click", e => {
                    e.preventDefault();
                    const value = option.dataset.value;
                    filterForm.querySelector('input[name="filter_value"]').value = value;
                    filterForm.submit();
                });
            });
        });

        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('preloader').style.display = 'none';
            }, 500);
        });

        const autoToggle = document.getElementById('autoCycleToggle');
        const cycleStart = document.getElementById('cycleStart');
        const cycleEnd = document.getElementById('cycleEnd');
        const cycleNumber = document.getElementById('cycleNumber');

        autoToggle.addEventListener('change', async function() {
            if (this.checked) {
                try {
                    const response = await fetch('/cycle/getLatest');

                    if (!response.ok) {
                        throw new Error(`Server responded with status: ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.error) {
                        throw new Error(data.error);
                    }

                    // Set date fields
                    cycleStart.value = data.start_date || '';
                    cycleEnd.value = data.end_date || '';

                    // Increment the cycle number by 1 for the new cycle
                    if (data.cycle_number !== null && data.cycle_number !== undefined && data.cycle_number !== '') {
                        // Convert to number, add 1, then back to string
                        cycleNumber.value = (parseInt(data.cycle_number) + 1).toString();
                    } else {
                        // If no previous cycle found, start with cycle 1
                        cycleNumber.value = '1';
                    }

                    if (data.start_date && data.end_date) {
                        // Make all fields readonly when auto-filled
                        cycleStart.setAttribute('readonly', true);
                        cycleEnd.setAttribute('readonly', true);
                        cycleNumber.setAttribute('readonly', true);
                    } else {
                        console.warn('No cycle dates returned from server');
                        this.checked = false;
                    }
                } catch (error) {
                    console.error('Error fetching auto dates:', error);
                    alert('Failed to load cycle data. Please try again or enter data manually.');
                    this.checked = false;
                }
            } else {
                // Remove readonly when auto-toggle is unchecked
                cycleStart.removeAttribute('readonly');
                cycleEnd.removeAttribute('readonly');
                cycleNumber.removeAttribute('readonly');

                // Clear all values
                cycleStart.value = '';
                cycleEnd.value = '';
                cycleNumber.value = '';
            }
        });
    </script>
    <!-- footer -->
    <?php require base_path('views/partials/footer.php') ?>