<?php
class OTP {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
        date_default_timezone_set('Asia/Manila'); // Set timezone in the constructor
    }

    public function generateOTP($email) {
        $otp = rand(100000, 999999); // Generate random OTP
        $current_time = time();
        $otp_expiry = date('Y-m-d H:i:s', $current_time + (3 * 60)); // Set OTP expiry time (3 minutes from now)

        // Update admin_table with new OTP and expiry time
        $update = "UPDATE admin_table SET otp='$otp', otp_expiry='$otp_expiry' WHERE email='$email'";
        mysqli_query($this->conn, $update);

        // Store OTP expiry in session
        $_SESSION['otp_expiry'] = $otp_expiry;

        return ['otp' => $otp, 'otp_expiry' => $otp_expiry];
    }
    public function generateOTPResend($email)
    {
        $otp = rand(100000, 999999);
        $otp_expiry = date('Y-m-d H:i:s', strtotime('+3 minutes'));

        $query = "UPDATE admin_table SET otp='$otp', otp_expiry='$otp_expiry' WHERE email='$email'";
        mysqli_query($this->conn, $query);

        $_SESSION['otp_expiry'] = $otp_expiry;

        return ['otp' => $otp, 'otp_expiry' => $otp_expiry];
    }

    public function generateOTPUser($email){
        $otp = rand(100000, 999999);
        $otp_expiry = date('Y-m-d H:i:s', strtotime('+3 minutes'));

        $stmt = $this->conn->prepare("UPDATE user_table SET otp=?, otp_expiry=? WHERE email=?");
        $stmt->bind_param('iss', $otp, $otp_expiry, $email);
        $stmt->execute();

        $_SESSION['otp_expiry'] = $otp_expiry;

        return ['otp' => $otp, 'otp_expiry' => $otp_expiry];
    }


    public function verifyOTP($email, $otp) {
        $current_time = date('Y-m-d H:i:s'); // Current time in server's timezone
        $query = "SELECT * FROM admin_table WHERE email='$email' AND otp='$otp' AND otp_expiry > '$current_time'";
        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // Update is_verified status and clear OTP data
            $update = "UPDATE admin_table SET is_verified=1, otp=NULL, otp_expiry=NULL WHERE email='$email'";
            mysqli_query($this->conn, $update);

            // Clear OTP from session after successful verification
            unset($_SESSION['otp']);
            unset($_SESSION['otp_expiry']);

            return true;
        }
        return false;
    }
}
?>
