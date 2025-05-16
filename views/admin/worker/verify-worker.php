<!-- head -->
<?php require base_path('views/partials/head.php') ?>
<div id="contents">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 bg1">
                <div id="LoginLogo" class="container-fluid">
                    <main>
                        <form action="/verify" method="POST">
                            <div class="top px-2 pt-4">
                                <a href="/"><img id="loginLogo" src="img/LOGO2.png" alt="Logo"></a>
                                <p class="about pt-1">ABOUT&nbsp;US</p>
                            </div>
                            <hr class="d-block d-lg-none">
                            <div class="px-2">
                                <h1 class="text">Verify</h1>
                                <p class="fs-4 pb-5">Your code was sent to you via email</p>
                                <label for="otp">Enter OTP</label>
                                <div class="form-group d-flex align-items-center position-relative">
                                    <input type="text" name="otp" id="otp" class="form-control oninput=" checkOTP()">
                                    <div id="countdownTimer" style="color:red" class="position-absolute end-0 pe-2"><span id="countdownTimer"></span></div>
                                </div>
                                <button id="resendBtn" name="resend_otp" class="mt-2 border-0 bg-white" type="submit">Resend Email</button>
                                <button id="btn" name="submit" class="w-100 py-3" type="submit"><b>VERIFY</b></button>
                            </div>
                        </form>
                    </main>
                </div>
            </div>
            <div class="col-lg-6 bg2">
                <div id="loginImg" class="container-fluid"></div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notification = document.getElementById('notification');

        // OTP Countdown function
        function startCountdown(expiryTime, display) {
            const endTime = new Date(expiryTime).getTime();
            const intervalId = setInterval(function() {
                const now = new Date().getTime();
                const duration = endTime - now;

                if (duration <= 0) {
                    clearInterval(intervalId);
                    display.textContent = "Expired";
                    document.getElementById("btn").disabled = true;
                    document.getElementById("resendBtn").disabled = false;

                    // Show OTP expired notification
                    showNotification('OTP expired. Please request a new one.');
                } else {
                    const minutes = Math.floor((duration / (1000 * 60)) % 60);
                    const seconds = Math.floor((duration / 1000) % 60);

                    display.textContent = `${minutes < 10 ? '0' + minutes : minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
                }
            }, 1000);
        }

        // Show notification function
        function showNotification(message) {
            notification.textContent = message;
            notification.classList.add('show');
            setTimeout(function() {
                notification.classList.remove('show');
            }, 6000);
        }

        // Handle the notifications for status and error in the session
        <?php if (isset($_SESSION['status'])): ?>
            showNotification('<?php echo $_SESSION['status']; ?>');
            <?php unset($_SESSION['status']); ?>
        <?php elseif (isset($_SESSION['error'])): ?>
            showNotification('<?php echo $_SESSION['error']; ?>');
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        // Start the OTP countdown if expiry is set
        const otpExpiry = '<?php echo isset($_SESSION['otp_expiry']) ? $_SESSION['otp_expiry'] : ''; ?>';
        if (otpExpiry) {
            const countdownDisplay = document.getElementById('countdownTimer');
            startCountdown(otpExpiry, countdownDisplay);
        } else {
            console.error('OTP expiry time not set or invalid.');
        }

        // Check OTP input
        document.getElementById('otp').addEventListener('input', function() {
            const otpInput = this.value.trim();
            document.getElementById("btn").disabled = otpInput === "";
        });
    });
</script>

<!-- footer -->
</body>
</main>