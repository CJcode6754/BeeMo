<?php
namespace Core;

// Include PHPMailer and configure SMTP settings
require base_path('vendor/phpmailer/phpmailer/src/Exception.php');
require base_path('vendor/phpmailer/phpmailer/src/PHPMailer.php');
require base_path('vendor/phpmailer/phpmailer/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require base_path('vendor/autoload.php');

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
            $this->mail->Username   = 'ceejayibabiosa@gmail.com';
            $this->mail->Password   = 'oboc qlez prvp etfp'; // For security, store this securely
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mail->Port       = 465;

            $this->mail->setFrom('ceejayibabiosa@gmail.com', 'BeeMo');
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
    
    public function sendDeleteHive($email, $otp, $name) {
        try {
            $this->mail->addAddress($email, $name);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Verify your email to delete hive';
            $this->mail->Body = "Hello, {$name}<br>This is the otp to delete your selected hive! Now input this OTP: {$otp}.";
            $this->mail->send();

            // Store a status message in the session
            $_SESSION['status'] = 'OTP sent to your email. Please check your inbox.';
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
            return false;
        }
    }
    
    public function sendLoginDetailsEmail($email, $password, $name) {
        try {
            $this->mail->addAddress($email, $name);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Login Details';
            $this->mail->Body = "Hello, {$name},<br>Your email has been successfully verified! Below are your login details:<br><br>
                                 Email: {$email}<br>
                                 Password: {$password}<br><br>
                                 Please keep this information confidential and do not share it with anyone.";
            $this->mail->send();
            
            // Store a status message in the session
            $_SESSION['status'] = 'Verification successful. Login details sent to your email.';
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
            return false;
        }
    }

    public function sendOTPWorker($userName, $email, $otp) {
        try {
            $this->mail->addAddress($email, $userName);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Verify your email';
            $this->mail->Body = "Hello, {$userName}<br>Your account registration is successfully done! Now activate your account with OTP: {$otp}.";
            $this->mail->send();
            
            // Store a status message in the session
            $_SESSION['status'] = 'OTP sent to your email. Please check your inbox.';
            return true;
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}");
            return false;
        }
    }

    public function sendOTPEmail($current_name, $edit_email, $otp) {
        try {
            $this->mail->addAddress($edit_email, $current_name);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Verify your email';
            $this->mail->Body = "Hello, {$current_name}<br>Your account registration is successfully done! Now activate your account with OTP: {$otp}.";
            $this->mail->send();

            return true;
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}");
            return false;
        }
    }

    public function sendOTPFP($email, $name) {
        try {
            $this->mail->addAddress($email, $name);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Change Password';
            $this->mail->Body= "Hello, {$name}<br>Click this link to continue the process of changing password 
            <a href='https://beemo.website/resetPassword?email=$email'>RESET PASSWORD</a>.";
            //$this->mail->Body    = "Hello, {$name}<br>Click this link to continue the process of changing password
            //<a href='http://localhost:3000/resetPassword'>RESET PASSWORD</a>.";
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
            $this->mail->Subject = 'Change Password';
            $this->mail->Body = "Hello, {$name}<br>Click this link to continue the process of changing password 
            <a href='https://beemo.website/resetPassword'>RESET PASSWORD</a>.";
            //$this->mail->Body = "Hello, {$name}<br>Click this link to continue the process of changing password
            //<a href='http://localhost:3000/resetPassword'>RESET PASSWORD</a>.";
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
            return false;
        }
    }
}
