<?php
use Core\App;
use Core\Validator;
use Core\Database;
use Core\Mailer;
use Core\OTP;

$db = App::resolve(Database::class);
$otpHandler = new OTP($db);
$mailer = new Mailer();

$currentAdminID = $_SESSION['user']['id'];
$errors = [];

if (!Validator::string($_POST['name'], 1, 255)) {
    $errors['name'] = 'Name field is required';
}

if (!Validator::email($_POST['email'])) {
    $errors['email'] = 'Email field is required';
}

if (!Validator::string($_POST['number'], 11, 11)) {
    $errors['number'] = 'Please provide eleven character number & it must start with 09.';
}

if (!Validator::string($_POST['password'], 6)) {
    $errors['password'] = 'Please provide a password of at least six character.';
}

if (count($errors)) {
    return view("/worker/create.php", [
        'heading' => 'Create Worker',
        'errors' => $errors
    ]);
}

$otpData = $otpHandler->generateOTPUser($_POST['email']);

$db->query('INSERT INTO user_table(name, email, number, password, admin_id, otp, otp_expiry) VALUES (:name, :email, :number, :password, :admin_id, :otp, :otp_expiry)', [
    'name' => $_POST['name'],
    'email' => $_POST['email'],
    'number' => $_POST['number'],
    'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
    'admin_id' => $currentAdminID,
    'otp' => $otpData['otp'],
    'otp_expiry' => $otpData['otp_expiry'],
]);

$mailer->sendOTPWorker($_POST['name'], $_POST['email'], $otpData['otp']);

$_SESSION['pending_verification_email'] = $_POST['email'];
header('Location: /verify-worker');
exit();
