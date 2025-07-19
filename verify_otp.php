<?php
session_start();
include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredOtp = trim($_POST['otp']);
    $email = $_SESSION['email'] ?? '';

    if (empty($enteredOtp) || empty($email)) {
        $error = "Invalid request.";
    } else {
        $stmt = $conn->prepare("SELECT otp_code, otp_expiry FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($otp_code, $otp_expiry);
        $stmt->fetch();
        $stmt->close();

        if ($otp_code == $enteredOtp && strtotime($otp_expiry) > time()) {
            $_SESSION['otp_verified'] = true;
            header("Location: reset_password.php");
            exit();
        } else {
            $error = "Invalid or expired OTP.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
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
            background: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            width: 350px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.6);
        }

        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: none;
        }

        input[type="submit"] {
            background-color: #00b5ff;
            color: #000;
            font-weight: bold;
        }

        .error {
            color: #ff4c4c;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Enter OTP</h2>
        <form method="POST">
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <input type="submit" value="Verify OTP">
        </form>
        <?php if (!empty($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
