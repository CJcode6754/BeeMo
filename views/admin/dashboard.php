    <!-- head -->
    <?php require base_path('views/partials/head.php') ?>
    <!-- Nav -->
    <?php require base_path('views/partials/sidebar.php') ?>
    <!-- Main -->
    <main class="bg-light">
        <div class="p-2">
            <!-- Navbar -->
            <?php require base_path("views/partials/nav.php") ?>
            <!-- Content -->
            <div class="home-page py-3 mt-4 border border-2 rounded-4 border-dark">
                <div class="px-4 py-4 my-4 text-center content-wrapper">
                    <img src="/img/BeeMo Logo.png" class="img-responsive" alt="BeeMo Logo">
                    <div class="col-lg-6 mx-auto">
                        <p class="Beemo-text py-3 mb-5">BeeMo: An IoT-Enabled Web-Based Stingless Beehive Management System with Real-Time Temperature, Humidity, Weight Monitoring</p>
                    </div>
                </div>
            </div>
        </div>
            <div class="space mt-1 d-md-none p-0 m-0"></div>
            <div class="yellow mt-1 d-md-none fixed-bottom p-0 m-0"></div>
    </main>

    <?php require base_path("views/partials/sidebarMobile.php") ?>
    
    <script>
        // history.pushState(null, null, null);
        // window.addEventListener('popstate', function () {
        //     history.pushState(null, null, null);
        // });

        function handleBackNavigation() {
            history.pushState(null, null, null);
            window.addEventListener('popstate', function () {
                history.pushState(null, null, null);
                // Optionally redirect to a specific page if needed
                window.location.replace('/'); // Redirect to login or another page
            });
        }

        // Call the function to handle back navigation
        handleBackNavigation();
    </script>
<!-- footer -->
<?php require base_path('views/partials/footer.php') ?>

      

