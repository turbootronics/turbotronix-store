<?php
session_start();
include "db.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $conn->prepare("SELECT id, name, password, year, branch, section, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $name, $hashedPassword, $year, $branch, $section, $role);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['year'] = $year;
                $_SESSION['branch'] = $branch;
                $_SESSION['section'] = $section;
                $_SESSION['role'] = $role;

                if ($role === 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Account not found.";
        }

        $stmt->close();
    } else {
        $error = "Please enter both email and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Nani Portal</title>
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

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 10px;
      border: 1px solid #555;
      border-radius: 6px;
      background-color: #1a1a1a;
      color: #fff;
      transition: all 0.5s ease;
      font-size: 14px;
    }

    .container:hover input[type="email"],
    .container:hover input[type="password"] {
      background-color: #f0f0f0;
      color: #000;
      border: 1px solid #ccc;
    }

    label {
      font-size: 13px;
      color: #ccc;
      display: block;
      margin-bottom: 10px;
      transition: color 0.5s ease;
    }

    .container:hover label {
      color: #333;
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

    .error {
      background-color: #ff5c5c;
      color: #fff;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
      text-align: center;
      font-weight: bold;
    }

    .container:hover .error {
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
  <h2>Login</h2>

  <?php if (!empty($error)): ?>
    <div class="error"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <input type="email" name="email" placeholder="Email Address" required>
    <input type="password" name="password" id="login-password" placeholder="Password" required>
    <label><input type="checkbox" onclick="togglePassword('login-password')"> Show Password</label>
    <input type="submit" value="Login">
  </form>

  <div class="links">
    <a href="register.php">Register</a> |
    <a href="forgot_password.php">Forgot Password?</a>
  </div>
  <div class="links">
    <a href="index.html">← Back to Home</a>
  </div>
</div>

<script>
  function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
  }
</script>

</body>
</html>
