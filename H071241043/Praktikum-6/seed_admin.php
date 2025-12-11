<?php
require_once 'config.php';

$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$role = 'super_admin';

$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $role);
$stmt->execute();

echo "Super Admin berhasil dibuat! ID: " . $conn->insert_id;

$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$username = 'pm1';
$password = password_hash('pm123', PASSWORD_DEFAULT);
$role = 'project_manager';
$stmt->bind_param("sss", $username, $password, $role);
$stmt->execute();
echo "\nProject Manager berhasil dibuat! ID: " . $conn->insert_id;
