<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_role']) || ($_SESSION['user_role'] != 'teacher' && $_SESSION['user_role'] != 'admin')) {
    echo 'Доступ запрещён';
    exit;
}

$id = $_POST['id'];
$name = $_POST['name'];
$description = $_POST['description'];
$main_img = $_POST['main_img'];

$sql = "UPDATE inventory SET name=?, description=?, main_img=? WHERE id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$name, $description, $main_img, $id]);

header('Location: catalog.php');
?>