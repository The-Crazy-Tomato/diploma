<?php
session_start();
require_once 'config.php';

$isTeacher = isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'teacher' || $_SESSION['user_role'] === 'admin');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новости | Спортивный клуб ЗПТ</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Roboto', sans-serif; background-color: #fef7e6; color: #2d2d2d; line-height: 1.5; }
        :root { --bordeaux: #6b1a2a; --bordeaux-light: #8a2538; --bordeaux-dark: #4d1220; --shadow: 0 20px 35px -12px rgba(0,0,0,0.1); --border-radius-lg: 32px; --border-radius-md: 24px; }
        h1, h2, h3 { font-family: 'Montserrat', sans-serif; }
        .container { max-width: 1300px; margin: 0 auto; padding: 0 1.5rem; }
        
        .auth-btn { position: fixed; top: 20px; right: 30px; background: var(--bordeaux); color: white; padding: 0.5rem 1.2rem; border-radius: 40px; text-decoration: none; z-index: 100; box-shadow: 0 2px 8px rgba(0,0,0,0.2); transition: 0.2s; }
        .auth-btn:hover { background: var(--bordeaux-light); }
        
        .hero-block { margin: 2rem auto 0 auto; max-width: 1300px; padding: 0 1.5rem; }
        .hero-image { background-image: url('image/photo1.png'); background-size: cover; background-position: center; border-radius: 32px; position: relative; min-height: 360px; display: flex; align-items: flex-end; justify-content: center; box-shadow: var(--shadow); padding-bottom: 2rem; }
        .nav-rectangle { background-color: rgba(107, 26, 42, 0.94); backdrop-filter: blur(6px); padding: 1rem 2.5rem; border-radius: 60px; display: flex; gap: 3rem; justify-content: center; flex-wrap: wrap; box-shadow: 0 8px 20px rgba(0,0,0,0.25); margin-bottom: -1rem; }
        .nav-rectangle a { color: white; text-decoration: none; font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 1.25rem; padding: 0.5rem 0; transition: 0.2s; border-bottom: 2px solid transparent; }
        .nav-rectangle a:hover, .nav-rectangle a.active { border-bottom-color: #f5cf9e; color: #f5cf9e; }
        
        .catalog-grid, .news-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem; margin: 2rem 0; }
        .inventory-card, .news-card { background: white; border-radius: 24px; overflow: hidden; transition: 0.2s; box-shadow: 0 5px 15px rgba(0,0,0,0.05); cursor: pointer; }
        .inventory-card:hover, .news-card:hover { transform: translateY(-5px); box-shadow: var(--shadow); }
        .card-img, .news-img { height: 200px; background: #f0e5d6; overflow: hidden; }
        .card-img img, .news-img img { width: 100%; height: 100%; object-fit: cover; }
        .card-content { padding: 1.2rem; }
        .news-date { color: #6b1a2a; font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500; }
        
        .btn { padding: 8px 18px; border-radius: 40px; border: none; font-weight: 500; cursor: pointer; margin-top: 10px; margin-right: 8px; transition: 0.2s; }
        .btn-primary { background: var(--bordeaux); color: white; }
        .btn-primary:hover { background: var(--bordeaux-light); }
        .btn-outline { background: transparent; border: 1px solid var(--bordeaux); color: var(--bordeaux); }
        .btn-outline:hover { background: var(--bordeaux); color: white; }
        .btn-danger { background: #b33a4a; color: white; }
        .btn-danger:hover { background: #8a2a3a; }
        .btn-sm { padding: 4px 12px; font-size: 0.8rem; }
        
        .detail-container { background: white; border-radius: 32px; padding: 2rem; margin-top: 2rem; }
        .detail-img { width: 100%; max-height: 400px; object-fit: cover; border-radius: 24px; margin-bottom: 1.5rem; }
        
        .exercise-card { background: #fef4ea; border-radius: 24px; padding: 1.5rem; margin-bottom: 1.5rem; display: flex; gap: 1.5rem; flex-wrap: wrap; }
        .exercise-images { display: flex; gap: 10px; flex-wrap: wrap; }
        .exercise-images img { width: 150px; height: 150px; object-fit: cover; border-radius: 16px; cursor: pointer; transition: 0.2s; }
        .exercise-images img:hover { transform: scale(1.02); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        
        .comments-section { margin-top: 2rem; }
        .comment-item { background: #faf3e8; border-radius: 20px; padding: 12px 16px; margin-bottom: 12px; }
        textarea { width: 100%; border-radius: 20px; border: 1px solid #ddd; padding: 12px; resize: vertical; font-family: 'Roboto', sans-serif; }
        
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: white; padding: 2rem; border-radius: 32px; width: 90%; max-width: 550px; max-height: 80vh; overflow-y: auto; }
        .modal-content input, .modal-content textarea { width: 100%; padding: 10px; margin: 10px 0; border-radius: 20px; border: 1px solid #ddd; font-family: 'Roboto', sans-serif; }
        .image-gallery { display: flex; gap: 10px; flex-wrap: wrap; margin: 10px 0; }
        .image-gallery img { width: 80px; height: 80px; object-fit: cover; border-radius: 12px; }
        .back-button { margin-bottom: 1.5rem; }
        
        footer { background: var(--bordeaux-dark); color: #e0cbd0; padding: 2.5rem 2rem 1.5rem; margin-top: 2rem; }
        .footer-container { max-width: 1300px; margin: 0 auto; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 2rem; }
        .footer-nav { display: flex; flex-direction: column; gap: 0.5rem; }
        .footer-nav a { color: #efcfd6; text-decoration: none; transition: 0.2s; }
        .footer-nav a:hover { color: #f5cf9e; }
        .copyright { text-align: center; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2); font-size: 0.8rem; }
        
        /* ===== АДАПТИВ ===== */
        @media (max-width: 992px) {
            .container { padding: 0 1.2rem; }
            .catalog-grid, .news-grid { gap: 1.5rem; }
            .exercise-card { flex-direction: column; }
            .exercise-images img { width: 120px; height: 120px; }
            .detail-container { padding: 1.5rem; }
        }
        
        @media (max-width: 768px) {
            .hero-block { margin: 1rem auto 0; padding: 0 1rem; }
            .hero-image { min-height: 250px; border-radius: 24px; }
            .nav-rectangle { padding: 0.6rem 1.2rem; gap: 1.2rem; border-radius: 40px; margin-bottom: -0.8rem; }
            .nav-rectangle a { font-size: 0.9rem; }
            .auth-btn { position: static; display: inline-block; margin: 0.8rem 1rem 0 0; float: right; font-size: 0.9rem; padding: 0.4rem 1rem; }
            h1 { font-size: 1.6rem; }
            h2 { font-size: 1.3rem; }
            .catalog-grid, .news-grid { grid-template-columns: 1fr; gap: 1.2rem; }
            .card-img, .news-img { height: 180px; }
            .card-content { padding: 1rem; }
            .detail-container { padding: 1.2rem; border-radius: 24px; }
            .detail-container > div:first-child { flex-direction: column; align-items: center; text-align: center; }
            .detail-container img:first-child { width: 180px; height: 180px; }
            .detail-img { max-height: 250px; }
            .exercise-card { padding: 1rem; gap: 1rem; }
            .exercise-images img { width: 100px; height: 100px; }
            .comment-item { padding: 10px 12px; }
            textarea { font-size: 14px; }
            .btn { padding: 6px 14px; font-size: 0.85rem; }
            .modal-content { padding: 1.2rem; width: 95%; }
            .modal-content input, .modal-content textarea { font-size: 14px; }
            footer { padding: 1.5rem; }
            .footer-container { flex-direction: column; gap: 1rem; text-align: center; }
            .footer-nav { flex-direction: row; justify-content: center; gap: 1.5rem; }
        }
        
        @media (max-width: 480px) {
            .hero-image { min-height: 200px; border-radius: 20px; }
            .nav-rectangle { padding: 0.5rem 1rem; gap: 0.8rem; }
            .nav-rectangle a { font-size: 0.8rem; }
            h1 { font-size: 1.4rem; }
            .card-img, .news-img { height: 150px; }
            .exercise-images img { width: 80px; height: 80px; }
            .btn { padding: 5px 12px; font-size: 0.8rem; }
            .modal-content h2 { font-size: 1.2rem; }
        }
        
        @media (min-width: 769px) and (max-width: 1024px) {
            .catalog-grid, .news-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

<?php if ($isTeacher): ?>
    <a href="logout.php" class="auth-btn">выход</a>
<?php else: ?>
    <a href="login.php" class="auth-btn">вход</a>
<?php endif; ?>

<div class="hero-block">
    <div class="hero-image">
        <div class="nav-rectangle">
            <a href="index.php">Главная</a>
            <a href="catalog.php">Каталог</a>
            <a href="news.php" class="active">Новости</a>
        </div>
    </div>
</div>

<main>
    <div class="container" style="margin-top: 2rem;">
        <div id="newsView">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <h1>Новости клуба</h1>
                <?php if ($isTeacher): ?>
                    <button class="btn btn-primary" id="addNewsBtn">+ Добавить новость</button>
                <?php endif; ?>
            </div>
            <div class="news-grid" id="newsGrid">
                <?php
                $stmt = $pdo->query("SELECT * FROM news ORDER BY date DESC");
                while($news = $stmt->fetch(PDO::FETCH_ASSOC)):
                ?>
                <div class="news-card" data-id="<?= $news['id'] ?>">
                    <div class="news-img">
                        <img src="<?= htmlspecialchars($news['img']) ?>" alt="<?= htmlspecialchars($news['title']) ?>" onerror="this.src='image/placeholder.png'">
                    </div>
                    <div class="card-content">
                        <div class="news-date"><?= date('d.m.Y', strtotime($news['date'])) ?></div>
                        <h3><?= htmlspecialchars($news['title']) ?></h3>
                        <p><?= htmlspecialchars(mb_substr($news['preview'] ?: $news['title'], 0, 100)) ?>...</p>
                        <?php if ($isTeacher): ?>
                            <div onclick="event.stopPropagation()">
                                <button class="btn btn-outline btn-sm edit-news" data-id="<?= $news['id'] ?>">Ред.</button>
                                <button class="btn btn-danger btn-sm delete-news" data-id="<?= $news['id'] ?>">Удалить</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div id="detailView" style="display: none;">
            <button class="btn btn-outline back-button" id="backToNewsBtn">← Все новости</button>
            <div id="detailContent" class="detail-container"></div>
        </div>
    </div>
</main>

<footer>
    <div class="footer-container">
        <div class="footer-col">
            <p><strong>КГБПОУ «Заринский политехнический техникум»</strong></p>
            <p>Спортивный клуб ЗПТ</p>
            <p>г. Заринск, Алтайский край</p>
        </div>
        <div class="footer-nav">
            <a href="index.php">Главная</a>
            <a href="catalog.php">Каталог</a>
            <a href="news.php">Новости</a>
        </div>
        <div class="footer-col">
            <p>📞 +7 (38595) 2-22-33</p>
            <p>📧 sport@zpt.edu22.info</p>
            <p><a href="http://zpt.edu22.info" target="_blank" style="color:#efcfd6;">zpt.edu22.info</a></p>
        </div>
    </div>
    <div class="copyright">© 2026 Спортивный клуб ЗПТ — всё для спорта и здоровья</div>
</footer>

<div id="newsModal" class="modal">
    <div class="modal-content">
        <h2 id="newsModalTitle">Добавить новость</h2>
        <input type="hidden" id="editNewsId">
        <input type="text" id="newsTitle" placeholder="Заголовок новости">
        <input type="text" id="newsPreview" placeholder="Краткий анонс (не обязательно)">
        <textarea id="newsContent" rows="8" placeholder="Полный текст новости..."></textarea>
        <input type="text" id="newsImg" placeholder="Путь к картинке (image/название.png)">
        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button class="btn btn-primary" id="saveNewsBtn">Сохранить</button>
            <button class="btn btn-outline" id="closeNewsModalBtn">Отмена</button>
        </div>
    </div>
</div>

<script>
const isTeacher = <?= $isTeacher ? 'true' : 'false' ?>;

document.querySelectorAll('.news-card').forEach(card => {
    card.addEventListener('click', async function(e) {
        if(e.target.closest('.edit-news, .delete-news')) return;
        const id = this.dataset.id;
        const resp = await fetch(`get_news.php?id=${id}`);
        const data = await resp.json();
        if(data.success) showDetail(data.news);
        else alert('Ошибка загрузки');
    });
});

function showDetail(news) {
    document.getElementById('detailContent').innerHTML = `
        <img src="${news.img || 'image/placeholder.png'}" class="detail-img" onerror="this.src='image/placeholder.png'">
        <h1>${escapeHtml(news.title)}</h1>
        <div style="color: #6b1a2a; margin-bottom: 1rem;">${news.date}</div>
        <div style="line-height: 1.7;">${news.content}</div>
        ${isTeacher ? `
            <div style="margin-top: 2rem;">
                <button class="btn btn-outline" onclick="openNewsModal(${news.id})">Редактировать</button>
                <button class="btn btn-danger" onclick="deleteNews(${news.id})" style="margin-left: 1rem;">Удалить</button>
            </div>
        ` : ''}
    `;
    document.getElementById('newsView').style.display = 'none';
    document.getElementById('detailView').style.display = 'block';
}

function escapeHtml(str) {
    if(!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if(m === '&') return '&amp;';
        if(m === '<') return '&lt;';
        if(m === '>') return '&gt;';
        return m;
    });
}

function openNewsModal(id = null) {
    const modal = document.getElementById('newsModal');
    document.getElementById('newsModalTitle').innerText = id ? 'Редактировать новость' : 'Добавить новость';
    document.getElementById('editNewsId').value = id || '';
    if(id) {
        fetch(`get_news.php?id=${id}`).then(r=>r.json()).then(data => {
            const news = data.news || data;
            document.getElementById('newsTitle').value = news.title;
            document.getElementById('newsPreview').value = news.preview || '';
            document.getElementById('newsContent').value = news.content;
            document.getElementById('newsImg').value = news.img || '';
        });
    } else {
        document.getElementById('newsTitle').value = '';
        document.getElementById('newsPreview').value = '';
        document.getElementById('newsContent').value = '';
        document.getElementById('newsImg').value = '';
    }
    modal.style.display = 'flex';
}

document.getElementById('addNewsBtn').onclick = () => openNewsModal();
document.querySelectorAll('.edit-news').forEach(btn => {
    btn.onclick = (e) => {
        e.stopPropagation();
        openNewsModal(parseInt(btn.dataset.id));
    };
});
document.querySelectorAll('.delete-news').forEach(btn => {
    btn.onclick = (e) => {
        e.stopPropagation();
        if(confirm('Удалить новость?')) {
            fetch(`delete_news.php?id=${btn.dataset.id}`).then(() => location.reload());
        }
    };
});

document.getElementById('saveNewsBtn').onclick = () => {
    const id = document.getElementById('editNewsId').value;
    const data = new FormData();
    data.append('title', document.getElementById('newsTitle').value);
    data.append('preview', document.getElementById('newsPreview').value);
    data.append('content', document.getElementById('newsContent').value);
    data.append('img', document.getElementById('newsImg').value);
    if(id) data.append('id', id);
    
    fetch(id ? 'update_news.php' : 'add_news.php', { method: 'POST', body: data })
        .then(() => location.reload());
};

function deleteNews(id) {
    if(confirm('Удалить новость?')) {
        fetch(`delete_news.php?id=${id}`).then(() => location.reload());
    }
}

document.getElementById('backToNewsBtn').onclick = () => {
    document.getElementById('newsView').style.display = 'block';
    document.getElementById('detailView').style.display = 'none';
};
document.getElementById('closeNewsModalBtn').onclick = () => document.getElementById('newsModal').style.display = 'none';
window.onclick = (e) => { if(e.target.classList.contains('modal')) e.target.style.display = 'none'; };
</script>
</body>
</html>