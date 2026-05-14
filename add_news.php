<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_role']) || ($_SESSION['user_role'] != 'teacher' && $_SESSION['user_role'] != 'admin')) {
    die('Доступ запрещён');
}

$title = $_POST['title'];
$preview = $_POST['preview'];
$content = $_POST['content'];
$img = $_POST['img'];
$date = date('Y-m-d');

$sql = "INSERT INTO news (title, preview, content, img, date) VALUES (?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$title, $preview, $content, $img, $date]);

header('Location: news.php');
exit;
?>