<?php
require_once '../config.php';
require_login();
require_role(['member','super_admin','project_manager']); // member wajib bisa

$user_id = (int)$_SESSION['user_id'];
$role    = $_SESSION['role'];
$id      = (int)($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT id, nama_tugas, status, assigned_to FROM tasks WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();
if (!$task) die("Task tidak ditemukan");

// Member hanya boleh task miliknya
if ($role === 'member' && (int)$task['assigned_to'] !== $user_id) {
  http_response_code(403); exit("Tidak berhak mengubah status task ini.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><title>Ubah Status</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<?php include '../partials/header.php'; ?>
<div class="container mx-auto p-6 bg-white rounded shadow mt-6">
  <h2 class="text-2xl font-bold mb-4">Ubah Status: <?= htmlspecialchars($task['nama_tugas']) ?></h2>
  <form action="update_status.php" method="POST" class="space-y-4">
    <input type="hidden" name="id" value="<?= $task['id'] ?>">
    <select name="status" class="border p-2 rounded w-full">
      <?php foreach(['belum','proses','selesai'] as $s): ?>
        <option value="<?= $s ?>" <?= $task['status']===$s?'selected':'' ?>><?= $s ?></option>
      <?php endforeach; ?>
    </select>
    <button class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Simpan</button>
  </form>
</div>
<?php include '../partials/footer.php'; ?>
</body>
</html>
