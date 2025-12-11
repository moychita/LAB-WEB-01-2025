<?php
require_once '../config.php';
require_login();
require_role(['super_admin','project_manager']);

$user_id = (int)$_SESSION['user_id'];
$role    = $_SESSION['role'];

$id          = (int)($_POST['id'] ?? 0);
$project_id  = (int)($_POST['project_id'] ?? 0);
$nama_tugas  = trim($_POST['nama_tugas'] ?? '');
$deskripsi   = trim($_POST['deskripsi'] ?? '');
$status      = $_POST['status'] ?? 'belum';
$assigned_to = $_POST['assigned_to'] !== '' ? (int)$_POST['assigned_to'] : NULL;

if ($id<=0 || $project_id<=0 || $nama_tugas==='') die("Data tidak lengkap");

// Ambil task + project lama untuk cek kepemilikan
$cek = $conn->prepare("SELECT t.id, p.manager_id FROM tasks t JOIN projects p ON t.project_id=p.id WHERE t.id=?");
$cek->bind_param("i", $id);
$cek->execute();
$old = $cek->get_result()->fetch_assoc();
if (!$old) die("Task tidak ditemukan");

// PM hanya boleh mengubah task dalam proyek yang ia kelola
if ($role === 'project_manager' && (int)$old['manager_id'] !== $user_id) {
  http_response_code(403); exit("Tidak berhak mengubah task ini.");
}

// Jika PM memindahkan task ke proyek lain, pastikan proyek target miliknya juga
if ($role === 'project_manager') {
  $chk = $conn->prepare("SELECT id FROM projects WHERE id=? AND manager_id=?");
  $chk->bind_param("ii", $project_id, $user_id);
  $chk->execute();
  if ($chk->get_result()->num_rows === 0) {
    http_response_code(403); exit("Tidak berhak memindahkan task ke proyek tersebut.");
  }
}

// Update
$stmt = $conn->prepare("UPDATE tasks SET project_id=?, assigned_to=?, nama_tugas=?, deskripsi=?, status=? WHERE id=?");
$stmt->bind_param("iisssi", $project_id, $assigned_to, $nama_tugas, $deskripsi, $status, $id);
$stmt->execute();

header("Location: index.php");
exit;
