<?php
require_once '../config.php';
require_login();
require_role(['super_admin']);

$username = trim($_POST['username']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];
$project_manager_id = $_POST['project_manager_id'] ?? null;

// jika role = project_manager → kolom project_manager_id harus NULL
if ($role === 'project_manager') {
    $project_manager_id = null;
}

// jika role = member → wajib pilih project manager
if ($role === 'member' && empty($project_manager_id)) {
    die("Team Member wajib memiliki Project Manager.");
}



// siapkan query
$stmt = $conn->prepare("INSERT INTO users (username, password, role, project_manager_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $username, $password, $role, $project_manager_id);
$stmt->execute();

header("Location: index.php");
exit;
