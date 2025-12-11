<?php
require_once '../config.php';
require_login();
require_role(['super_admin','project_manager','member']);

$user_id = (int)$_SESSION['user_id'];
$role    = $_SESSION['role'];

// Query dasar + filter per role
if ($role === 'super_admin') {
  $sql = "SELECT t.*, p.nama_proyek, u.username AS assignee
          FROM tasks t
          LEFT JOIN projects p ON t.project_id = p.id
          LEFT JOIN users u ON t.assigned_to = u.id
          ORDER BY t.id DESC";
  $stmt = $conn->prepare($sql);
} elseif ($role === 'project_manager') {
  $sql = "SELECT t.*, p.nama_proyek, u.username AS assignee
          FROM tasks t
          JOIN projects p ON t.project_id = p.id
          LEFT JOIN users u ON t.assigned_to = u.id
          WHERE p.manager_id = ?
          ORDER BY t.id DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);
} else { // member
  $sql = "SELECT t.*, p.nama_proyek, u.username AS assignee
          FROM tasks t
          LEFT JOIN projects p ON t.project_id = p.id
          LEFT JOIN users u ON t.assigned_to = u.id
          WHERE t.assigned_to = ?
          ORDER BY t.id DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><title>Tasks</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<?php include '../partials/header.php'; ?>

<div class="container mx-auto p-6 bg-white rounded shadow mt-6">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-2xl font-bold">Daftar Tugas</h2>
     <div class="flex space-x-2">
    <?php if (in_array($role, ['super_admin','project_manager'])): ?>
      <a href="../dashboard.php" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">
        ← Kembali
      </a>
      <a href="create.php" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">+ Tambah Task</a>
       </div>
    <?php endif; ?>
  </div>

  <table class="w-full border">
    <thead class="bg-gray-100">
      <tr>
        <th class="border p-2">ID</th>
        <th class="border p-2">Proyek</th>
        <th class="border p-2">Nama Tugas</th>
        <th class="border p-2">Assignee</th>
        <th class="border p-2">Status</th>
        <th class="border p-2">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php while($t = $result->fetch_assoc()): ?>
      <tr>
        <td class="border p-2 text-center"><?= $t['id'] ?></td>
        <td class="border p-2"><?= htmlspecialchars($t['nama_proyek'] ?? '-') ?></td>
        <td class="border p-2"><?= htmlspecialchars($t['nama_tugas']) ?></td>
        <td class="border p-2"><?= htmlspecialchars($t['assignee'] ?? '-') ?></td>
        <td class="border p-2 font-medium"><?= htmlspecialchars($t['status']) ?></td>
        <td class="border p-2 text-center space-x-2">
          <?php if ($role === 'member' && (int)$t['assigned_to'] === $user_id): ?>
            <a href="edit_status.php?id=<?= $t['id'] ?>" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Ubah Status</a>
          <?php endif; ?>

          <?php if (in_array($role, ['super_admin','project_manager'])): ?>
            <a href="edit.php?id=<?= $t['id'] ?>" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Edit</a>
            <a href="delete.php?id=<?= $t['id'] ?>" onclick="return confirm('Hapus task ini?')" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Hapus</a>
          <?php endif; ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include '../partials/footer.php'; ?>
</body>
</html>
