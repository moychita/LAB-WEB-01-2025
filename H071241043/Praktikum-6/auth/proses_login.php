<?php
require_once '../config.php';
session_start();

$username = trim($_POST['username']);
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();


while ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: ../dashboard.php");
        exit;
    }
}

// Jika tidak ada satupun yang cocok
$_SESSION['error'] = "Username atau password salah.";
header("Location: login.php");
exit;
