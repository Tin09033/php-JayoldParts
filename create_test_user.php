<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';

$db = new Database();

$email = 'user@example.com';
$existing = $db->fetchOne("SELECT id FROM users WHERE email = ?", [$email]);

if (!$existing) {
    $password = password_hash('password123', PASSWORD_DEFAULT);
    
    $db->insert("users", [
        'name' => 'Test User',
        'email' => $email,
        'phone' => '09123456789',
        'password' => $password,
        'role' => 'user',
        'status' => 'active'
    ]);
    
    echo "User created successfully!";
} else {
    echo "User already exists!";
}
