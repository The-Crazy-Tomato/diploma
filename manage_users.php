<?php
session_start();
require_once 'config.php';

// Проверяем, админ ли пользователь
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
if (!$isAdmin) {
    header('Location: catalog.php');
    exit;
}

// Обработка действий
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $login = trim($_POST['login']);
        $full_name = trim($_POST['full_name']);
        $password = md5(trim($_POST['password']));
        $role = 'teacher';
        
        $stmt = $pdo->prepare("INSERT INTO users (login, password, role, full_name) VALUES (?, ?, ?, ?)");
        $stmt->execute([$login, $password, $role, $full_name]);
        $message = 'Учитель добавлен';
        
    } elseif ($action === 'delete' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
        $stmt->execute([$id]);
        $message = 'Учитель удалён';
        
    } elseif ($action === 'reset_password' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $new_pass = md5('teacher123');
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$new_pass, $id]);
        $message = 'Пароль сброшен на teacher123';
    }
}

// Получаем список всех учителей (не админов)
$stmt = $pdo->query("SELECT id, login, full_name, role FROM users WHERE role != 'admin' ORDER BY id");
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление учителями — Спортивный клуб ЗПТ</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #fef7e6;
            color: #2d2d2d;
            line-height: 1.5;
        }
        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        .card {
            background: white;
            border-radius: 32px;
            padding: 2rem;
            box-shadow: 0 20px 35px -12px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        h1, h2 {
            font-family: 'Montserrat', sans-serif;
            color: #6b1a2a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .btn {
            padding: 6px 12px;
            border-radius: 30px;
            border: none;
            cursor: pointer;
            font-size: 0.8rem;
        }
        .btn-danger { background: #b33a4a; color: white; }
        .btn-outline { background: transparent; border: 1px solid #6b1a2a; color: #6b1a2a; }
        .btn-primary { background: #6b1a2a; color: white; }
        input, select {
            padding: 8px 12px;
            border-radius: 30px;
            border: 1px solid #ddd;
            width: 100%;
            margin-bottom: 1rem;
        }
        form div { margin-bottom: 0.5rem; }
        .message {
            background: #e0f0e0;
            padding: 10px;
            border-radius: 20px;
            margin-bottom: 1rem;
        }
        .nav-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        a {
            color: #6b1a2a;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="nav-bar">
        <a href="catalog.php">← Назад в каталог</a>
        <span>👑 Администратор: <?= htmlspecialchars($_SESSION['user_name']) ?> | <a href="logout.php">Выйти</a></span>
    </div>

    <div class="card">
        <h1>👥 Управление учителями</h1>
        <p>Здесь можно добавлять, удалять и сбрасывать пароли учителям физкультуры.</p>
    </div>

    <?php if ($message): ?>
        <div class="message">✅ <?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="card">
        <h2>➕ Добавить нового учителя</h2>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <div><input type="text" name="login" placeholder="Логин (например: ivanov)" required></div>
            <div><input type="text" name="full_name" placeholder="Полное имя (например: Иванов Иван)" required></div>
            <div><input type="text" name="password" placeholder="Пароль" value="teacher123" required></div>
            <button class="btn btn-primary">➕ Добавить</button>
        </form>
    </div>

    <div class="card">
        <h2>📋 Список учителей</h2>
        <table>
            <thead>
                <tr><th>ID</th><th>Логин</th><th>ФИО</th><th>Действия</th></tr>
            </thead>
            <tbody>
                <?php foreach ($teachers as $t): ?>
                <tr>
                    <td><?= $t['id'] ?></td>
                    <td><?= htmlspecialchars($t['login']) ?></td>
                    <td><?= htmlspecialchars($t['full_name']) ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="reset_password">
                            <input type="hidden" name="id" value="<?= $t['id'] ?>">
                            <button class="btn btn-outline" onclick="return confirm('Сбросить пароль на teacher123?')">Сброс пароля</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $t['id'] ?>">
                            <button class="btn btn-danger" onclick="return confirm('Удалить учителя?')">Удалить</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>