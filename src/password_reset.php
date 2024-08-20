<?php
class PasswordReset {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
        session_start();
    }

    public function resetPassword($password, $confirmPassword, $email) {
        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Passwords do not match.';
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $updateQuery = "UPDATE admin_table SET password = ? WHERE email = ?";
        $stmt = $this->conn->prepare($updateQuery);
        $stmt->bind_param('ss', $hashedPassword, $email);

        if ($stmt->execute()) {
            $_SESSION['status'] = 'Password Updated Successfully.';
            header('Location: /');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to update password. Try again.';
            return false;
        }
    }
}
?>
