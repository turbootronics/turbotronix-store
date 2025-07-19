<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

function getFolders($path) {
    $folders = [];
    if (is_dir($path)) {
        foreach (scandir($path) as $folder) {
            if ($folder !== '.' && $folder !== '..' && is_dir("$path/$folder")) {
                $folders[] = $folder;
            }
        }
    }
    return $folders;
}

$years = getFolders("uploads");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Upload Panel</title>
  <style>
    body {
      background: linear-gradient(-45deg, #0f0c29, #302b63, #24243e, #00b5ff);
      background-size: 400% 400%;
      animation: gradientBG 15s ease infinite;
      color: white;
      font-family: Arial, sans-serif;
      padding: 40px;
    }
    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    form {
      max-width: 600px;
      margin: auto;
      background-color: rgba(0,0,0,0.6);
      padding: 20px;
      border-radius: 10px;
    }
    select, input[type="file"], input[type="submit"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 5px;
      border: none;
    }
    input[type="submit"] {
      background: #00c9ff;
      font-weight: bold;
      color: black;
      cursor: pointer;
    }
  </style>
</head>
<body>

<h2 align="center">Admin Upload Panel</h2>
<form action="uploadfile.php" method="POST" enctype="multipart/form-data">
  <select name="year" id="year" required>
    <option value="">Select Year</option>
    <?php foreach ($years as $year): ?>
      <option value="<?= $year ?>"><?= $year ?></option>
    <?php endforeach; ?>
  </select>

  <select name="branch" id="branch" required></select>
  <select name="section" id="section" required></select>
  <select name="semester" id="semester" required>
    <option value="sem1">Sem 1</option>
    <option value="sem2">Sem 2</option>
  </select>
  <select name="subject" id="subject" required></select>

  <input type="file" name="file" required>
  <input type="submit" value="Upload File">
</form>

<script>
const base = "uploads";

document.getElementById('year').addEventListener('change', function() {
  const year = this.value;
  fetch(`fetch_folders.php?path=${base}/${year}`)
    .then(res => res.json())
    .then(data => {
      const branch = document.getElementById('branch');
      branch.innerHTML = '<option value="">Select Branch</option>';
      data.forEach(d => {
        branch.innerHTML += `<option value="${d}">${d}</option>`;
      });
    });
});

document.getElementById('branch').addEventListener('change', function() {
  const year = document.getElementById('year').value;
  const branch = this.value;
  fetch(`fetch_folders.php?path=${base}/${year}/${branch}`)
    .then(res => res.json())
    .then(data => {
      const section = document.getElementById('section');
      section.innerHTML = '<option value="">Select Section</option>';
      data.forEach(d => {
        section.innerHTML += `<option value="${d}">${d}</option>`;
      });
    });
});

document.getElementById('section').addEventListener('change', updateSubjects);
document.getElementById('semester').addEventListener('change', updateSubjects);

function updateSubjects() {
  const year = document.getElementById('year').value;
  const branch = document.getElementById('branch').value;
  const section = document.getElementById('section').value;
  const sem = document.getElementById('semester').value;

  if (year && branch && section && sem) {
    const subjectPath = `${base}/${year}/${branch}/${section}/${sem}/subjects`;
    fetch(`fetch_folders.php?path=${subjectPath}`)
      .then(res => res.json())
      .then(data => {
        const subject = document.getElementById('subject');
        subject.innerHTML = '<option value="">Select Subject</option>';
        data.forEach(s => {
          subject.innerHTML += `<option value="${s}">${s}</option>`;
        });
      });
  }
}
</script>
</body>
</html>
