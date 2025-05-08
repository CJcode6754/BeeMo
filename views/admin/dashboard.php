<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeeMo</title>
    <link rel="stylesheet" href="./css/dashboard4.css">
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

<body class="overflow-x-hidden">
    
    <!-- Nav -->
     <?php require"views/partials/sidebar.php" ?>
    <!-- Main -->
    <main class="bg-light">
        <div class="p-2">
            <!-- Navbar -->
            <?php require"views/partials/nav.php" ?>
            <!-- Content -->
            <div class="home-page py-3 mt-4 border border-2 rounded-4 border-dark">
                <div class="px-4 py-4 my-4 text-center content-wrapper">
                    <img src="img/BeeMo Logo.png" class="img-responsive" alt="BeeMo Logo">
                    <div class="col-lg-6 mx-auto">
                        <p class="Beemo-text py-3 mb-5">BeeMo: An IoT-Enabled Web-Based Stingless Beehive Management System with Real-Time Temperature, Humidity, Weight Monitoring</p>
                    </div>
                </div>
            </div>
        </div>
            <div class="space mt-1 d-md-none p-0 m-0"></div>
            <div class="yellow mt-1 d-md-none fixed-bottom p-0 m-0"></div>
    </main>

    <?php require"views/partials/sidebarMobile.php" ?>
    
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
    <script src="./js/reusable.js"></script>
    <script src="./js/notification9.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>

      

