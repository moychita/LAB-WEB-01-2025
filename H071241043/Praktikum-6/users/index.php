<?php
require_once '../config.php';
require_login();
require_role(['super_admin']);

$result = $conn->query("SELECT u.*, pm.username AS manager_name 
                        FROM users u 
                        LEFT JOIN users pm ON u.project_manager_id = pm.id
                        ORDER BY u.id ASC");

// $auth = $conn->query("SELECT * FROM projects WHERE id=" . (int)$_SESSION['user_id'])->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Users</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<?php include '../partials/header.php'; ?>

<div class="container mx-auto mt-8 bg-white p-6 rounded shadow">
  <h2 class="text-2xl font-bold mb-4">Kelola Pengguna</h2>
  <div class="flex space-x-2">
<a href="../dashboard.php" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">
        ← Kembali
      </a>
  <a href="create.php" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">+ Tambah User</a>
  </div>
  <table class="w-full mt-4 border">
    <thead class="bg-gray-200">
      <tr>
        <!-- <th class="border p-2">ID</th> -->
        <th class="border p-2">Username</th>
        <th class="border p-2">Role</th>
        <th class="border p-2">Project Manager</th>
        <th class="border p-2">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <!-- <td class="border p-2 text-center"><?= $row['id'] ?></td> -->
        <td class="border p-2"><?= htmlspecialchars($row['username']) ?></td>
        <td class="border p-2"><?= $row['role'] ?></td>
        <td class="border p-2"><?= $row['manager_name'] ?? '-' ?></td>
        <td class="border p-2 text-center">
          <a href="edit.php?id=<?= $row['id'] ?>" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Edit</a>
          

          <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus user ini?')" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include '../partials/footer.php'; ?>
</body>
</html>
