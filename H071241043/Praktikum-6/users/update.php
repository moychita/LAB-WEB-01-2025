<?php
require_once '../config.php';
require_login();
require_role(['super_admin']);

$id = $_POST['id'];
$username = trim($_POST['username']);
$password = $_POST['password'];
$role = $_POST['role'];
$project_manager_id = $_POST['project_manager_id'] ?? NULL;

// Validasi member wajib punya PM
if ($role === 'member' && empty($project_manager_id)) {
  die("Team Member wajib memiliki Project Manager.");
}

// Siapkan query dinamis: password hanya diupdate jika diisi
if (!empty($password)) {
  $password_hash = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $conn->prepare("UPDATE users SET username=?, password=?, role=?, project_manager_id=? WHERE id=?");
  $stmt->bind_param("sssii", $username, $password_hash, $role, $project_manager_id, $id);
} else {
  $stmt = $conn->prepare("UPDATE users SET username=?, role=?, project_manager_id=? WHERE id=?");
  $stmt->bind_param("ssii", $username, $role, $project_manager_id, $id);
}

$stmt->execute();
header("Location: index.php");
exit;
