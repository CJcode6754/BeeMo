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
        <div class="reports-page py-3 mt-4 border border-2 rounded-4 border-dark">
            <div id="preloader">
                <div class="container"></div>
            </div>
            <div class="container-cont">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="container1 p-1">
                            <div class="px-4 py-2 text-center content-wrapper">
                                <div class="container-top">
                                    <div class="date-parameter-container d-flex justify-content-center mb-3 mt-2">
                                        <div class="label-container btn-group d-flex justify-content-center">
                                            <a href="#/temperature" class="btn btn-label label-current" data-type="temperature">Temperature</a>
                                            <a href="#/humidity" class="btn btn-label label-not" data-type="humidity">Humidity</a>
                                            <a href="#/weight" class="btn btn-label label-not" data-type="weight">Weight</a>
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
                                    <p class="mx-5 mx-md-4" id="previousWeightContainer" style="font-size: 12px;">Previous: <span class="fw-bold" id="previousWeight" style="font-size: 16px;">-</span></p>
                                    <p class=" mx-5 mx-md-4" id="avgContainer" style="font-size: 12px;">Average: <span class="fw-bold" id="average-value" style="font-size: 16px;">-</span></p>
                                </div>
                                <div class="col">
                                    <p class="" style="font-size: 12px;">Maximum: <span class="fw-bold" id="max-value" style="font-size: 16px;">-</span></p>
                                </div>
                                <div class="col">
                                    <p class="mx-5 mx-md-4" style="font-size: 12px;" id="weightGainContainer">Weight Gain: <span class="fw-bold" id="weightGain" style="font-size: 16px;">-</span></p>
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
<?php require base_path("views/partials/sidebarMobile.php") ?>
<script>
    window.addEventListener('load', function() {
        setTimeout(function() {
            document.getElementById('preloader').style.display = 'none';
        }, 4200);
    });
</script>
<script src="/js/reports.js"></script>

<!-- footer -->
<?php require base_path('views/partials/footer.php') ?>