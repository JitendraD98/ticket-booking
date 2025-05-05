<?php
// Database connection using PDO with error handling and UTF-8 charset

$host = 'localhost';
$db   = 'ticket_booking';
$user = 'root';  // Change to your DB username
$pass = '';      // Change to your DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Associative array fetch
    PDO::ATTR_EMULATE_PREPARES   => false,                    // Use native prepared statements
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     exit('Database connection failed: ' . $e->getMessage());
}
?>
