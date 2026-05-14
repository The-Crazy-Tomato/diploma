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
    <title>Каталог | Спортивный клуб ЗПТ</title>
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
            <a href="catalog.php" class="active">Каталог</a>
            <a href="news.php">Новости</a>
        </div>
    </div>
</div>

<main>
    <div class="container" style="margin-top: 2rem;">
        <div id="catalogView">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <h1>Каталог инвентаря</h1>
                <?php if ($isTeacher): ?>
                    <button class="btn btn-primary" id="addInventoryBtn">+ Добавить инвентарь</button>
                <?php endif; ?>
            </div>
            <div class="catalog-grid" id="catalogGrid">
                <?php
                $stmt = $pdo->query("SELECT * FROM inventory");
                while($item = $stmt->fetch(PDO::FETCH_ASSOC)):
                ?>
                <div class="inventory-card" data-id="<?= $item['id'] ?>">
                    <div class="card-img">
                        <img src="<?= htmlspecialchars($item['main_img']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" onerror="this.src='image/placeholder.png'">
                    </div>
                    <div class="card-content">
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <p><?= htmlspecialchars(mb_substr($item['description'], 0, 60)) ?>...</p>
                        <?php if ($isTeacher): ?>
                            <div onclick="event.stopPropagation()">
                                <button class="btn btn-outline btn-sm edit-item" data-id="<?= $item['id'] ?>">Ред.</button>
                                <button class="btn btn-danger btn-sm delete-item" data-id="<?= $item['id'] ?>">Удалить</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div id="detailView" style="display: none;">
            <button class="btn btn-outline back-button" id="backToCatalogBtn">← Назад к каталогу</button>
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

<div id="itemModal" class="modal">
    <div class="modal-content">
        <h2 id="itemModalTitle">Добавить инвентарь</h2>
        <input type="hidden" id="editId">
        <input type="text" id="itemName" placeholder="Название">
        <textarea id="itemDesc" placeholder="Описание"></textarea>
        <input type="text" id="itemImg" placeholder="Путь к картинке (image/название.png)">
        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button class="btn btn-primary" id="saveItemBtn">Сохранить</button>
            <button class="btn btn-outline" id="closeItemModalBtn">Отмена</button>
        </div>
    </div>
</div>

<div id="exerciseModal" class="modal">
    <div class="modal-content">
        <h2 id="exerciseModalTitle">Добавить упражнение</h2>
        <input type="hidden" id="editExerciseId">
        <input type="hidden" id="currentInventoryId">
        <input type="text" id="exerciseTitle" placeholder="Название упражнения (например: Удар подъёмом)">
        <input type="text" id="exerciseRepeats" placeholder="Повторы (например: 3 подхода по 10 раз)">
        <textarea id="exerciseText" rows="4" placeholder="Описание техники выполнения"></textarea>
        <div>
            <h4>Картинки упражнения</h4>
            <div id="existingImages" class="image-gallery"></div>
            <input type="text" id="newImagePath" placeholder="Путь к картинке (image/название.png)">
            <button type="button" class="btn-outline" id="addImageBtn">+ Добавить картинку</button>
        </div>
        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button class="btn btn-primary" id="saveExerciseBtn">Сохранить</button>
            <button class="btn btn-outline" id="closeExerciseModalBtn">Отмена</button>
        </div>
    </div>
</div>

<script>
const isTeacher = <?= $isTeacher ? 'true' : 'false' ?>;

document.querySelectorAll('.inventory-card').forEach(card => {
    card.addEventListener('click', async function(e) {
        if(e.target.closest('.edit-item, .delete-item')) return;
        const id = this.dataset.id;
        const resp = await fetch(`get_item.php?id=${id}`);
        const data = await resp.json();
        if(data.success) showDetail(data.item);
        else alert('Ошибка загрузки');
    });
});

function showDetail(item) {
    let exercisesHtml = '';
    for(let ex of (item.exercises || [])) {
        let imagesHtml = '';
        for(let img of (ex.images || [])) {
            imagesHtml += `<img src="${img.img}" onclick="openImageZoom('${img.img}')">`;
        }
        exercisesHtml += `
            <div class="exercise-card" data-exercise-id="${ex.id}">
                <div style="flex:2">
                    <h3 style="color:#6b1a2a; margin-bottom:8px;">${escapeHtml(ex.title || 'Упражнение')}</h3>
                    ${ex.repeats ? `<p><strong>Повторы:</strong> ${escapeHtml(ex.repeats)}</p>` : ''}
                    <p>${escapeHtml(ex.text)}</p>
                    ${isTeacher ? `
                        <div style="margin-top:12px">
                            <button class="btn btn-outline btn-sm" onclick="openExerciseModal(${ex.id}, ${item.id})">Ред.</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteExercise(${ex.id})">Удалить</button>
                        </div>
                    ` : ''}
                </div>
                <div class="exercise-images">
                    ${imagesHtml}
                </div>
            </div>
        `;
    }

    let commentsHtml = '';
    for(let c of (item.comments || [])) {
        commentsHtml += `<div class="comment-item"><strong>${escapeHtml(c.author || 'Аноним')}</strong> (${c.date})<br>${escapeHtml(c.text)}</div>`;
    }

    document.getElementById('detailContent').innerHTML = `
        <div style="display: flex; gap: 2rem; flex-wrap: wrap; margin-bottom: 2rem;">
            <img src="${item.main_img}" style="width:200px; height:200px; object-fit:cover; border-radius:20px;" onerror="this.src='image/placeholder.png'">
            <div style="flex:1">
                <h1>${escapeHtml(item.name)}</h1>
                <p>${escapeHtml(item.description)}</p>
                ${isTeacher ? `
                    <div style="margin-top:1rem;">
                        <button class="btn btn-outline" onclick="openExerciseModal(null, ${item.id})">+ Добавить упражнение</button>
                        <button class="btn btn-outline" onclick="openItemModal(${item.id})">Ред. инвентарь</button>
                    </div>
                ` : ''}
            </div>
        </div>
        <h2 style="margin-top:2rem">Упражнения</h2>
        ${exercisesHtml || '<p>Упражнения пока не добавлены</p>'}
        <div class="comments-section">
            <h2>Комментарии студентов</h2>
            ${commentsHtml || '<p>Комментариев пока нет</p>'}
            <textarea id="newComment" rows="2" placeholder="Оставить анонимный отзыв..."></textarea>
            <button class="btn btn-primary" id="sendCommentBtn">Отправить</button>
        </div>
    `;

    document.getElementById('sendCommentBtn').onclick = () => {
        const txt = document.getElementById('newComment').value.trim();
        if(txt) {
            fetch('add_comment.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `inventory_id=${item.id}&text=${encodeURIComponent(txt)}`
            }).then(() => {
                fetch(`get_item.php?id=${item.id}`)
                    .then(r => r.json())
                    .then(data => showDetail(data.item));
            });
        }
    };

    document.getElementById('catalogView').style.display = 'none';
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

function openItemModal(id = null) {
    const modal = document.getElementById('itemModal');
    document.getElementById('itemModalTitle').innerText = id ? 'Редактировать инвентарь' : 'Добавить инвентарь';
    document.getElementById('editId').value = id || '';
    if(id) {
        fetch(`get_item.php?id=${id}`).then(r=>r.json()).then(data => {
            const item = data.item || data;
            document.getElementById('itemName').value = item.name;
            document.getElementById('itemDesc').value = item.description;
            document.getElementById('itemImg').value = item.main_img;
        });
    } else {
        document.getElementById('itemName').value = '';
        document.getElementById('itemDesc').value = '';
        document.getElementById('itemImg').value = '';
    }
    modal.style.display = 'flex';
}

document.getElementById('addInventoryBtn').onclick = () => openItemModal();
document.querySelectorAll('.edit-item').forEach(btn => {
    btn.onclick = (e) => {
        e.stopPropagation();
        openItemModal(parseInt(btn.dataset.id));
    };
});
document.querySelectorAll('.delete-item').forEach(btn => {
    btn.onclick = (e) => {
        e.stopPropagation();
        if(confirm('Удалить?')) fetch(`delete_item.php?id=${btn.dataset.id}`).then(() => location.reload());
    };
});
document.getElementById('saveItemBtn').onclick = () => {
    const id = document.getElementById('editId').value;
    const data = new FormData();
    data.append('name', document.getElementById('itemName').value);
    data.append('description', document.getElementById('itemDesc').value);
    data.append('main_img', document.getElementById('itemImg').value);
    if(id) data.append('id', id);
    fetch(id ? 'update_item.php' : 'add_item.php', { method: 'POST', body: data })
        .then(() => location.reload());
};

let pendingImages = [];

function openExerciseModal(exerciseId = null, inventoryId = null) {
    const modal = document.getElementById('exerciseModal');
    document.getElementById('exerciseModalTitle').innerText = exerciseId ? 'Редактировать упражнение' : 'Добавить упражнение';
    document.getElementById('editExerciseId').value = exerciseId || '';
    if(inventoryId) document.getElementById('currentInventoryId').value = inventoryId;

    if(exerciseId) {
        fetch(`get_exercise.php?id=${exerciseId}`).then(r=>r.json()).then(data => {
            document.getElementById('exerciseTitle').value = data.title || '';
            document.getElementById('exerciseRepeats').value = data.repeats || '';
            document.getElementById('exerciseText').value = data.text || '';
            pendingImages = data.images || [];
            renderImages();
        });
    } else {
        document.getElementById('exerciseTitle').value = '';
        document.getElementById('exerciseRepeats').value = '';
        document.getElementById('exerciseText').value = '';
        pendingImages = [];
        renderImages();
    }
    modal.style.display = 'flex';
}

function renderImages() {
    const container = document.getElementById('existingImages');
    container.innerHTML = '';
    pendingImages.forEach((img, idx) => {
        const div = document.createElement('div');
        div.style.position = 'relative';
        div.style.display = 'inline-block';
        div.style.margin = '5px';
        div.innerHTML = `<img src="${img.img}" style="width:80px;height:80px;object-fit:cover;border-radius:12px"><button onclick="removeImage(${idx})" style="position:absolute;top:-8px;right:-8px;background:#b33a4a;color:white;border:none;border-radius:50%;width:22px;height:22px;cursor:pointer;font-size:14px;">x</button>`;
        container.appendChild(div);
    });
}

function removeImage(idx) {
    pendingImages.splice(idx, 1);
    renderImages();
}

document.getElementById('addImageBtn').onclick = () => {
    const path = document.getElementById('newImagePath').value.trim();
    if(path) {
        pendingImages.push({ img: path });
        document.getElementById('newImagePath').value = '';
        renderImages();
    }
};

document.getElementById('saveExerciseBtn').onclick = () => {
    const exerciseId = document.getElementById('editExerciseId').value;
    const inventoryId = document.getElementById('currentInventoryId').value;
    const data = new FormData();
    data.append('title', document.getElementById('exerciseTitle').value);
    data.append('repeats', document.getElementById('exerciseRepeats').value);
    data.append('text', document.getElementById('exerciseText').value);
    data.append('inventory_id', inventoryId);
    if(exerciseId) data.append('id', exerciseId);
    data.append('images', JSON.stringify(pendingImages));

    fetch(exerciseId ? 'update_exercise.php' : 'add_exercise.php', { method: 'POST', body: data })
        .then(() => location.reload());
};

function deleteExercise(id) {
    if(confirm('Удалить упражнение?')) {
        fetch(`delete_exercise.php?id=${id}`).then(() => location.reload());
    }
}

function openImageZoom(src) {
    const modalDiv = document.createElement('div');
    modalDiv.style.position = 'fixed';
    modalDiv.style.top = '0';
    modalDiv.style.left = '0';
    modalDiv.style.width = '100%';
    modalDiv.style.height = '100%';
    modalDiv.style.background = 'rgba(0,0,0,0.9)';
    modalDiv.style.display = 'flex';
    modalDiv.style.justifyContent = 'center';
    modalDiv.style.alignItems = 'center';
    modalDiv.style.zIndex = '2000';
    modalDiv.onclick = () => modalDiv.remove();
    const img = document.createElement('img');
    img.src = src;
    img.style.maxWidth = '90%';
    img.style.maxHeight = '90%';
    modalDiv.appendChild(img);
    document.body.appendChild(modalDiv);
}

document.getElementById('backToCatalogBtn').onclick = () => {
    document.getElementById('catalogView').style.display = 'block';
    document.getElementById('detailView').style.display = 'none';
};
document.getElementById('closeItemModalBtn').onclick = () => document.getElementById('itemModal').style.display = 'none';
document.getElementById('closeExerciseModalBtn').onclick = () => document.getElementById('exerciseModal').style.display = 'none';
window.onclick = (e) => { if(e.target.classList.contains('modal')) e.target.style.display = 'none'; };
</script>
</body>
</html>