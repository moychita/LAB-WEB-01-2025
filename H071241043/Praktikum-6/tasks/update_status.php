<?php
require_once '../config.php';
require_login();
require_role(['member','super_admin','project_manager']);

$user_id = (int)$_SESSION['user_id'];
$role    = $_SESSION['role'];

$id     = (int)($_POST['id'] ?? 0);
$status = $_POST['status'] ?? 'belum';

// Ambil task
$stmt = $conn->prepare("SELECT assigned_to FROM tasks WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();
if (!$task) die("Task tidak ditemukan");

// Member hanya boleh ubah task miliknya
if ($role === 'member' && (int)$task['assigned_to'] !== $user_id) {
  http_response_code(403); exit("Tidak berhak mengubah status task ini.");
}

// Update status saja
$up = $conn->prepare("UPDATE tasks SET status=? WHERE id=?");
$up->bind_param("si", $status, $id);
$up->execute();

header("Location: index.php");
exit;
