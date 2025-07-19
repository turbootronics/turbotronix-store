<?php
session_start();
include "db.php";

// Only allow logged-in users (admin or customer)
if (!isset($_SESSION['email'])) {
    die("❌ Access denied. Please log in first.");
}

// Get file name from URL
if (isset($_GET['file'])) {
    $filename = basename($_GET['file']);
    $filepath = "uploads/" . $filename;

    if (file_exists($filepath)) {
        // Log download into DB
        $email = $_SESSION['email'];
        
        // Find file ID from files table
        $query = $conn->query("SELECT id FROM files WHERE filename = '$filename' LIMIT 1");
        if ($query->num_rows > 0) {
            $file_data = $query->fetch_assoc();
            $file_id = $file_data['id'];
            $conn->query("INSERT INTO downloads (user_email, file_id) VALUES ('$email', '$file_id')");
        }

        // Force download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    } else {
        echo "❌ File not found.";
    }
} else {
    echo "❌ No file specified.";
}
?>
<?php
session_start();
include "db.php";

// Only allow logged-in users (admin or customer)
if (!isset($_SESSION['email'])) {
    die("❌ Access denied. Please log in first.");
}

// Get file name from URL
if (isset($_GET['file'])) {
    $filename = basename($_GET['file']);
    $filepath = "uploads/" . $filename;

    if (file_exists($filepath)) {
        // Log download into DB
        $email = $_SESSION['email'];
        
        // Find file ID from files table
        $query = $conn->query("SELECT id FROM files WHERE filename = '$filename' LIMIT 1");
        if ($query->num_rows > 0) {
            $file_data = $query->fetch_assoc();
            $file_id = $file_data['id'];
            $conn->query("INSERT INTO downloads (user_email, file_id) VALUES ('$email', '$file_id')");
        }

        // Force download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    } else {
        echo "❌ File not found.";
    }
} else {
    echo "❌ No file specified.";
}
?>
