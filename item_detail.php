<?php
session_start();
require_once 'config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT * FROM inventory WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$item) {
    header('Location: catalog.php');
    exit;
}

// Получаем упражнения
$exStmt = $pdo->prepare("SELECT * FROM exercises WHERE inventory_id = ?");
$exStmt->execute([$id]);
$exercises = $exStmt->fetchAll(PDO::FETCH_ASSOC);

// Получаем комментарии
$comStmt = $pdo->prepare("SELECT * FROM comments WHERE inventory_id = ? ORDER BY date DESC");
$comStmt->execute([$id]);
$comments = $comStmt->fetchAll(PDO::FETCH_ASSOC);

$isTeacher = isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'teacher' || $_SESSION['user_role'] === 'admin');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($item['name']) ?> | Спортивный клуб ЗПТ</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Roboto&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Roboto', sans-serif; background-color: #fef7e6; color: #2d2d2d; }
        .container { max-width: 1300px; margin: 0 auto; padding: 0 1.5rem; }
        .hero-block { margin: 2rem auto 0; max-width: 1300px; padding: 0 1.5rem; }
        .hero-image { background-image: url('image/photo1.png'); background-size: cover; background-position: center; border-radius: 32px; min-height: 360px; display: flex; align-items: flex-end; justify-content: center; padding-bottom: 2rem; }
        .nav-rectangle { background-color: rgba(107,26,42,0.94); backdrop-filter: blur(6px); padding: 1rem 2.5rem; border-radius: 60px; display: flex; gap: 3rem; justify-content: center; flex-wrap: wrap; margin-bottom: -1rem; }
        .nav-rectangle a { color: white; text-decoration: none; font-weight: 700; font-size: 1.25rem; border-bottom: 2px solid transparent; }
        .nav-rectangle a:hover { border-bottom-color: #f5cf9e; color: #f5cf9e; }
        .auth-btn { position: fixed; top: 20px; right: 30px; background: #6b1a2a; color: white; padding: 0.5rem 1.2rem; border-radius: 40px; text-decoration: none; z-index: 100; }
        .btn { padding: 8px 18px; border-radius: 40px; border: none; font-weight: 500; cursor: pointer; display: inline-block; text-decoration: none; }
        .btn-primary { background: #6b1a2a; color: white; }
        .btn-outline { background: transparent; border: 1px solid #6b1a2a; color: #6b1a2a; }
        .detail-container { background: white; border-radius: 32px; padding: 2rem; margin-top: 2rem; }
        .detail-img { width: 300px; max-width: 100%; border-radius: 24px; }
        .exercise-card { background: #fef4ea; border-radius: 20px; padding: 1rem; margin-bottom: 1rem; display: flex; gap: 1rem; flex-wrap: wrap; }
        .exercise-card img { width: 150px; height: 150px; object-fit: cover; border-radius: 16px; }
        .comment-item { background: #faf3e8; border-radius: 16px; padding: 0.8rem; margin-bottom: 0.8rem; }
        textarea { width: 100%; border-radius: 20px; border: 1px solid #ddd; padding: 12px; margin: 1rem 0; resize: vertical; }
        footer { background: #4d1220; color: #e0cbd0; padding: 2rem; margin-top: 2rem; }
        .footer-container { max-width: 1300px; margin: 0 auto; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 2rem; }
        .footer-nav a { color: #efcfd6; text-decoration: none; display: block; }
        .back-link { display: inline-block; margin-bottom: 1rem; }
        @media (max-width: 800px) {
            .nav-rectangle { gap: 1.5rem; padding: 0.8rem 1.5rem; }
            .auth-btn { position: static; display: inline-block; margin: 1rem 1.5rem 0 0; float: right; }
        }
    </style>
</head>
<body>

<?php if ($isTeacher): ?>
    <a href="logout.php" class="auth-btn"> выход</a>
<?php else: ?>
    <a href="login.php" class="auth-btn"> вход</a>
<?php endif; ?>

<div class="hero-block">
    <div class="hero-image">
        <div class="nav-rectangle">
            <a href="index.php">Главная</a>
            <a href="catalog.php">Каталог</a>
            <a href="news.php">Новости</a>
        </div>
    </div>
</div>

<main class="container">
    <a href="catalog.php" class="back-link">← Назад к каталогу</a>
    
    <div class="detail-container">
        <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
            <img src="<?= htmlspecialchars($item['main_img']) ?>" class="detail-img" onerror="this.src='image/placeholder.png'">
            <div style="flex:1">
                <h1><?= htmlspecialchars($item['name']) ?></h1>
                <p><?= htmlspecialchars($item['description']) ?></p>
                <?php if ($isTeacher): ?>
                    <div style="margin-top: 1rem;">
                        <a href="edit_inventory.php?id=<?= $item['id'] ?>" class="btn btn-outline">✏️ Редактировать</a>
                        <a href="delete_inventory.php?id=<?= $item['id'] ?>" class="btn btn-danger" onclick="return confirm('Удалить?')">🗑️ Удалить</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <h2 style="margin-top: 2rem;">Упражнения и техника</h2>
        <?php if(count($exercises) > 0): ?>
            <?php foreach($exercises as $ex): ?>
            <div class="exercise-card">
                <img src="<?= htmlspecialchars($ex['img']) ?>" onerror="this.src='image/placeholder.png'">
                <div style="flex:1"><?= nl2br(htmlspecialchars($ex['text'])) ?></div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Упражнения пока не добавлены.</p>
        <?php endif; ?>
        
        <h2 style="margin-top: 2rem;">Комментарии</h2>
        <?php if(count($comments) > 0): ?>
            <?php foreach($comments as $c): ?>
            <div class="comment-item">
                <strong>Аноним</strong> (<?= date('d.m.Y', strtotime($c['date'])) ?>)<br>
                <?= nl2br(htmlspecialchars($c['text'])) ?>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Комментариев пока нет.</p>
        <?php endif; ?>
        
        <form method="POST" action="add_comment.php">
            <input type="hidden" name="inventory_id" value="<?= $item['id'] ?>">
            <textarea name="text" rows="2" placeholder="Оставить анонимный отзыв..." required></textarea>
            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
    </div>
</main>

<footer>
    <div class="footer-container">
        <div>
            <p><strong>КГБПОУ «Заринский политехнический техникум»</strong></p>
            <p>Спортивный клуб ЗПТ</p>
            <p>г. Заринск, Алтайский край</p>
        </div>
        <div class="footer-nav">
            <a href="index.php">Главная</a>
            <a href="catalog.php">Каталог</a>
            <a href="news.php">Новости</a>
        </div>
        <div>
            <p>📞 +7 (38595) 2-22-33</p>
            <p>📧 sport@zpt.edu22.info</p>
        </div>
    </div>
    <div class="copyright" style="text-align:center; margin-top:1rem;">© 2026 Спортивный клуб ЗПТ — всё для спорта и здоровья</div>
</footer>

</body>
</html>