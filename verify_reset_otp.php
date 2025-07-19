<?php
include "db.php";
$email = $_GET['email'] ?? '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = $_POST['otp'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT otp_code, otp_expiry FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($otp_code, $otp_expiry);
    $stmt->fetch();

    if ($otp_code == $entered_otp && strtotime($otp_expiry) > time()) {
        header("Location: reset_password.php?email=$email");
    } else {
        $error = "Invalid or expired OTP.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Verify OTP</title></head>
<body>
  <h2>Enter OTP sent to your email</h2>
  <form method="post">
    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
    <input type="text" name="otp" required placeholder="Enter OTP"><br>
    <button type="submit">Verify OTP</button>
  </form>
  <?= $error ? "<p style='color:red;'>$error</p>" : "" ?>
</body>
</html>
