<?php require_once '../config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
  <div class="bg-white p-8 rounded shadow-md w-96">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
    <form action="proses_login.php" method="POST">
      <input type="text" name="username" placeholder="Username" required class="w-full border p-2 mb-4 rounded">
      <input type="password" name="password" placeholder="Password" required class="w-full border p-2 mb-4 rounded">
      <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded w-full hover:bg-blue-600">Masuk</button>
    </form>
    <?php if (isset($_SESSION['error'])): ?>
      <p class="text-red-500 text-center mt-4"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
  </div>
</body>
</html>
