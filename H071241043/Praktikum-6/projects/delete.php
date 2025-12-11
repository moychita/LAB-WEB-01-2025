<?php
require_once '../config.php';
require_login();
require_role(['super_admin','project_manager']);

$user_id = (int)$_SESSION['user_id'];
$role    = $_SESSION['role'];
$id      = (int)($_GET['id'] ?? 0);

// Ambil proyek
$stmt = $conn->prepare("SELECT manager_id FROM projects WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$proj = $stmt->get_result()->fetch_assoc();
if (!$proj) die("Proyek tidak ditemukan");

// PM hanya boleh hapus proyek miliknya
if ($role === 'project_manager' && (int)$proj['manager_id'] !== $user_id) {
  http_response_code(403); exit("Tidak berhak menghapus proyek ini.");
}

$del = $conn->prepare("DELETE FROM projects WHERE id=?");
$del->bind_param("i", $id);
$del->execute();

header("Location: index.php");
exit;
