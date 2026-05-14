<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_role']) || ($_SESSION['user_role'] != 'teacher' && $_SESSION['user_role'] != 'admin')) {
    echo 'Доступ запрещён';
    exit;
}

$id = $_GET['id'];
$sql = "DELETE FROM inventory WHERE id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

header('Location: catalog.php');
?>