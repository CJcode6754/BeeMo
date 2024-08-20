<?php
// Include PHPMailer and configure SMTP settings
require './vendor/phpmailer/phpmailer/src/Exception.php';
require './vendor/phpmailer/phpmailer/src/PHPMailer.php';
require './vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

class Mailer {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->configureSMTP();
    }

    private function configureSMTP() {
        try {
            $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $this->mail->isSMTP();
            $this->mail->Host       = 'smtp.gmail.com';
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = 'beemoofficialwebsite@gmail.com';
            $this->mail->Password   = 'iien zxds aaho zqvb'; // For security, store this securely
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mail->Port       = 465;

            $this->mail->setFrom('beemoofficialwebsite@gmail.com', 'BeeMo');
        } catch (Exception $e) {
            echo "Mailer Error: " . $this->mail->ErrorInfo;
        }
    }

    public function sendOTP($email, $otp, $name) {
        try {
            $this->mail->addAddress($email, $name);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Verify your email';
            $this->mail->Body = "Hello, {$name}<br>Your account registration is successfully done! Now activate your account with OTP: {$otp}.";
            $this->mail->send();

            // Store a status message in the session
            $_SESSION['status'] = 'OTP sent to your email. Please check your inbox.';
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
            return false;
        }
    }
    public function sendOTPFP($email, $name) {
        try {
            $this->mail->addAddress($email, $name);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Change Password';
            // $mail->Body    = "Hello, {$name}<br>Your account registration is successfully done! Click this link to continue the process of changing password 
            // <a href='https://beemo.website/reset_password.php?email=$email'>RESET PASSWORD</a>.";
            $this->mail->Body    = "Hello, {$name}<br>Your account registration is successfully done! Click this link to continue the process of changing password 
            <a href='http://localhost:3000/resetPassword'>RESET PASSWORD</a>.";
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
            return false;
        }
    }
    public function sendOTP2($email, $name) {
        try {
            $this->mail->addAddress($email, $name);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Verify your email';
            $this->mail->Body = "Hello, {$name}<br>Your account registration is successfully done! Click this link to continue the process of changing password 
            <a href='http://localhost:3000/resetPassword'>RESET PASSWORD</a>.";
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
            return false;
        }
    }
}
?>
