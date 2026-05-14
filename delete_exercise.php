<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_role']) || ($_SESSION['user_role'] != 'teacher' && $_SESSION['user_role'] != 'admin')) {
    die('Доступ запрещён');
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM exercise_images WHERE exercise_id = ?");
$stmt->execute([$id]);

$stmt = $pdo->prepare("DELETE FROM exercises WHERE id = ?");
$stmt->execute([$id]);

header('Location: catalog.php');
exit;
?>