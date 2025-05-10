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
        <div class="worker-page py-3 mt-4 border border-2 rounded-4 border-dark">
            <div class="px-4 py-4 my-4 text-center content-wrapper">
                <p class="fs-4 mb-5 fw-bold worker-highlight">Workers</p>
                <div class="container-worker">
                    <div class="table-responsive mt-5" style="max-height: 165px; overflow-y: auto;">
                        <table class="table worker-table border-dark" name="worker_list">
                            <thead>
                                <tr>
                                    <th style="background-color: #FAEF9B;">Full Name</th>
                                    <th style="background-color: #FAEF9B;">Email</th>
                                    <th style="background-color: #FAEF9B">Contact Number</th>
                                    <th style="background-color: #FAEF9B;">Password</th>
                                    <th style="background-color: #FAEF9B;">Edit</th>
                                    <th style="background-color: #FAEF9B;">Remove</th>
                                </tr>
                            </thead>
                            <tbody id="workerTableBody">
                                <?php foreach ($workers as $worker) : ?>
                                    <?php $deleteModalID = 'DeleteModal_' . $worker['id']; ?>
                                    <tr>
                                        <td><a class="text-decoration-none" href="/worker?id=<?= $worker['id']?>"><?= $worker['name'] ?></a></td>
                                        <td><?= $worker['email'] ?></td>
                                        <td><?= $worker['number'] ?></td>
                                        <td><?= $worker['password'] ?></td>
                                        <td>
                                            <button name='btn_edit' class='btn edit-btn'
                                                type='button'>
                                                <i class='fa-regular fa-pen-to-square'></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button class='btn delete-btn'><i class='fa-regular fa-trash-can' style='color: red;' data-bs-toggle='modal' type='button' data-bs-target='#<?= $deleteModalID ?>'></i></button>
                                        </td>
                                    </tr>

                                    <div class='modal fade' id='<?=$deleteModalID?>' tabindex='-1' aria-labelledby='Delete_WorkerModal' aria-hidden='true'>
                                        <div class='modal-dialog modal-lg modal-dialog-centered rounded d-flex justify-content-center'>
                                            <div class='modal-content' style='border: 2px solid #2B2B2B; width: 450px; height: 180px;'>
                                                <div class='modal-header border-dark border-2' style='background-color: #FCF4B9;'>
                                                    <h5 class='modal-title fw-semibold mx-4' id='Delete_WorkerModal_<?= $deleteModalID ?>'>Are you sure you want to delete this cycle? </h5>
                                                </div>
                                                <div class='modal-body m-2 d-flex justify-content-center'>
                                                    <form action='' method='POST' class='row mt-2 g-1'>
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
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class=" mt-5 d-flex justify-content-end pe-3">
                        <a href="/worker/create" class="add-button px-4 border border-1 border-black text-black text-decoration-none fw-semibold">
                            <span class="fw-bold">+ </span> Add Worker
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Side Bar Mobile View -->

<?php require base_path("views/partials/sidebarMobile.php") ?>

<div id="notification" class="notification"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notification = document.getElementById('notification');

        // Show notification function
        function showNotification(message) {
            notification.textContent = message;
            notification.classList.add('show');
            setTimeout(function() {
                notification.classList.remove('show');
            }, 6000);
        }
    });
</script>
<script src="/js/worker.js"></script>
<!-- footer -->
<?php require base_path('views/partials/footer.php') ?>