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
        session_start();

        $email = $_POST['email'];
        $password = $_POST['password'];

        // Sanitize and filter input
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Check in admin_table
        $stmt = $this->conn->prepare("SELECT * FROM admin_table WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['email'] = $row['email'];
                $_SESSION['adminID'] = $row['adminID'];
                header('Location: /dashboard');
                exit();
            } else {
                $_SESSION['error'] = 'Incorrect password';
                header('Location: /');
                exit();
            }
        }

        // Check in user_table
        $stmt1 = $this->conn->prepare("SELECT * FROM user_table WHERE email = ?");
        $stmt1->bind_param("s", $email);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        if ($result1->num_rows > 0) {
            $row = $result1->fetch_assoc();

            // Compare the plain-text password directly
            if ($password === $row['password']) {
                $_SESSION['email'] = $row['email'];
                $_SESSION['userID'] = $row['userID'];
                header('Location: /userDashboard');  // Redirect to dashboard
                exit();
            } else {
                $_SESSION['error'] = 'Incorrect password';
                header('Location: /');  // Redirect back to login
                exit();
            }
        } else {
            $_SESSION['error'] = 'Email not found';
            header('Location: /');  // Redirect back to login
            exit();
        }

    }
}

?>
