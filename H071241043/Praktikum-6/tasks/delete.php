<?php
require_once '../config.php';
require_login();
require_role(['super_admin','project_manager']);

$user_id = (int)$_SESSION['user_id'];
$role    = $_SESSION['role'];
$id      = (int)($_GET['id'] ?? 0);

// Ambil task + project untuk cek kepemilikan
$stmt = $conn->prepare("SELECT t.id, p.manager_id FROM tasks t JOIN projects p ON t.project_id = p.id WHERE t.id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();
if (!$task) die("Task tidak ditemukan");

// PM hanya boleh hapus pada proyeknya
if ($role === 'project_manager' && (int)$task['manager_id'] !== $user_id) {
  http_response_code(403); exit("Tidak berhak menghapus task ini.");
}

$del = $conn->prepare("DELETE FROM tasks WHERE id=?");
$del->bind_param("i", $id);
$del->execute();

header("Location: index.php");
exit;
