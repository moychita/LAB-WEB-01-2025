<?php
require_once '../config.php';
require_login();
require_role(['super_admin','project_manager']);

$user_id = (int)$_SESSION['user_id'];
$role    = $_SESSION['role'];
$id      = (int)($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM projects WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();
if (!$project) die("Proyek tidak ditemukan");

// PM hanya bisa edit proyek miliknya
if ($role === 'project_manager' && (int)$project['manager_id'] !== $user_id) {
  http_response_code(403); exit("Tidak berhak mengedit proyek ini.");
}

$managers = null;
if ($role === 'super_admin') {
  $managers = $conn->query("SELECT id, username FROM users WHERE role='project_manager' ORDER BY username");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><title>Edit Proyek</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<?php include '../partials/header.php'; ?>
<div class="container mx-auto p-6 bg-white rounded shadow mt-6">
  <h2 class="text-2xl font-bold mb-4">Edit Proyek</h2>
  <form action="update.php" method="POST" class="space-y-4">
    <input type="hidden" name="id" value="<?= $project['id'] ?>">
    <div>
      <label class="block mb-1">Nama Proyek</label>
      <input type="text" name="nama_proyek" value="<?= htmlspecialchars($project['nama_proyek']) ?>" class="border p-2 rounded w-full" required>
    </div>
    <div>
      <label class="block mb-1">Deskripsi</label>
      <textarea name="deskripsi" class="border p-2 rounded w-full" rows="4"><?= htmlspecialchars($project['deskripsi']) ?></textarea>
    </div>
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block mb-1">Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" value="<?= $project['tanggal_mulai'] ?>" class="border p-2 rounded w-full">
      </div>
      <div>
        <label class="block mb-1">Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" value="<?= $project['tanggal_selesai'] ?>" class="border p-2 rounded w-full">
      </div>
    </div>

    <?php if ($role === 'super_admin'): ?>
      <div>
        <label class="block mb-1">Project Manager</label>
        <select name="manager_id" class="border p-2 rounded w-full" required>
          <?php while($m = $managers->fetch_assoc()): ?>
            <option value="<?= $m['id'] ?>" <?= (int)$m['id']===(int)$project['manager_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($m['username']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
    <?php endif; ?>

    <button class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Simpan</button>
  </form>
</div>
<?php include '../partials/footer.php'; ?>
</body>
</html>
