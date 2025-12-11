<?php
require_once '../config.php';
require_login();
require_role(['super_admin','project_manager']);

$user_id = (int)$_SESSION['user_id'];
$role    = $_SESSION['role'];

// List project sesuai role
if ($role === 'super_admin') {
  $proj = $conn->query("SELECT id, nama_proyek FROM projects ORDER BY nama_proyek");
} else {
  $stmt = $conn->prepare("SELECT id, nama_proyek FROM projects WHERE manager_id = ? ORDER BY nama_proyek");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $proj = $stmt->get_result();
}

// List users untuk assignee (bisa semua user; kalau mau khusus member, WHERE role='member')
$usr = $conn->query("SELECT id, username FROM users WHERE role = 'member' ORDER BY username");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><title>Tambah Task</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<?php include '../partials/header.php'; ?>
<div class="container mx-auto p-6 bg-white rounded shadow mt-6">
  <h2 class="text-2xl font-bold mb-4">Tambah Task</h2>
  <form action="store.php" method="POST" class="space-y-4">
    <div>
      <label class="block mb-1">Proyek</label>
      <select name="project_id" class="border p-2 rounded w-full" required>
        <option value="">-- Pilih --</option>
        <?php while($p = $proj->fetch_assoc()): ?>
          <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nama_proyek']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div>
      <label class="block mb-1">Nama Tugas</label>
      <input type="text" name="nama_tugas" class="border p-2 rounded w-full" required>
    </div>
    <div>
      <label class="block mb-1">Deskripsi</label>
      <textarea name="deskripsi" class="border p-2 rounded w-full" rows="4"></textarea>
    </div>
    <div>
      <label class="block mb-1">Assign ke</label>
      <select name="assigned_to" class="border p-2 rounded w-full">
        <option value="">-- (Kosong) --</option>
        <?php while($u = $usr->fetch_assoc()): ?>
          <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div>
      <label class="block mb-1">Status</label>
      <select name="status" class="border p-2 rounded w-full">
        <option value="belum">belum</option>
        <option value="proses">proses</option>
        <option value="selesai">selesai</option>
      </select>
    </div>
    <button class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Simpan</button>
  </form>
</div>
<?php include '../partials/footer.php'; ?>
</body>
</html>
