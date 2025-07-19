<?php
session_start();

// Simulate admin
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'admin';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";

// Handle file deletion
if (isset($_GET['delete'])) {
    $fileToDelete = $_GET['delete'];
    if (file_exists($fileToDelete)) {
        unlink($fileToDelete);
        $message = "🗑️ File deleted successfully.";
    }
}

// Show uploaded subjects
function showUploadedSubjects($base = 'uploads') {
    if (!is_dir($base)) {
        echo "<p>No uploads found.</p>";
        return;
    }

    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
    $subjectsShown = [];

    foreach ($rii as $file) {
        if ($file->isDir()) continue;

        $path = $file->getPath();
        $parts = explode('/', $path);
        $count = count($parts);

        if ($count >= 6 && $parts[$count - 2] === 'subjects') {
            $subject = $parts[$count - 1];

            if (!isset($subjectsShown[$subject])) {
                $subjectsShown[$subject] = [];
            }
            $subjectsShown[$subject][] = $file->getFilename();
        }
    }

    if (empty($subjectsShown)) {
        echo "<p>No uploaded subjects found.</p>";
    } else {
        foreach ($subjectsShown as $subject => $files) {
            echo "<div class='subject-box'>";
            echo "<h3>$subject</h3>";
            echo "<ul>";
            foreach ($files as $file) {
                $pattern = glob("$base/*/*/*/*/subjects/$subject/$file");
                $filePath = $pattern[0] ?? '';
                echo "<li>$file 
                    <a href='?delete=$filePath' onclick='return confirm(\"Delete $file?\")' class='delete-btn'>Delete</a>
                </li>";
            }
            echo "</ul>";
            echo "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Upload Portal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef1f7;
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .message {
            text-align: center;
            margin: 15px;
            font-weight: bold;
            color: green;
        }

        .subject-box {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 0 8px rgba(0,0,0,0.08);
        }

        .subject-box h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .subject-box ul {
            padding-left: 20px;
        }

        .subject-box li {
            margin-bottom: 5px;
        }

        .delete-btn {
            color: red;
            margin-left: 15px;
            font-size: 14px;
        }

        .btns {
            text-align: center;
            margin-top: 20px;
        }

        .btns a {
            background: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 8px 16px;
            margin: 0 10px;
            border-radius: 6px;
            display: inline-block;
        }

        .btns a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<h1>Admin Upload Portal</h1>

<?php if ($message): ?>
    <p class="message"><?= $message ?></p>
<?php endif; ?>

<h2 style="text-align:center;">Uploaded Subjects</h2>
<?php showUploadedSubjects(); ?>

<div class="btns">
    <a href="admin.php">🔙 Back to Upload</a>
    <a href="index.html">🏠 Homepage</a>
</div>

</body>
</html>
