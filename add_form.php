<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_role']) || ($_SESSION['user_role'] != 'teacher' && $_SESSION['user_role'] != 'admin')) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Добавить</title>
    <style>
        body { background: #fef7e6; font-family: sans-serif; }
        form { background: white; max-width: 500px; margin: 50px auto; padding: 30px; border-radius: 30px; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border-radius: 20px; border: 1px solid #ddd; }
        button { background: #6b1a2a; color: white; padding: 10px 20px; border: none; border-radius: 30px; cursor: pointer; }
    </style>
</head>
<body>
<form action="add_item.php" method="POST">
    <h2>Добавить инвентарь</h2>
    <input type="text" name="name" placeholder="Название" required>
    <textarea name="description" placeholder="Описание"></textarea>
    <input type="text" name="main_img" placeholder="Путь к картинке (image/название.png)">
    <button type="submit">Сохранить</button>
    <a href="catalog_test.php">Отмена</a>
</form>
</body>
</html>