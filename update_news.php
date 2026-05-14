<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_role']) || ($_SESSION['user_role'] != 'teacher' && $_SESSION['user_role'] != 'admin')) {
    die('Доступ запрещён');
}

$id = $_POST['id'];
$title = $_POST['title'];
$preview = $_POST['preview'];
$content = $_POST['content'];
$img = $_POST['img'];

$sql = "UPDATE news SET title = ?, preview = ?, content = ?, img = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$title, $preview, $content, $img, $id]);

header('Location: news.php');
exit;
?>