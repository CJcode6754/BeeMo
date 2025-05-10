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
                    <h4 class="mb-4 fw-bold text-dark border-bottom border-warning pb-2">Add Worker</h4>
                </div>
                <form action="" method="POST">
                    <!-- Name -->
                    <div class="mb-2">
                        <label for="name" class="form-label small text-muted">Full Name</label>
                        <input name="name" id="name" type="text" class="form-control rounded-3 py-2 <?= isset($errors['name']) ? 'is-invalid' : '' ?>" placeholder="e.g. Juan Dela Cruz">
                        <?php if (isset($errors['name'])) : ?>
                            <div class="invalid-feedback d-block"><?= $errors['name'] ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="mb-2">
                        <label for="email" class="form-label small text-muted">Email</label>
                        <input name="email" id="email" type="text" class="form-control rounded-3 py-2 <?= isset($errors['email']) ? 'is-invalid' : '' ?>" placeholder="e.g. juan@example.com">
                        <?php if (isset($errors['email'])) : ?>
                            <div class="invalid-feedback d-block"><?= $errors['email'] ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-2">
                        <label for="number" class="form-label small text-muted">Phone Number</label>
                        <input name="number" id="number" type="tel" class="form-control rounded-3 py-2 <?= isset($errors['number']) ? 'is-invalid' : '' ?>" placeholder="e.g. 09123456789">
                        <?php if (isset($errors['number'])) : ?>
                            <div class="invalid-feedback d-block"><?= $errors['number'] ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Password -->
                    <div class="mb-2">
                        <label for="password" class="form-label small text-muted">Password</label>
                        <input name="password" id="password" type="password" class="form-control rounded-3 py-2 <?= isset($errors['password']) ? 'is-invalid' : '' ?>" placeholder="Enter password">
                        <?php if (isset($errors['password'])) : ?>
                            <div class="invalid-feedback d-block"><?= $errors['password'] ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-2">
                        <label for="password_confirmation" class="form-label small text-muted">Confirm Password</label>
                        <input name="password_confirmation" type="password" class="form-control rounded-3 py-2" placeholder="Confirm password">
                        <?php if (isset($errors['password_confirmation'])) : ?>
                            <div class="invalid-feedback d-block"><?= $errors['password_confirmation'] ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button class="btn btn-dark fw-semibold py-2" type="submit">Save</button>
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

<script src="/js/worker.js"></script>

<!-- Footer -->
<?php require base_path('views/partials/footer.php') ?>
