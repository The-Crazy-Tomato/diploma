<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_role']) || ($_SESSION['user_role'] != 'teacher' && $_SESSION['user_role'] != 'admin')) {
    die('Доступ запрещён');
}

$inventory_id = $_POST['inventory_id'];
$title = $_POST['title'];
$repeats = $_POST['repeats'];
$text = $_POST['text'];

$sql = "INSERT INTO exercises (inventory_id, title, repeats, text) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$inventory_id, $title, $repeats, $text]);
$exercise_id = $pdo->lastInsertId();

// Сохраняем картинки
if(isset($_POST['images'])) {
    $images = json_decode($_POST['images'], true);
    foreach($images as $img) {
        $stmt = $pdo->prepare("INSERT INTO exercise_images (exercise_id, img) VALUES (?, ?)");
        $stmt->execute([$exercise_id, $img['img']]);
    }
}

header('Location: catalog.php');
exit;
?>