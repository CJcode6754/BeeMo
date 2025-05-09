<div class="offcanvas offcanvas-start sidebar2 overflow-x-hidden overflow-y-hidden" tabindex="-1" id="offcanvasNav-Menu" aria-labelledby="staticBackdropLabel">
    <div class="d-flex align-items-center p-3 py-5">
        <a href="/" class="sidebar-logo fw-bold text-dark text-decoration-none fs-4" data-bs-dismiss="offcanvas" aria-label="Close">
            <img src="img/BeeMo Logo Side.png" width="173px" height="75px" alt="BeeMo Logo">
        </a>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <ul class="sidebar-menu p-2 py-2 m-0 mb-0">
        <li class="sidebar-menu-item2 <?= URLS('/') ? 'active' : '' ?>">
            <a href="/">
                <i class="fa-solid fa-house sidebar-menu-item-icon2"></i>
                Home
            </a>
        </li>
        <li class="sidebar-menu-item2 <?= URLS('/chooseHive') ? 'active' : '' ?>">
            <a href="/chooseHive">
                <i class="fa-solid fa-bars-progress sidebar-menu-item-icon2"></i>
                Choose Hive
            </a>
        </li>
        <li class="sidebar-menu-item2 py-1 <?= URLS('/parameterMonitoring') ? 'active' : '' ?>">
            <a href="/parameterMonitoring">
                <i class="fa-solid fa-temperature-arrow-up sidebar-menu-item-icon2"></i>
                Parameters Monitoring
            </a>
        </li>
        <li class="sidebar-menu-item2 <?= URLS('/reports') ? 'active' : '' ?>">
            <a href="/reports">
                <i class="fa-solid fa-chart-line sidebar-menu-item-icon2"></i>
                Reports
            </a>
        </li>
        <li class="sidebar-menu-item2 <?= URLS('/harvestCycle') ? 'active' : '' ?>">
            <a href="/harvestCycle">
                <i class="fa-solid fa-arrows-spin sidebar-menu-item-icon2"></i>
                Harvest Cycle
            </a>
        </li>
        <li class="sidebar-menu-item2 <?= URLS('/beeguide') ? 'active' : '' ?>">
            <a href="/beeguide">
                <i class="fa-solid fa-book sidebar-menu-item-icon2"></i>
                Bee Guide
            </a>
        </li>
        <li class="sidebar-menu-item2 <?= URLS('/workers') ? 'active' : '' ?>">
            <a href="/workers">
                <i class="fs-5 fa-solid fa-person sidebar-menu-item-icon2"></i>
                Worker
            </a>
        </li>
        <li class="sidebar-menu-item2 <?= URLS('/about') ? 'active' : '' ?>">
            <a href="/about">
                <i class="fa-solid fa-circle-info sidebar-menu-item-icon2"></i>
                About
            </a>
        </li>
    </ul>
</div>