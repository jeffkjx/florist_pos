<?php
// includes/db.php

$host = 'localhost';
$dbname = 'florist_pos';
$user = 'root';         // change if your DB user differs
$pass = '';             // change if you have a password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
