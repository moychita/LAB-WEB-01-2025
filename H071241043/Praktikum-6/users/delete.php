<?php
require_once '../config.php';
require_login();
require_role(['super_admin']);

$id = $_GET['id'];
$conn->query("DELETE FROM users WHERE id = $id");
header("Location: index.php");
exit;
