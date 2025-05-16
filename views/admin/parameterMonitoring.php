<!-- head -->
<?php require base_path('views/partials/head.php') ?>
<!-- Sidebar -->
<?php require base_path("views/partials/sidebar.php") ?>

<!-- Main -->
<main class="bg-light">
    <div class="p-2">
        <!-- Navbar -->
        <?php require base_path("views/partials/nav.php") ?>

        <div class="monitoring-page py-4 mt-4 border border-2 rounded-4 border-dark">
            <div class="px-4 py-2 my-4 text-center content-wrapper">
                <p class="monitoring-text fs-4 mb-5 fw-bold monitoring-highlight">
                    <?= "Hive " . htmlspecialchars($_SESSION['hiveID'] ?? 'Not Set'); ?>
                </p>
                <div class="column-container row g-3 mt-4 two-column">
                    <div class="col-md-4">
                        <div class="container5">
                            <div class="d-flex justify-content-between m-md-4 m-3">
                                <div class="d-block">
                                    <p class="temp fw-bold">🌡️ Temperature</p>
                                    <p class="temp-based">Based: 32-35 °C</p>
                                    <p class="temp-degree" style="color: black;">
                                        <?= $data['temperature'] ?> °C
                                    </p>
                                    <p class="temp-interpretation" style="color: 
                                    <?php
                                    if ($data['temperature'] >= 32 && $data['temperature'] <= 35) {
                                        echo 'green';
                                    } else {
                                        echo 'red';
                                    }
                                    ?>">
                                    <?php
                                        if ($data['temperature'] >= 32 && $data['temperature'] <= 35) {
                                            echo 'Normal';
                                        } elseif ($data['temperature'] < 32) {
                                            echo 'Too Low';
                                        } elseif ($data['temperature'] > 35) {
                                            echo 'Too High';
                                        }else{
                                            echo 'None';
                                        }
                                        ?>
                                    </p>
                                </div>
                                <i class="param-icon fa-solid fa-temperature-arrow-up align-content-center"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="container6">
                            <div class="d-flex justify-content-between m-md-4 m-3">
                                <div class="d-block">
                                    <p class="humid">💧 Humidity</p>
                                    <p class="humid-based">Based: 50-60%</p>
                                    <p class="humid-percent" style="color: black;">
                                        <?= $data['humidity'] ?> %
                                    </p>
                                    <p class="humid-interpretation" style="color: 
                                    <?php
                                    if ($data['humidity'] >= 50 && $data['humidity'] <= 60) {
                                        echo 'green';
                                    } else {
                                        echo 'red';
                                    }
                                    ?>">
                                    <?php
                                        if ($data['humidity'] >= 50 && $data['humidity'] <= 60) {
                                            echo 'Normal';
                                        } elseif ($data['humidity'] < 50) {
                                            echo 'Too Low';
                                        } elseif ($data['humidity'] > 60) {
                                            echo 'Too High';
                                        }else{
                                            echo 'None';
                                        }
                                        ?>
                                    </p>
                                </div>
                                <i class="param-icon fa-solid fa-droplet align-content-center"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="container7">
                            <div class="d-flex justify-content-between m-md-4 m-3">
                                <div class="d-block weight-block">
                                    <p class="weight">⚖️ Weight</p>
                                    <p class="initial-weight">Based: 2 kg</p>
                                    <p class="weight-value"><?= $data['weight'] ?> g</p>
                                    <p class="weight-interpretation" style="margin-top: 24px; font-size: 20px; color: 
                                    <?php
                                    $netWeightKg = $data['weight'] / 1000 - 1; // assuming 1kg hive base
                                    if ($netWeightKg >= 2) {
                                        echo 'green';
                                    } else {
                                        echo 'orange';
                                    }
                                    ?>">
                                    <?php
                                        if ($netWeightKg >= 2) {
                                            echo 'Ready for Harvest';
                                        } else {
                                            echo 'Not Yet Ready';
                                        }
                                        ?>
                                    </p>
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

<?php require base_path('views/partials/sidebarMobile.php') ?>

<script src="/js/fetchdata.js"></script>
<!-- footer -->
<?php require base_path('views/partials/footer.php') ?>