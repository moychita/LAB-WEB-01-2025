<?php
require_once '../config.php';
require_login();
require_role(['super_admin','project_manager']);

$user_id = (int)$_SESSION['user_id'];
$role    = $_SESSION['role'];

$project_id  = (int)($_POST['project_id'] ?? 0);
$nama_tugas  = trim($_POST['nama_tugas'] ?? '');
$deskripsi   = trim($_POST['deskripsi'] ?? '');
$status      = $_POST['status'] ?? 'belum';
$assigned_to = $_POST['assigned_to'] !== '' ? (int)$_POST['assigned_to'] : NULL;

// Validasi minimal
if ($project_id <= 0 || $nama_tugas === '') {
  die("Proyek dan nama tugas wajib diisi.");
}

// Jika PM, cek proyek miliknya
if ($role === 'project_manager') {
  $chk = $conn->prepare("SELECT id FROM projects WHERE id=? AND manager_id=?");
  $chk->bind_param("ii", $project_id, $user_id);
  $chk->execute();
  if ($chk->get_result()->num_rows === 0) {
    http_response_code(403); exit("Tidak berhak menambah task pada proyek ini.");
  }
}

// Simpan
$stmt = $conn->prepare("INSERT INTO tasks (project_id, assigned_to, nama_tugas, deskripsi, status) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iisss", $project_id, $assigned_to, $nama_tugas, $deskripsi, $status);
$stmt->execute();

header("Location: index.php");
exit;
