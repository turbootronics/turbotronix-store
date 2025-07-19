<?php
session_start();
include "db.php";
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to display error page with gradient style
function showError($message) {
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <title>Error | OTP</title>
        <style>
            body {
                font-family: 'Segoe UI', sans-serif;
                background: linear-gradient(-45deg, #0f0c29, #302b63, #24243e, #00b5ff);
                background-size: 400% 400%;
                animation: gradientBG 15s ease infinite;
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
            }

            @keyframes gradientBG {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            .box {
                background: rgba(0, 0, 0, 0.7);
                padding: 30px;
                border-radius: 12px;
                text-align: center;
                width: 350px;
                box-shadow: 0 8px 24px rgba(0,0,0,0.6);
            }

            .box h2 {
                margin-bottom: 20px;
                color: #ff4c4c;
            }

            .box p {
                margin-bottom: 20px;
                font-size: 16px;
            }

            .box a {
                padding: 10px 20px;
                background-color: #00b5ff;
                color: #fff;
                text-decoration: none;
                border-radius: 6px;
                transition: background 0.3s;
            }

            .box a:hover {
                background-color: #008fcc;
            }
        </style>
    </head>
    <body>
        <div class='box'>
            <h2>Error</h2>
            <p>$message</p>
            <a href='forgot_password.php'>← Try Again</a>
        </div>
    </body>
    </html>
    ";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        showError("Please enter your email address.");
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $otp = rand(100000, 999999);
        $otp_expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        $update = $conn->prepare("UPDATE users SET otp_code = ?, otp_expiry = ?, is_verified = 0 WHERE email = ?");
        $update->bind_param("sss", $otp, $otp_expiry, $email);
        $update->execute();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sijjunani321@gmail.com'; // Your Gmail
            $mail->Password = 'rnclparruhhkvzyn';       // App password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('sijjunani321@gmail.com', 'Nani Portal');
            $mail->addAddress($email);
            $mail->Subject = 'OTP for Password Reset';
            $mail->isHTML(true);
            $mail->Body = "<div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f5f8fa; border-radius: 8px;'>
            <h2 style='color: #007bff;'>Nani Portal - OTP Verification</h2>
            <p>Hello,</p>
            <p>We received a request to reset your password.</p>
            <p style='font-size: 18px; color: #000;'>Your OTP is:</p>
            <div style='font-size: 32px; font-weight: bold; background-color: #e9ecef; padding: 10px 20px; border-radius: 6px; display: inline-block; color: #007bff;'>$otp</div>
            <p style='margin-top: 20px;'>This OTP will expire in <strong>10 minutes</strong>.</p>
            <p>If you did not request this, please ignore this message.</p>
            <br>
            <p>Thanks,<br><strong>Nani Portal</strong></p>
            </div>
            ";


            $mail->send();

            $_SESSION['success'] = "OTP sent to your email.";
            $_SESSION['email'] = $email;
            header("Location: verify_otp.php");
            exit();
        } catch (Exception $e) {
            showError("Email could not be sent. Mailer Error: " . $mail->ErrorInfo);
        }
    } else {
        showError("Email not found. Please try again.");
    }
} else {
    showError("Invalid request.");
}
?>
