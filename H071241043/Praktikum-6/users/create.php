<?php
require_once '../config.php';
require_login();
require_role(['super_admin']);

// Ambil daftar project manager untuk dropdown
$pm_result = $conn->query("SELECT id, username FROM users WHERE role = 'project_manager'");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah User</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // JavaScript untuk menampilkan dropdown PM hanya jika role = member
    function togglePM() {
      const role = document.getElementById('role').value;
      const pmField = document.getElementById('pm_field');
      pmField.style.display = (role === 'member') ? 'block' : 'none';
    }
    window.onload = togglePM;
  </script>
</head>
<body class="bg-gray-100">
  <?php include '../partials/header.php'; ?>

  <div class="container mx-auto mt-10 bg-white p-8 rounded shadow max-w-lg">
    <h2 class="text-2xl font-bold mb-6 text-center">Tambah Pengguna Baru</h2>

    <!-- tampilkan pesan error/success -->
    <?php if (isset($_SESSION['error'])): ?>
      <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
      </div>
    <?php elseif (isset($_SESSION['success'])): ?>
      <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
      </div>
    <?php endif; ?>

    <form action="store.php" method="POST" class="space-y-4">
      <!-- Username -->
      <div>
        <label class="block font-semibold mb-1">Username:</label>
        <input type="text" name="username" class="w-full border rounded p-2" required>
      </div>

      <!-- Password -->
      <div>
        <label class="block font-semibold mb-1">Password:</label>
        <input type="password" name="password" class="w-full border rounded p-2" required>
      </div>

      <!-- Role -->
      <div>
        <label class="block font-semibold mb-1">Role:</label>
        <select name="role" id="role" onchange="togglePM()" class="w-full border rounded p-2" required>
          <option value="" disabled selected>Pilih Role</option>
          <option value="super_admin">Super Admin</option>
          <option value="project_manager">Project Manager</option>
          <option value="member">Member</option>
        </select>
      </div>

      <!-- Project Manager (muncul jika role=member) -->
      <div id="pm_field" style="display:none;">
        <label class="block font-semibold mb-1">Pilih Project Manager:</label>
        <select name="project_manager_id" class="w-full border rounded p-2">
          <option value="">-- Pilih Project Manager --</option>
          <?php while ($pm = $pm_result->fetch_assoc()): ?>
            <option value="<?= $pm['id']; ?>"><?= htmlspecialchars($pm['username']); ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <!-- Tombol -->
      <div class="flex justify-between mt-6">
        <a href="index.php" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">← Kembali</a>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
          Simpan
        </button>
      </div>
    </form>
  </div>

  <?php include '../partials/footer.php'; ?>
</body>
</html>
