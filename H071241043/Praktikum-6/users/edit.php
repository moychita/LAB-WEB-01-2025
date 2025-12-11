<?php
require_once '../config.php';
require_login();
require_role(['super_admin']);

$id = $_GET['id'] ?? 0;

// Ambil data user yang mau diedit
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die("User tidak ditemukan!");
}

// Ambil daftar Project Manager untuk dropdown jika role = member
$pm_result = $conn->query("SELECT id, username FROM users WHERE role = 'project_manager' AND id != $id");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<?php include '../partials/header.php'; ?>

<div class="container mx-auto mt-8 bg-white p-6 rounded shadow">
  <h2 class="text-2xl font-bold mb-4">Edit Pengguna</h2>
  <form action="update.php" method="POST" class="space-y-4">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">

    <div>
      <label>Username:</label>
      <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required class="w-full border p-2 rounded">
    </div>

    <div>
      <label>Password (biarkan kosong jika tidak diubah):</label>
      <input type="password" name="password" class="w-full border p-2 rounded">
    </div>

    <div>
      <label>Role:</label>
      <select name="role" id="role" class="w-full border p-2 rounded" onchange="togglePM()">
        <option value="project_manager" <?= $user['role']=='project_manager'?'selected':'' ?>>Project Manager</option>
        <option value="member" <?= $user['role']=='member'?'selected':'' ?>>Team Member</option>
      </select>
    </div>

    <div id="pm-field" class="<?= $user['role']=='member' ? '' : 'hidden' ?>">
      <label>Pilih Project Manager:</label>
      <select name="project_manager_id" class="w-full border p-2 rounded">
        <option value="">-- Pilih --</option>
        <?php while ($pm = $pm_result->fetch_assoc()): ?>
          <option value="<?= $pm['id'] ?>" <?= $pm['id']==$user['project_manager_id'] ? 'selected' : '' ?>>
            <?= $pm['username'] ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Simpan Perubahan</button>
  </form>
</div>

<script>
function togglePM() {
  const role = document.getElementById('role').value;
  document.getElementById('pm-field').classList.toggle('hidden', role !== 'member');
}
</script>

<?php include '../partials/footer.php'; ?>
</body>
</html>
