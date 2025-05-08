<div id="sidebar" class="sidebar position-fixed top-0 bottom-0 bg-white border-end offcanvass">
    <div class="d-flex align-items-center p-3 py-5">
        <a href="/dashboard.php" class="sidebar-logo fw-bold text-dark text-decoration-none fs-4"><img src="img/BeeMo Logo Side.png" width="173px" height="75px" alt="BeeMo Logo"></a>
    </div>
    <ul class="sidebar-menu p-3 py-1 m-0 mb-0">
        <li class="sidebar-menu-item <?= URLS('/dashboard.php') ? 'active' : '' ?>">
            <a href="/dashboard.php">
                <i class="fa-solid fa-house sidebar-menu-item-icon"></i>
                Home
            </a>
        </li>
        <li class="sidebar-menu-item <?= URLS('/chooseHive.php') ? 'active' : '' ?>">
            <a href="/chooseHive.php">
                <i class="fs-5 fa-solid fa-bars-progress sidebar-menu-item-icon"></i>
                Choose Hive
            </a>
        </li>
        <li class="sidebar-menu-item <?= URLS('/parameterMonitoring.php') ? 'active' : '' ?>">
            <a href="/parameterMonitoring.php">
                <i class="fs-5 fa-solid fa-temperature-arrow-up sidebar-menu-item-icon"></i>
                Parameters Monitoring
            </a>
        </li>
        <li class="sidebar-menu-item <?= URLS('/reports.php') ? 'active' : '' ?>">
            <a href="/reports.php">
                <i class="fs-5 fa-solid fa-chart-line sidebar-menu-item-icon"></i>
                Reports
            </a>
        </li>
        <li class="sidebar-menu-item <?= URLS('/harvestCycle.php') ? 'active' : '' ?>">
            <a href="/harvestCycle.php">
                <i class="fs-5 fa-solid fa-arrows-spin sidebar-menu-item-icon"></i>
                Harvest Cycle
            </a>
        </li>
        <li class="sidebar-menu-item <?= URLS('/beeguide.php') ? 'active' : '' ?>">
            <a href="/beeguide.php">
                <i class="fs-5 fa-solid fa-book sidebar-menu-item-icon"></i>
                Bee Guide
            </a>
        </li>
        <li class="sidebar-menu-item <?= URLS('/addWorker.php') ? 'active' : '' ?>">
            <a href="/addWorker.php">
                <i class="fs-4 fa-solid fa-person sidebar-menu-item-icon"></i>
                Worker
            </a>
        </li>
        <li class="sidebar-menu-item <?= URLS('/about.php') ? 'active' : '' ?>">
            <a href="/about.php">
                <i class="fa-solid fa-circle-info sidebar-menu-item-icon"></i>
                About
            </a>
        </li>
    </ul>
</div>