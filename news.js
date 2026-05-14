document.addEventListener('DOMContentLoaded', () => {
    // Открытие детальной новости
    document.querySelectorAll('.news-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.tagName === 'BUTTON') return;
            const id = this.dataset.id;
            fetch(`get_news.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) showNewsDetail(data.news);
                    else alert('Ошибка загрузки');
                })
                .catch(err => alert('Ошибка соединения'));
        });
    });

    // Кнопки для учителя
    document.querySelectorAll('.edit-news').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            alert('Редактирование новости id=' + btn.dataset.id);
        });
    });
    document.querySelectorAll('.delete-news').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (confirm('Удалить новость?')) {
                fetch(`delete_news.php?id=${btn.dataset.id}`).then(() => location.reload());
            }
        });
    });
    document.getElementById('addNewsBtn')?.addEventListener('click', () => alert('Форма добавления новости'));

    // Кнопка назад
    document.getElementById('backToNewsBtn')?.addEventListener('click', () => {
        document.getElementById('newsView').style.display = 'block';
        document.getElementById('detailView').style.display = 'none';
    });
});

function showNewsDetail(news) {
    const container = document.getElementById('newsDetailContainer');
    container.innerHTML = `
        <div class="detail-container">
            <img src="${news.img || 'image/placeholder.png'}" class="detail-img" onerror="this.src='image/placeholder.png'">
            <h1>${news.title}</h1>
            <div style="color: #6b1a2a; margin-bottom: 1rem;">${news.date}</div>
            <div class="news-content">${news.content}</div>
            <div style="margin-top: 2rem;">
                <button class="btn btn-outline" id="editNewsDetailBtn">✏️ Редактировать</button>
                <button class="btn btn-danger" id="deleteNewsDetailBtn" style="margin-left: 1rem;">🗑️ Удалить</button>
            </div>
        </div>
    `;
    document.getElementById('editNewsDetailBtn')?.addEventListener('click', () => alert('Редактирование id=' + news.id));
    document.getElementById('deleteNewsDetailBtn')?.addEventListener('click', () => {
        if (confirm('Удалить новость?')) {
            fetch(`delete_news.php?id=${news.id}`).then(() => location.reload());
        }
    });
    document.getElementById('newsView').style.display = 'none';
    document.getElementById('detailView').style.display = 'block';
}