<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_role']) || ($_SESSION['user_role'] != 'teacher' && $_SESSION['user_role'] != 'admin')) {
    die('Доступ запрещён');
}

$id = $_POST['id'];
$title = $_POST['title'];
$repeats = $_POST['repeats'];
$text = $_POST['text'];

$sql = "UPDATE exercises SET title = ?, repeats = ?, text = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$title, $repeats, $text, $id]);

// Удаляем старые картинки
$stmt = $pdo->prepare("DELETE FROM exercise_images WHERE exercise_id = ?");
$stmt->execute([$id]);

// Добавляем новые
if(isset($_POST['images'])) {
    $images = json_decode($_POST['images'], true);
    foreach($images as $img) {
        $stmt = $pdo->prepare("INSERT INTO exercise_images (exercise_id, img) VALUES (?, ?)");
        $stmt->execute([$id, $img['img']]);
    }
}

header('Location: catalog.php');
exit;
?>