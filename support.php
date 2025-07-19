<?php
include "db.php"; // Make sure this points to your DB connection file
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Support | Admin Contacts</title>
  <style>
    body {
      background: #0f2027;
      color: #fff;
      font-family: Arial, sans-serif;
      padding: 40px;
    }
    h2 {
      text-align: center;
      color: #00f7ff;
      margin-bottom: 30px;
    }
    table {
      width: 80%;
      margin: auto;
      border-collapse: collapse;
      background: #1e2a38;
      box-shadow: 0 0 10px cyan;
      border-radius: 10px;
      overflow: hidden;
    }
    th, td {
      padding: 14px;
      text-align: left;
      border-bottom: 1px solid #333;
    }
    th {
      background-color: #00f7ff;
      color: #000;
    }
    tr:hover {
      background-color: #2c5364;
    }
    .back {
      display: block;
      margin: 20px auto;
      text-align: center;
    }
    .back a {
      background: #00c9ff;
      color: #000;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
    }
    .back a:hover {
      background: #00b7e6;
    }
  </style>
</head>
<body>

<h2>Contact Support - Admin Details</h2>

<table>
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Joined On</th>
  </tr>

<?php
$result = $conn->query("SELECT name, email, phone, created_at FROM admins");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".htmlspecialchars($row['name'])."</td>
                <td>".htmlspecialchars($row['email'])."</td>
                <td>".htmlspecialchars($row['phone'])."</td>
                <td>".date('d M Y', strtotime($row['created_at']))."</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No admin data found.</td></tr>";
}
?>

</table>

<div class="back">
  <a href="index.html">← Back to homepage</a>
</div>

</body>
</html>
<?php
include "db.php"; // Make sure this points to your DB connection file
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Support | Admin Contacts</title>
  <style>
    body {
      background: #0f2027;
      color: #fff;
      font-family: Arial, sans-serif;
      padding: 40px;
    }
    h2 {
      text-align: center;
      color: #00f7ff;
      margin-bottom: 30px;
    }
    table {
      width: 80%;
      margin: auto;
      border-collapse: collapse;
      background: #1e2a38;
      box-shadow: 0 0 10px cyan;
      border-radius: 10px;
      overflow: hidden;
    }
    th, td {
      padding: 14px;
      text-align: left;
      border-bottom: 1px solid #333;
    }
    th {
      background-color: #00f7ff;
      color: #000;
    }
    tr:hover {
      background-color: #2c5364;
    }
    .back {
      display: block;
      margin: 20px auto;
      text-align: center;
    }
    .back a {
      background: #00c9ff;
      color: #000;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
    }
    .back a:hover {
      background: #00b7e6;
    }
  </style>
</head>
<body>

<h2>Contact Support - Admin Details</h2>

<table>
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Joined On</th>
  </tr>

<?php
$result = $conn->query("SELECT name, email, phone, created_at FROM admins");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".htmlspecialchars($row['name'])."</td>
                <td>".htmlspecialchars($row['email'])."</td>
                <td>".htmlspecialchars($row['phone'])."</td>
                <td>".date('d M Y', strtotime($row['created_at']))."</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No admin data found.</td></tr>";
}
?>

</table>

<div class="back">
  <a href="index.html">← Back to homepage</a>
</div>

</body>
</html>
