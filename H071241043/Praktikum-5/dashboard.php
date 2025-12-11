<?php
session_start();
require 'data.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$loggedInUser = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { 
            font-family: sans-serif; 
            margin: 40px; }
        a { 
            color: #dc3545; 
            text-decoration: none; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; }
        th, td { 
            padding: 12px; 
            border: 1px solid #ddd; 
            text-align: left; }
        th { 
            background-color: #f2f2f2; }
        .user-data { 
            margin-top: 20px; }
        .user-data td:first-child { 
            font-weight: bold; 
            width: 150px; }
    </style>
</head>
<body>

    <h1>Selamat Datang, <?php echo htmlspecialchars($loggedInUser['name']); ?>!</h1>
    <a href="logout.php">Logout</a>
    <hr>

    <?php if ($loggedInUser['username'] === 'adminxxx'): ?>
        <h2>Data Semua Pengguna</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <h2>Data Anda</h2>
        <table class="user-data">
            <tr>
                <td>Nama</td>
                <td><?php echo htmlspecialchars($loggedInUser['name']); ?></td>
            </tr>
            <tr>
                <td>Username</td>
                <td><?php echo htmlspecialchars($loggedInUser['username']); ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo htmlspecialchars($loggedInUser['email']); ?></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td><?php echo htmlspecialchars($loggedInUser['gender']); ?></td>
            </tr>
            <tr>
                <td>Fakultas</td>
                <td><?php echo htmlspecialchars($loggedInUser['faculty']); ?></td>
            </tr>
             <tr>
                <td>Angkatan</td>
                <td><?php echo htmlspecialchars($loggedInUser['batch']); ?></td>
            </tr>
        </table>
    <?php endif; ?>

</body>
</html>