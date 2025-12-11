<?php
require_once '../config.php';
require_login();
require_role(['super_admin','project_manager']);

$user_id = (int)$_SESSION['user_id'];
$role    = $_SESSION['role'];

$nama_proyek    = trim($_POST['nama_proyek'] ?? '');
$deskripsi      = trim($_POST['deskripsi'] ?? '');
$tgl_mulai      = $_POST['tanggal_mulai'] ?? null;
$tgl_selesai    = $_POST['tanggal_selesai'] ?? null;
$manager_id_in  = $_POST['manager_id'] ?? null;

if ($nama_proyek === '') die("Nama proyek wajib diisi.");

// tentukan manager_id
if ($role === 'project_manager') {
  $manager_id = $user_id;
} else {
  // super_admin wajib pilih PM yang valid
  if (empty($manager_id_in)) die("Harus memilih Project Manager.");
  $stmt = $conn->prepare("SELECT id FROM users WHERE id=? AND role='project_manager'");
  $stmt->bind_param("i", $manager_id_in);
  $stmt->execute();
  if ($stmt->get_result()->num_rows === 0) die("Manager tidak valid.");
  $manager_id = (int)$manager_id_in;
}

// simpan
$stmt = $conn->prepare(
  "INSERT INTO projects (nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai, manager_id)
   VALUES (?, ?, ?, ?, ?)"
);
$stmt->bind_param("ssssi", $nama_proyek, $deskripsi, $tgl_mulai, $tgl_selesai, $manager_id);
$stmt->execute();

header("Location: index.php");
exit;
