<?php
session_start();
include "db.php";

// Only admin can delete
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

if (isset($_GET['file'])) {
    $filename = $_GET['file'];

    // Delete file from folder
    $filepath = "uploads/" . $filename;
    if (file_exists($filepath)) {
        unlink($filepath); // delete file from server
    }

    // Delete file entry from database
    $stmt = $conn->prepare("DELETE FROM files WHERE filename = ?");
    $stmt->bind_param("s", $filename);
    $stmt->execute();
}

header("Location: admin.php");
exit();
