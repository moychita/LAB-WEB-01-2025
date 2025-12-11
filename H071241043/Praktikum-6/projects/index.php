<?php
require_once '../config.php';
require_login();
require_role(['super_admin','project_manager']);

$user_id = (int)$_SESSION['user_id'];
$role    = $_SESSION['role'];

if ($role === 'super_admin') {
  $sql = "SELECT p.*, u.username AS manager
          FROM projects p
          LEFT JOIN users u ON p.manager_id = u.id
          ORDER BY p.id DESC";
  $stmt = $conn->prepare($sql);
} else { // project_manager
  $sql = "SELECT p.*, u.username AS manager
          FROM projects p
          LEFT JOIN users u ON p.manager_id = u.id
          WHERE p.manager_id = ?
          ORDER BY p.id DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);
}
$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><title>Projects</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<?php include '../partials/header.php'; ?>
<div class="container mx-auto p-6 bg-white rounded shadow mt-6">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-2xl font-bold">Daftar Proyek</h2>
    <div class="flex space-x-2">
      <a href="../dashboard.php" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">
        ← Kembali
      </a>
      <a href="create.php" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
        + Tambah Proyek
      </a>
    </div>
  </div>
  <table class="w-full border">
    <thead class="bg-gray-100">
      <tr>
        <th class="border p-2">ID</th>
        <th class="border p-2">Nama Proyek</th>
        <th class="border p-2">Manager</th>
        <th class="border p-2">Mulai</th>
        <th class="border p-2">Selesai</th>
        <th class="border p-2">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php while($p = $res->fetch_assoc()): ?>
      <tr>
        <td class="border p-2 text-center"><?= $p['id'] ?></td>
        <td class="border p-2"><?= htmlspecialchars($p['nama_proyek']) ?></td>
        <td class="border p-2"><?= htmlspecialchars($p['manager'] ?? '-') ?></td>
        <td class="border p-2"><?= $p['tanggal_mulai'] ?? '-' ?></td>
        <td class="border p-2"><?= $p['tanggal_selesai'] ?? '-' ?></td>
        <td class="border p-2 text-center space-x-2">
          <a class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600" href="edit.php?id=<?= $p['id'] ?>">Edit</a>
          <a class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
             onclick="return confirm('Hapus proyek ini?')"
             href="delete.php?id=<?= $p['id'] ?>">Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include '../partials/footer.php'; ?>
</body>
</html>
