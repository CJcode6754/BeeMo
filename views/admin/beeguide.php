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
            <div class="beeguide-page mt-4 border border-2 rounded-4 border-dark">
                <div class="text-center content-wrapper scrollable-content container" style="max-height: 580px; overflow-y: auto; scroll-behavior: smooth;">
                    
                    <!-- Scrollable Wrapper -->
                    <p class="beeguideWeb-text fs-4 beeguide-highlight text-centers" style="color: #292929;">Bee Guide</p>

                    <div class="row justify-content-center px-5">
                        <!-- First Image (Left) -->
                        <div class="col-xl-7 col-lg-12 mb-2 d-flex justify-content-center">
                            <img src="/img/SystemArch1.png" class="system-img" alt="Left Image">
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
                            <img src="/img/Web Img/Param.png" id="ParamImage" class="param-img pt-3" alt="Parameters Monitoring Image">
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
                            <img src="/img/Web Img/Reports.png" id="reportsImage" class="reports-img pt-4" alt="Reports Image">
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
                            <img src="/img/Web Img/HarvestCycleTable.png" id="HarvestCycleImage" class="harvest-img pt-4" alt="Workers Image">
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
                            <img src="/img/Web Img/Workers.png" id="workersImage" class="workers-img pt-4" alt="Workers Image">
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
    <?php require base_path("views/partials/sidebarMobile.php") ?>

<!-- footer -->
<?php require base_path('views/partials/footer.php') ?>