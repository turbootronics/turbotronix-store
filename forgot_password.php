<?php
session_start();
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password | Nani Portal</title>
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
      background: linear-gradient(135deg, #000000, #1a1a1a, #444444);
      padding: 40px 30px;
      border-radius: 12px;
      width: 380px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
      color: #ffffff;
      transition: all 0.5s ease;
    }

    .container:hover {
      background: #ffffff;
      color: #000;
    }

    .container:hover h2 {
      color: #00b5ff;
    }

    h2 {
      text-align: center;
      margin-bottom: 24px;
      color: #00f7ff;
      transition: color 0.5s ease;
    }

    input[type="email"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 12px;
      border: 1px solid #555;
      border-radius: 6px;
      background-color: #1a1a1a;
      color: #fff;
      transition: all 0.5s ease;
      font-size: 14px;
    }

    .container:hover input[type="email"] {
      background-color: #f0f0f0;
      color: #000;
      border: 1px solid #ccc;
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

    .error, .success {
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
      text-align: center;
      font-weight: bold;
    }

    .error {
      background-color: #ff5c5c;
      color: #fff;
    }

    .success {
      background-color: #4caf50;
      color: #fff;
    }

    .container:hover .error, .container:hover .success {
      color: #000;
    }

    .links {
      margin-top: 10px;
      text-align: center;
    }

    .links a {
      color: #00f7ff;
      text-decoration: none;
      font-size: 14px;
      transition: color 0.3s ease;
    }

    .container:hover .links a {
      color: #0077cc;
    }

    .links a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Forgot Password</h2>

  <?php if ($error): ?>
    <div class="error"><?= $error ?></div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="success"><?= $success ?></div>
  <?php endif; ?>

  <form action="send_reset_otp.php" method="POST">
    <input type="email" name="email" placeholder="Enter your email" required>
    <input type="submit" value="Send OTP">
  </form>

  <div class="links">
    <a href="login.php">Back to Login</a>
  </div>
</div>

</body>
</html>
