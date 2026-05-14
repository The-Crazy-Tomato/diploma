<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_role']) || ($_SESSION['user_role'] != 'teacher' && $_SESSION['user_role'] != 'admin')) {
    echo 'Доступ запрещён';
    exit;
}

$name = $_POST['name'];
$description = $_POST['description'];
$main_img = $_POST['main_img'];

if($name == '') {
    echo 'Имя не может быть пустым';
    exit;
}

$sql = "INSERT INTO inventory (name, description, main_img) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$name, $description, $main_img]);

header('Location: catalog.php');
?>