<?php

namespace Core;

use Core\Database;

class OTP
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
        date_default_timezone_set('Asia/Manila');
    }

    public function generateOTP($email)
    {
        $otp = rand(100000, 999999);
        $otp_expiry = date('Y-m-d H:i:s', strtotime('+3 minutes'));

        $this->db->query(
            "UPDATE admin_table SET otp = ?, otp_expiry = ? WHERE email = ?",
            [$otp, $otp_expiry, $email]
        );

        $_SESSION['otp_expiry'] = $otp_expiry;

        return ['otp' => $otp, 'otp_expiry' => $otp_expiry];
    }

    public function generateOTPResend($email)
    {
        return $this->generateOTP($email);
    }

    public function generateOTPUser($email)
    {
        $otp = rand(100000, 999999);
        $otp_expiry = date('Y-m-d H:i:s', strtotime('+3 minutes'));

        $this->db->query(
            "UPDATE user_table SET otp = ?, otp_expiry = ? WHERE email = ?",
            [$otp, $otp_expiry, $email]
        );

        $_SESSION['otp_expiry'] = $otp_expiry;

        return ['otp' => $otp, 'otp_expiry' => $otp_expiry];
    }

    public function verifyOTP($email, $otp)
    {
        $current_time = date('Y-m-d H:i:s');

        $this->db->query(
            "SELECT * FROM user_table WHERE email = ? AND otp = ? AND otp_expiry > ?",
            [$email, $otp, $current_time]
        );

        $result = $this->db->find();

        if ($result) {
            $this->db->query(
                "UPDATE user_table SET is_verified = 1, otp = NULL, otp_expiry = NULL WHERE email = ?",
                [$email]
            );

            unset($_SESSION['otp']);
            unset($_SESSION['otp_expiry']);

            return true;
        }

        return false;
    }
}
