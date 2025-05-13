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
        <div class="worker-page py-5 mt-5 border border-2 rounded-4 border-dark shadow-sm bg-light">
            <div class="container" style="max-width: 600px;">
                <div class="d-flex justify-content-center">
                    <h4 class="mb-4 fw-bold text-dark border-bottom border-warning pb-2">Edit Cycle</h4>
                </div>
                <form action="/harvestCycle/patch" method="POST">
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="id" value="<?=$cycle['id']?>">
                    <!-- Cycle Number -->
                    <div class="mb-2">
                        <label for="name" class="form-label small text-muted">Cycle Number</label>
                        <input name="cycle_num" id="cycle_num" type="number" class="form-control rounded-3 py-2 <?= isset($errors['cycle_num']) ? 'is-invalid' : '' ?>" value="<?= $cycle['cycle_number'] ?>">
                        <?php if (isset($errors['cycle_num'])) : ?>
                            <div class="invalid-feedback d-block"><?= $errors['cycle_num'] ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Start Date -->
                    <div class="mb-2">
                        <label for="start_date" class="form-label small text-muted">Start of Cycle</label>
                        <input name="start_date" id="start_date" type="date" class="form-control rounded-3 py-2 <?= isset($errors['start_date']) ? 'is-invalid' : '' ?>" value="<?= $cycle['start_of_cycle'] ?>">
                        <?php if (isset($errors['start_date'])) : ?>
                            <div class="invalid-feedback d-block"><?= $errors['start_date'] ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- End Date -->
                    <div class="mb-2">
                        <label for="end_date" class="form-label small text-muted">End of Cycle</label>
                        <input name="end_date" id="end_date" type="date" class="form-control rounded-3 py-2 <?= isset($errors['end_date']) ? 'is-invalid' : '' ?>" value="<?= $cycle['end_of_cycle'] ?>">
                        <?php if (isset($errors['end_date'])) : ?>
                            <div class="invalid-feedback d-block"><?= $errors['end_date'] ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-6">
                            <a href="/harvestCycle" class="btn btn-secondary fw-semibold py-2 w-100">Cancel</a>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-dark fw-semibold py-2 w-100" type="submit">Save</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>

<!-- Side Bar Mobile View -->
<?php require base_path("views/partials/sidebarMobile.php") ?>

<!-- Notification -->
<div id="notification" class="notification"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const notification = document.getElementById('notification');

        function showNotification(message) {
            notification.textContent = message;
            notification.classList.add('show');
            setTimeout(function () {
                notification.classList.remove('show');
            }, 6000);
        }
    });
</script>

<!-- Footer -->
<?php require base_path('views/partials/footer.php') ?>
