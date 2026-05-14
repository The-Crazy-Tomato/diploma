<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_role']) || ($_SESSION['user_role'] != 'teacher' && $_SESSION['user_role'] != 'admin')) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM inventory WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Редактировать</title>
    <style>
        body { background: #fef7e6; font-family: sans-serif; }
        form { background: white; max-width: 500px; margin: 50px auto; padding: 30px; border-radius: 30px; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border-radius: 20px; border: 1px solid #ddd; }
        button { background: #6b1a2a; color: white; padding: 10px 20px; border: none; border-radius: 30px; cursor: pointer; }
    </style>
</head>
<body>
<form action="update_item.php" method="POST">
    <h2>Редактировать инвентарь</h2>
    <input type="hidden" name="id" value="<?= $item['id'] ?>">
    <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>
    <textarea name="description"><?= htmlspecialchars($item['description']) ?></textarea>
    <input type="text" name="main_img" value="<?= htmlspecialchars($item['main_img']) ?>">
    <button type="submit">Сохранить</button>
    <a href="catalog_test.php">Отмена</a>
</form>
</body>
</html>