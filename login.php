<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && md5($password) === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_login'] = $user['login'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['full_name'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'неверный логин или пароль';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>вход</title>
    <style>
        body { background: #fef7e6; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .form { background: white; padding: 2rem; border-radius: 32px; width: 300px; }
        input { width: 100%; padding: 8px; margin: 10px 0; border-radius: 30px; border: 1px solid #ccc; }
        button { background: #6b1a2a; color: white; border: none; padding: 10px; width: 100%; border-radius: 30px; cursor: pointer; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="form">
        <h2>вход для учителя</h2>
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="login" placeholder="логин" required>
            <input type="password" name="password" placeholder="пароль" required>
            <button type="submit">войти</button>
        </form>
    </div>
</body>
</html>