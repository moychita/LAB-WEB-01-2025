<?php
require_once '../config.php';
require_login();
require_role(['super_admin','project_manager']);

$user_id = (int)$_SESSION['user_id'];
$role    = $_SESSION['role'];

$id             = (int)($_POST['id'] ?? 0);
$nama_proyek    = trim($_POST['nama_proyek'] ?? '');
$deskripsi      = trim($_POST['deskripsi'] ?? '');
$tgl_mulai      = $_POST['tanggal_mulai'] ?? null;
$tgl_selesai    = $_POST['tanggal_selesai'] ?? null;
$manager_id_in  = $_POST['manager_id'] ?? null;

if ($id<=0 || $nama_proyek==='') die("Data tidak lengkap");

// Ambil proyek
$cek = $conn->prepare("SELECT manager_id FROM projects WHERE id=?");
$cek->bind_param("i", $id);
$cek->execute();
$cur = $cek->get_result()->fetch_assoc();
if (!$cur) die("Proyek tidak ditemukan");

// PM hanya boleh ubah proyek miliknya & tidak boleh ganti manager
if ($role === 'project_manager') {
  if ((int)$cur['manager_id'] !== $user_id) {
    http_response_code(403); exit("Tidak berhak mengubah proyek ini.");
  }
  $stmt = $conn->prepare("UPDATE projects SET nama_proyek=?, deskripsi=?, tanggal_mulai=?, tanggal_selesai=? WHERE id=?");
  $stmt->bind_param("ssssi", $nama_proyek, $deskripsi, $tgl_mulai, $tgl_selesai, $id);
  $stmt->execute();
} else {
  // super_admin: validasi manager
  if (empty($manager_id_in)) die("Manager wajib diisi.");
  $val = $conn->prepare("SELECT id FROM users WHERE id=? AND role='project_manager'");
  $val->bind_param("i", $manager_id_in);
  $val->execute();
  if ($val->get_result()->num_rows === 0) die("Manager tidak valid.");

  $stmt = $conn->prepare("UPDATE projects SET nama_proyek=?, deskripsi=?, tanggal_mulai=?, tanggal_selesai=?, manager_id=? WHERE id=?");
  $stmt->bind_param("ssssii", $nama_proyek, $deskripsi, $tgl_mulai, $tgl_selesai, $manager_id_in, $id);
  $stmt->execute();
}

header("Location: index.php");
exit;
