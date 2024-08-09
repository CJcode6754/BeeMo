<?php
class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function register($name, $email, $M_number, $password) {
        // Check if the email already exists
        $check_email_query = "SELECT * FROM admin_table WHERE email='$email'";
        $check_email_result = mysqli_query($this->conn, $check_email_query);

        if (mysqli_num_rows($check_email_result) > 0) {
            $_SESSION['error'] = 'Email Address Already Exists';
            return false;
        }

        $password_hashed = password_hash($password, PASSWORD_BCRYPT);

        // Generate OTP and set OTP expiry
        $otpObj = new OTP($this->conn);
        $otpData = $otpObj->generateOTP($email);
        $otp = $otpData['otp'];
        $otp_expiry = $otpData['otp_expiry'];

        // Insert the new user into the database
        $insert = "INSERT INTO admin_table (admin_name, email, number, password, otp, is_verified, otp_expiry)
                   VALUES ('$name', '$email', '$M_number', '$password_hashed', '$otp', 0, '$otp_expiry')";

        $insert_run = mysqli_query($this->conn, $insert);

        if ($insert_run) {
            $mailer = new Mailer();
            if ($mailer->sendOTP($email, $otp, $name)) {
                $_SESSION['status'] = 'Registration Successful. Verify your Email Address with the OTP sent.';
                $_SESSION['email'] = $email;
                return true;
            } else {
                $_SESSION['error'] = 'Failed to send OTP. Please try again later.';
                return false;
            }
        } else {
            $_SESSION['error'] = 'Error: ' . mysqli_error($this->conn);
            return false;
        }
    }
}

class UserIndex {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function authenticate($email, $password) {
        $email = $this->conn->real_escape_string(filter_var($email, FILTER_SANITIZE_EMAIL));

        // Check in admin_table
        $query = "SELECT * FROM admin_table WHERE email = '$email'";
        $result = $this->conn->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['email'] = $row['email'];
                $_SESSION['adminID'] = $row['adminID'];
                return 'dashboard.php';
            } else {
                return 'Incorrect email or password';
            }
        }

        // Check in user_table
        $query = "SELECT * FROM user_table WHERE email = '$email'";
        $result = $this->conn->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['email'] = $row['email'];
                $_SESSION['userID'] = $row['userID'];
                return 'user_page.php';
            } else {
                return 'Incorrect email or password';
            }
        }

        return 'Incorrect email or password';
    }
}
?>
