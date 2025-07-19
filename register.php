<?php
include "db.php";
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $year = $_POST['year'] ?? '';
    $branch = $_POST['branch'] ?? '';
    $section = $_POST['section'] ?? '';
    $role = 'user'; // Default role

    if ($name && $email && $password && $year && $branch && $section) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $stmt->close();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, year, branch, section, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $name, $email, $hashedPassword, $year, $branch, $section, $role);

            if ($stmt->execute()) {
                $success = "Registration successful. You can now <a href='login.php'>Login</a>";
            } else {
                $error = "Registration failed. Please try again.";
            }
            $stmt->close();
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | Nani Portal</title>
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
      width: 400px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
      transition: all 0.5s ease;
    }
    .container:hover {
      background: #ffffff;
      color: #000;
    }
    .container:hover h2 { color: #00b5ff; }
    h2 {
      text-align: center;
      margin-bottom: 24px;
      color: #00f7ff;
      transition: color 0.5s ease;
    }
    input, select {
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
    .container:hover input, .container:hover select {
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
    .container:hover label { color: #333; }
    input[type="submit"] {
      background-color: #00c9ff;
      color: #000;
      font-weight: bold;
      border: none;
      cursor: pointer;
      margin-top: 10px;
    }
    input[type="submit"]:hover { background-color: #00b7e6; }
    .error, .success {
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
      text-align: center;
      font-weight: bold;
    }
    .error { background-color: #ff5c5c; color: #fff; }
    .success { background-color: #22cc88; color: #fff; }
    .container:hover .error, .container:hover .success { color: #000; }
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
    .container:hover .links a { color: #0077cc; }
    .links a:hover { text-decoration: underline; }
  </style>
</head>
<body>

<div class="container">
  <h2>Register</h2>

  <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
  <?php if ($success): ?><div class="success"><?= $success ?></div><?php endif; ?>

  <form method="POST">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email Address" required>
    <input type="password" name="password" placeholder="Password" required>

    <select name="year" required>
      <option value="">Select Year</option>
      <option value="1styr">1st Year</option>
      <option value="2ndyr">2nd Year</option>
      <option value="3rdyr">3rd Year</option>
      <option value="4thyr">4th Year</option>
    </select>

    <select name="branch" required>
      <option value="">Select Branch</option>
      <option value="CSE">CSE</option>
      <option value="CSD">CSD</option>
      <option value="CSM">CSM</option>
      <option value="ECE">ECE</option>
      <option value="EEE">EEE</option>
      <option value="CIVIL">CIVIL</option>
      <option value="MECH">MECHANICAL</option>
    </select>

    <select name="section" required>
      <option value="">Select Section</option>
      <option value="A">A</option>
      <option value="B">B</option>
      <option value="C">C</option>
    </select>

    <input type="submit" value="Register">
  </form>

  <div class="links">
    <a href="login.php">Already registered? Login</a>
  </div>
</div>

</body>
</html>
