<?php
// ==== Konfigurasi Database ====
$hostname = "localhost";
$username = "root";
$password = "";
$database = "db_manajemen_proyek";

// ==== Koneksi ====
$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// ==== Session ====
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==== Helper ====
function base_url($path = "") {
    $prefix = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    return $prefix . "/" . ltrim($path, "/");
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . base_url("auth/login.php"));
        exit;
    }
}

function require_role(array $roles) {
    if (!in_array($_SESSION['role'] ?? '', $roles)) {
        http_response_code(403);
        exit("Akses ditolak.");
    }
}
?>
