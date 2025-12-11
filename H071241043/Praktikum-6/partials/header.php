<?php
  // Cek halaman aktif
  $current_page = basename($_SERVER['PHP_SELF']);

  // Tentukan path logout berdasarkan posisi file
  if ($current_page === 'dashboard.php') {
      $logout_link = 'auth/logout.php';
  } else {
      $logout_link = '../auth/logout.php';
  }
?>

<nav class="bg-white shadow p-4 flex justify-between items-center">
  <h1 class="text-xl font-bold">Manajemen Proyek</h1>
  <div>
    <span class="mr-4 text-gray-700">
      <?= htmlspecialchars($_SESSION['username']) ?> (<?= $_SESSION['role'] ?>)
    </span>
    <a href="<?= $logout_link ?>" class="bg-red-500 text-white py-1 px-3 rounded">
      Logout
    </a>
  </div>
</nav>
