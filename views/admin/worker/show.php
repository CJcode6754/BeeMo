<!-- head -->
<?php require base_path('views/partials/head.php') ?>
<!-- Sidebar -->
<?php require base_path("views/partials/sidebar.php") ?>
<style>
    .bg-bee {
        background: linear-gradient(135deg, #fff8e1, #ffecb3);
    }

    .bee-card {
        background: rgba(255, 255, 255, 0.95);
        border: 2px solid #f9c80e;
        border-radius: 2rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(8px);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .text-bee-dark {
        color: #5c4b00;
    }

    .btn-bee {
        background-color: #f9c80e;
        color: #000;
        border: none;
    }

    .btn-bee:hover {
        background-color: #f4b400;
        color: #000;
    }

    /* Full viewport height and centering content */
    .main-container {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .content-wrapper {
        max-width: 960px;
        width: 100%;
    }

    .text-center {
        text-align: center;
    }
</style>

<!-- Main -->
<main class="bg-bee main-container">
    <div class="container content-wrapper">
        <?php $deleteModalID = 'DeleteModal_' . $worker['id']; ?>

        <!-- Centered Content -->
        <div class="text-center mb-4">
            <h2 class="fw-bold display-5 text-bee-dark" style="font-family: 'Poppins', sans-serif;">
                🐝 Worker Information
            </h2>
            <p class="text-muted">Organize and manage your hive members</p>
        </div>

        <!-- Worker Card -->
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="bee-card p-5">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="fw-bold text-bee-dark mb-1">
                                <i class="fa-solid fa-user text-warning me-2"></i><?= $worker['name'] ?>
                            </h4>
                            <span class="badge bg-warning text-dark">Active Bee</span>
                        </div>
                        <img src="/assets/bee-icon.png" alt="Bee Icon" width="48" height="48">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-bee-dark fw-semibold">Email</label>
                        <div class="form-control-plaintext"><?= $worker['email'] ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-bee-dark fw-semibold">Contact Number</label>
                        <div class="form-control-plaintext"><?= $worker['number'] ?></div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-bee-dark fw-semibold">Password</label>
                        <div class="form-control-plaintext"><?= $worker['password'] ?></div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button class="btn btn-bee px-4 rounded-pill">
                            <i class="fa-regular fa-pen-to-square me-2"></i>Edit
                        </button>
                        <button class="btn btn-danger px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#<?= $deleteModalID ?>">
                            <i class="fa-regular fa-trash-can me-2"></i>Delete
                        </button>
                    </div>

                    <!-- Back Button -->
                    <div class="text-end mt-4">
                        <a href="/workers" class="btn btn-dark px-4 rounded-pill">
                            <i class="fa-solid fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
                <div class='modal fade' id='<?= $deleteModalID ?>' tabindex='-1' aria-labelledby='Delete_WorkerModal' aria-hidden='true'>
                        <div class='modal-dialog modal-lg modal-dialog-centered rounded d-flex justify-content-center'>
                            <div class='modal-content' style='border: 2px solid #2B2B2B; width: 450px; height: 180px;'>
                                <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                    <h5 class='modal-title fw-semibold mx-4' id='Delete_WorkerModal_<?= $deleteModalID ?>'>Are you sure you want to delete this worker? </h5>
                                </div>
                                <div class='modal-body m-2 d-flex justify-content-center'>
                                    <form action='/worker/delete' method='POST' class='row mt-2 g-1'>
                                        <div class='col-md-4 me-5'>
                                            <button type='button' class='btn btn-dark' data-bs-dismiss='modal' aria-label='Close'>No</button>
                                        </div>
                                        <div class='col-md-4'>
                                            <button name='btn_delete' type='submit' class='btn btn-success'>Yes</button>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type='hidden' name='id' value='<?= $worker['id'] ?>'>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</main>

<!-- Notification -->
<div id="notification" class="position-fixed top-0 end-0 m-4 p-3 bg-warning text-dark rounded shadow d-none">
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const notification = document.getElementById('notification');

        function showNotification(message) {
            notification.textContent = message;
            notification.classList.remove('d-none');
            setTimeout(() => {
                notification.classList.add('d-none');
            }, 6000);
        }
    });
</script>

<script src="/js/worker.js"></script>

<!-- footer -->
<?php require base_path('views/partials/footer.php') ?>