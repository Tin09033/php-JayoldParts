<?php
require_once __DIR__ . '/includes/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$password = password_hash('jayson123', PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE admins SET password = ? WHERE email = 'admin@jayoldparts.com'");
$stmt->bind_param("s", $password);
$stmt->execute();

echo "Password updated successfully!";
