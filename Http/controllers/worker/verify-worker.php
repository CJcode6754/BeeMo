<?php
use Core\App;
use Core\Database;
use Core\OTP;

$db = App::resolve(Database::class);
$otpHandler = new OTP($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['pending_verification_email'] ?? null;
    $otp = $_POST['otp'] ?? null;

    if ($email && $otp) {
        // You probably want to *verify* the OTP here, not generate a new one
        $isValid = $otpHandler->verifyOTP($email, $otp);

        if ($isValid) {
            unset($_SESSION['pending_verification_email']);
            $_SESSION['status'] = 'Email successfully verified!';
            header('location: /workers'); // or login or dashboard
            exit();
        } else {
            $error = "Invalid or expired OTP.";
        }
    } else {
        $error = "Email or OTP not provided.";
    }
}

view("/worker/verify-worker.php", [
    'error' => $error ?? null,
]);
