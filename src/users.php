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
                $_SESSION['status'] = 'Verify your email with the OTP sent.';
                $_SESSION['email'] = $email;
                $_SESSION['admin_name'] = $name;
                return true;
            } else {
                $_SESSION['error'] = 'Failed to send OTP. Try again.';
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

    public function authenticate() {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $email = $this->conn->real_escape_string(filter_var($email, FILTER_SANITIZE_EMAIL));

        // Check in admin_table
        $query = "SELECT * FROM admin_table WHERE email = '$email'";
        $result = $this->conn->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['email'] = $row['email'];
                $_SESSION['adminID'] = $row['adminID'];
                header('Location: /dashboard');
                exit();
            } 
            else {
                $_SESSION['error'] = 'Incorrect email or password';
                header('Location: /');
                exit();
            }
        }

        // // Check in user_table
        // $query = "SELECT * FROM user_table WHERE email = '$email'";
        // $result = $this->conn->query($query);

        // if ($result && $result->num_rows > 0) {
        //     $row = $result->fetch_assoc();
        //     if (password_verify($password, $row['password'])) {
        //         $_SESSION['email'] = $row['email'];
        //         $_SESSION['userID'] = $row['userID'];
        //         header('Location: /user_page');
        //         exit();
        //     } else {
        //         $_SESSION['error'] = 'Incorrect email or password';
        //         header('Location: /');
        //         exit();
        //     }
        // } else {
        //     $_SESSION['error'] = 'Incorrect email or password';
        //     header('Location: /');
        //     exit();
        // }
    }
}
?>
