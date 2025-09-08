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
        <div class="beeguide-page mt-4 border border-2 rounded-4 border-dark shadow-sm">
            <div class="content-wrapper container py-4" style="max-height: 580px; overflow-y: auto; scroll-behavior: smooth;">
                
                <!-- Title -->
                <h1 class="text-center fw-bold mb-4" style="color:#292929;">Bee Guide</h1>

                <!-- System Overview -->
                <div class="row align-items-center justify-content-center mb-5">
                    <div class="col-xl-7 col-lg-12 text-center mb-3">
                        <img src="/img/SystemArch1.png" class="img-fluid rounded shadow" alt="BeeMo System Architecture Diagram" loading="lazy">
                    </div>
                    <div class="col-xl-5 col-lg-12">
                        <h2 class="text-center fw-semibold py-2">How does the system work?</h2>
                        <p>
                            The <strong>BeeMo system</strong> operates as an integrated solution for monitoring and managing 
                            the environment within artificial beehives. Sensors such as the <strong>DHT22</strong> (temperature &amp; humidity) 
                            and <strong>HX711</strong> (weight) continuously gather real-time data on critical parameters affecting bee health 
                            and productivity.  
                        </p>
                        <p>
                            Data is transmitted via <strong>NodeMCU ESP8266</strong> to a central processor, where it is analyzed. 
                            Based on the analysis, regulators such as the <strong>TEC-12706 thermoelectric cooler</strong>, 
                            <strong>PTC heater</strong>, and <strong>DC fan</strong> are activated to maintain optimal hive conditions.  
                            A stable power supply ensures uninterrupted operation and continuous monitoring, 
                            thereby <strong>enhancing honey production and overall farm efficiency</strong>.
                        </p>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white border rounded-3 p-4 shadow-sm mb-5">
                    <div class="row">
                        <div class="col-12">
                            <p class="mb-3">
                                <strong>"BeeMo: An IoT-Enabled Web-Based Stingless Beehive Management System with Real-Time Temperature, 
                                Humidity, Weight Monitoring"</strong> modernizes and improves stingless bee farming by replacing 
                                traditional methods with IoT-enabled monitoring.  
                            </p>
                            <div class="alert alert-warning mb-0" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Note:</strong> If issues occur, please check wire connections. 
                                If the problem persists, contact the maintenance team.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Web-System Tutorial -->
                <h2 class="text-center fw-bold mb-4" style="color:#292929;">Web-System Tutorial</h2>

                <!-- Parameters Monitoring -->
                <section class="mb-5">
                    <h3 class="text-center fw-semibold mb-3" style="color:#292929;">Parameters Monitoring</h3>
                    <div class="text-center mb-3">
                        <img src="/img/Web%20Img/Param.png" class="img-fluid shadow rounded" alt="Parameters Monitoring Interface" loading="lazy">
                    </div>
                    <p>
                        <strong>Parameters Monitoring</strong> allows users to monitor the hive's 
                        <span class="text-success fw-semibold">temperature</span>, 
                        <span class="text-primary fw-semibold">humidity</span>, and 
                        <span class="text-warning fw-semibold">weight</span>.  
                        Users can disable automatic regulation and set specific values manually. 
                        If parameters fall outside thresholds, the system triggers notifications 
                        (displayed via the website's notification icon).
                    </p>
                </section>

                <hr class="my-4">

                <!-- Reports -->
                <section class="mb-5">
                    <h3 class="text-center fw-semibold mb-3" style="color:#292929;">Reports</h3>
                    <div class="text-center mb-3">
                        <img src="/img/Web%20Img/Reports.png" class="img-fluid shadow rounded" alt="Reports Dashboard Interface" loading="lazy">
                    </div>
                    <p>
                        The <strong>Reports</strong> feature provides historical parameter data (with date &amp; time).  
                        Data is visualized in line graphs, with 
                        <span class="text-danger fw-semibold">red</span> markers for out-of-range values and 
                        <span class="text-success fw-semibold">green</span> for optimal values.  
                        Reports can be filtered by <strong>Harvest Cycle</strong> (Admin or Worker), and refined 
                        further by month or day using dropdown filters.
                    </p>
                </section>

                <hr class="my-4">

                <!-- Harvest Cycle -->
                <section class="mb-5">
                    <h3 class="text-center fw-semibold mb-3" style="color:#292929;">Harvest Cycle</h3>
                    <div class="text-center mb-3">
                        <img src="/img/Web%20Img/HarvestCycleTable.png" class="img-fluid shadow rounded" alt="Harvest Cycle Management Table" loading="lazy">
                    </div>
                    <p>
                        The <strong>Harvest Cycle</strong> feature records harvested honey weight (kg).  
                        Entries can be edited or deleted.  
                        It integrates with <strong>Reports</strong> for cycle-based analytics.  
                        Admins and Workers have separate cycle views, with filters for 
                        <strong>All</strong>, <strong>Pending</strong>, and <strong>Completed</strong> cycles.  
                        Notifications (web &amp; SMS) are sent near cycle completion for timely management.
                    </p>
                </section>

                <hr class="my-4">

                <!-- Workers -->
                <section class="mb-4">
                    <h3 class="text-center fw-semibold mb-3" style="color:#292929;">Workers</h3>
                    <div class="text-center mb-3">
                        <img src="/img/Web%20Img/Workers.png" class="img-fluid shadow rounded" alt="Worker Management Interface" loading="lazy">
                    </div>
                    <p>
                        The <strong>Workers</strong> module (admin-only) manages worker accounts.  
                        Admins can <strong>add</strong>, <strong>edit</strong>, or <strong>remove</strong> accounts, 
                        and assign permissions.  
                        Workers log in using admin-provided credentials, gaining access to their assigned roles in the system.
                    </p>
                </section>

            </div>
        </div>
    </div>
    <div class="space mt-1 d-md-none"></div>
    <div class="yellow mt-1 d-md-none fixed-bottom"></div>
</main>

<!-- Side Bar Mobile View -->
<?php require base_path("views/partials/sidebarMobile.php") ?>

<!-- footer -->
<?php require base_path('views/partials/footer.php') ?>