<?php
require_once '../config.php';
require_login();
require_role(['super_admin','project_manager']);

$user_id = (int)$_SESSION['user_id'];
$role    = $_SESSION['role'];
$id      = (int)($_GET['id'] ?? 0);

// Ambil task + project untuk cek kepemilikan
$stmt = $conn->prepare("SELECT t.*, p.nama_proyek, p.manager_id FROM tasks t JOIN projects p ON t.project_id = p.id WHERE t.id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();
if (!$task) die("Task tidak ditemukan");

// PM hanya boleh edit task di proyeknya
if ($role === 'project_manager' && (int)$task['manager_id'] !== $user_id) {
  http_response_code(403); exit("Tidak berhak mengedit task ini.");
}

// Proyek list sesuai role
if ($role === 'super_admin') {
  $proj = $conn->query("SELECT id, nama_proyek FROM projects ORDER BY nama_proyek");
} else {
  $qp = $conn->prepare("SELECT id, nama_proyek FROM projects WHERE manager_id=? ORDER BY nama_proyek");
  $qp->bind_param("i", $user_id);
  $qp->execute();
  $proj = $qp->get_result();
}

// Users list untuk assignee
$usr = $conn->query("SELECT id, username FROM users ORDER BY username");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><title>Edit Task</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<?php include '../partials/header.php'; ?>
<div class="container mx-auto p-6 bg-white rounded shadow mt-6">
  <h2 class="text-2xl font-bold mb-4">Edit Task</h2>
  <form action="update.php" method="POST" class="space-y-4">
    <input type="hidden" name="id" value="<?= $task['id'] ?>">
    <div>
      <label class="block mb-1">Proyek</label>
      <select name="project_id" class="border p-2 rounded w-full" required>
        <?php while($p = $proj->fetch_assoc()): ?>
          <option value="<?= $p['id'] ?>" <?= $p['id']==$task['project_id']?'selected':'' ?>>
            <?= htmlspecialchars($p['nama_proyek']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <div>
      <label class="block mb-1">Nama Tugas</label>
      <input type="text" name="nama_tugas" class="border p-2 rounded w-full" value="<?= htmlspecialchars($task['nama_tugas']) ?>" required>
    </div>
    <div>
      <label class="block mb-1">Deskripsi</label>
      <textarea name="deskripsi" class="border p-2 rounded w-full" rows="4"><?= htmlspecialchars($task['deskripsi']) ?></textarea>
    </div>
    <div>
      <label class="block mb-1">Assign ke</label>
      <select name="assigned_to" class="border p-2 rounded w-full">
        <option value="">-- (Kosong) --</option>
        <?php while($u = $usr->fetch_assoc()): ?>
          <option value="<?= $u['id'] ?>" <?= (int)$task['assigned_to']===(int)$u['id']?'selected':'' ?>>
            <?= htmlspecialchars($u['username']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <div>
      <label class="block mb-1">Status</label>
      <select name="status" class="border p-2 rounded w-full">
        <?php foreach(['belum','proses','selesai'] as $s): ?>
          <option value="<?= $s ?>" <?= $task['status']===$s?'selected':'' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Simpan</button>
  </form>
</div>
<?php include '../partials/footer.php'; ?>
</body>
</html>
