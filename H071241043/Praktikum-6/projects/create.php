<?php
require_once '../config.php';
require_login();
require_role(['super_admin','project_manager']);

$role = $_SESSION['role'];

$managers = null;
if ($role === 'super_admin') {
  $managers = $conn->query("SELECT id, username FROM users WHERE role='project_manager' ORDER BY username");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><title>Tambah Proyek</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<?php include '../partials/header.php'; ?>
<div class="container mx-auto p-6 bg-white rounded shadow mt-6">
  <h2 class="text-2xl font-bold mb-4">Tambah Proyek</h2>
  <form action="store.php" method="POST" class="space-y-4">
    <div>
      <label class="block mb-1">Nama Proyek</label>
      <input type="text" name="nama_proyek" class="border p-2 rounded w-full" required>
    </div>
    <div>
      <label class="block mb-1">Deskripsi</label>
      <textarea name="deskripsi" class="border p-2 rounded w-full" rows="4"></textarea>
    </div>
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block mb-1">Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" class="border p-2 rounded w-full">
      </div>
      <div>
        <label class="block mb-1">Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" class="border p-2 rounded w-full">
      </div>
    </div>

    <?php if ($role === 'super_admin'): ?>
      <div>
        <label class="block mb-1">Project Manager</label>
        <select name="manager_id" class="border p-2 rounded w-full" required>
          <option value="">-- Pilih Manager --</option>
          <?php while($m = $managers->fetch_assoc()): ?>
            <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['username']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
    <?php endif; ?>

    <button class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Simpan</button>
  </form>
</div>
<?php include '../partials/footer.php'; ?>
</body>
</html>
