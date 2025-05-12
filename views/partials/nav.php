<nav class="px-3 py-3 rounded-4">
    <div>
        <p class="d-none d-lg-block mt-3 mx-4 fw-bold" style="font-size: 17px;"><?= URLS('/worker/create') ? "Create $heading" : "Welcome to $heading" ?></p>
    </div>
    <i class="fa-solid fa-bars sidebar-toggle me-3 d-block d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNav-Menu" aria-controls="offcanvasRight" aria-expanded="false" aria-label="Toggle navigation"></i>
    <h5 class="fw-bold mb-0 me-auto"></h5>
    <div class="dropdown me-3 d-sm-block">
        <div id="nf-btn" class="navbar-link border border-1 border-black rounded-5" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-bell"></i>
            <span id="nf-count"></span>
        </div>
        <div class="notif-container dropdown-menu dropdown-menu-start border-dark border-2 rounded-3">
            <div class="d-flex justify-content-between dropdown-header border-dark border-2">
                <div>
                    <p class="fs-6 fw-bold text-dark pt-3">Notifications
                        <span class="badge text-dark bg-warning-subtle rounded-pill" id="nf-count-badge">0</span>
                    </p>
                </div>
                <div>
                    <form class="pb-2" action="/dashboard" method="post">
                        <button class="clearNotif" name="clearNotif">Clear all</button>
                    </form>
                </div>
            </div>
            <div id="notifications">
                <!-- Notifications will be dynamically inserted here -->
            </div>
        </div>
    </div>

    <div class="dropdown me-3 d-sm-block">
        <div class="navbar-link border border-1 border-black rounded-5" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-user"></i>
        </div>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li>
                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#Profile-Modal">
                    <i class="fa-solid fa-user"></i>
                    Profile
                </a>
            </li>
            <!-- Logout -->
            <form id="logoutForm" action="/session" method="POST" style="display: none;">
                <input type="hidden" name="_method" value="DELETE">
            </form>
            <li class="dropdown-item" onclick="document.getElementById('logoutForm').submit();">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </li>
        </ul>
    </div>
</nav>