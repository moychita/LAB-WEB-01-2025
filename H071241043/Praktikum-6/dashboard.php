<?php
require_once 'config.php';
require_login();

$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
  <?php include 'partials/header.php'; ?>

  <div class="p-8">
    <h1 class="text-3xl font-bold mb-4">Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    <p>Anda login sebagai <span class="font-semibold text-blue-600"><?= $role ?></span>.</p>

    <div class="mt-6 space-x-4">
      <?php if ($role === 'super_admin'): ?>
        <a href="users/index.php" class="bg-blue-500 text-white py-2 px-4 rounded">Kelola Users</a>
      <?php endif; ?>

      <?php if (in_array($role, ['super_admin', 'project_manager'])): ?>
        <a href="projects/index.php" class="bg-green-500 text-white py-2 px-4 rounded">Kelola Proyek</a>
      <?php endif; ?>

      <?php if (in_array($role, ['super_admin', 'project_manager', 'member'])): ?>
        <a href="tasks/index.php" class="bg-yellow-500 text-white py-2 px-4 rounded">Lihat Tugas</a>
      <?php endif; ?>
    </div>
  </div>

  <?php include 'partials/footer.php'; ?>
</body>
</html>
