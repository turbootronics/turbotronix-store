<?php
session_start();
include "db.php";

if (!isset($_SESSION['email'])) {
    header("Location: forgot_password.php");
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($new_password) || empty($confirm_password)) {
        $error = "Please fill out both fields.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $email = $_SESSION['email'];

        $stmt = $conn->prepare("UPDATE users SET password = ?, otp_code = NULL, otp_expiry = NULL, is_verified = 1 WHERE email = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);

        if ($stmt->execute()) {
            $success = "Password successfully reset. You can now <a href='login.php'>Login</a>.";
            session_destroy();
        } else {
            $error = "Something went wrong. Please try again.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password | Nani Portal</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

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

    .container {
      background: rgba(0, 0, 0, 0.7);
      padding: 40px 30px;
      border-radius: 12px;
      width: 380px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #00f7ff;
    }

    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 10px;
      border: 1px solid #555;
      border-radius: 6px;
      background-color: #1a1a1a;
      color: #fff;
      font-size: 14px;
    }

    label {
      font-size: 13px;
      color: #ccc;
      display: block;
      margin-bottom: 6px;
    }

    input[type="submit"] {
      width: 100%;
      padding: 12px;
      background-color: #00c9ff;
      color: #000;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 10px;
      transition: 0.3s;
    }

    input[type="submit"]:hover {
      background-color: #00b7e6;
    }

    .msg {
      padding: 10px;
      margin-bottom: 15px;
      text-align: center;
      border-radius: 6px;
      font-weight: bold;
    }

    .error { background-color: #ff5c5c; color: #fff; }
    .success { background-color: #2ecc71; color: #fff; }

    a {
      color: #00f7ff;
      text-decoration: none;
      font-size: 14px;
    }

    a:hover {
      text-decoration: underline;
    }

  </style>
</head>
<body>

<div class="container">
  <h2>Reset Password</h2>

  <?php if ($error): ?>
    <div class="msg error"><?= $error ?></div>
  <?php elseif ($success): ?>
    <div class="msg success"><?= $success ?></div>
  <?php endif; ?>

  <?php if (!$success): ?>
  <form method="POST" action="">
    <label for="new_password">New Password</label>
    <input type="password" name="new_password" required>

    <label for="confirm_password">Confirm Password</label>
    <input type="password" name="confirm_password" required>

    <input type="submit" value="Reset Password">
  </form>
  <?php endif; ?>

</div>

</body>
</html>
