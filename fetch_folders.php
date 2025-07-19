<?php
$path = $_GET['path'] ?? '';
$folders = [];

if (is_dir($path)) {
    foreach (scandir($path) as $dir) {
        if ($dir !== '.' && $dir !== '..' && is_dir("$path/$dir")) {
            $folders[] = $dir;
        }
    }
}

header('Content-Type: application/json');
echo json_encode($folders);
?>
