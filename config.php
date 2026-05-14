<?php
$host = 'localhost';
$dbname = 'uchiha2e_zpt_sp';
$user = 'uchiha2e_zpt_sp';
$pass = 'bZ!dYXkGRKe0';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
?>