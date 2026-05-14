function openEditModal(id = null) {
    const modal = document.getElementById('itemModal');
    const modalTitle = document.getElementById('modalTitle');
    const editId = document.getElementById('editId');
    const itemName = document.getElementById('itemName');
    const itemDesc = document.getElementById('itemDesc');
    const itemImg = document.getElementById('itemImg');
    
    if(id) {
        modalTitle.innerText = 'Редактировать инвентарь';
        fetch('get_item.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                const item = data.item || data;
                editId.value = item.id;
                itemName.value = item.name;
                itemDesc.value = item.description;
                itemImg.value = item.main_img;
            });
    } else {
        modalTitle.innerText = 'Добавить инвентарь';
        editId.value = '';
        itemName.value = '';
        itemDesc.value = '';
        itemImg.value = '';
    }
    modal.style.display = 'flex';
}

function saveItem() {
    const id = document.getElementById('editId').value;
    const name = document.getElementById('itemName').value;
    const desc = document.getElementById('itemDesc').value;
    const img = document.getElementById('itemImg').value;
    
    if(name === '') {
        alert('Введите название');
        return;
    }
    
    const formData = new FormData();
    formData.append('name', name);
    formData.append('description', desc);
    formData.append('main_img', img);
    if(id) formData.append('id', id);
    
    const url = id ? 'update_item.php' : 'add_item.php';
    
    fetch(url, {
        method: 'POST',
        body: formData
    }).then(() => {
        window.location.reload();
    });
}

function closeModal() {
    document.getElementById('itemModal').style.display = 'none';
}

function showDetail(item) {
    let exercisesHtml = '';
    if(item.exercises && item.exercises.length) {
        for(let ex of item.exercises) {
            exercisesHtml += `
                <div style="background:#fef4ea; border-radius:20px; padding:1rem; margin-bottom:1rem; display:flex; gap:1rem; flex-wrap:wrap;">
                    <img src="${ex.img}" style="width:150px; height:150px; object-fit:cover; border-radius:16px;" onerror="this.src='image/placeholder.png'">
                    <div style="flex:1"><p>${ex.text}</p></div>
                </div>
            `;
        }
    } else {
        exercisesHtml = '<p>Упражнения пока не добавлены</p>';
    }
    
    let commentsHtml = '';
    if(item.comments && item.comments.length) {
        for(let c of item.comments) {
            commentsHtml += `<div style="background:#faf3e8; border-radius:16px; padding:0.8rem; margin-bottom:0.8rem;"><strong>${c.author || 'Аноним'}</strong> (${c.date})<br>${c.text}</div>`;
        }
    } else {
        commentsHtml = '<p>Комментариев пока нет</p>';
    }
    
    let teacherButtons = '';
    if(window.isTeacher) {
        teacherButtons = `
            <div style="margin-top:1rem;">
                <button class="btn btn-outline" onclick="openEditModal(${item.id})">Редактировать</button>
                <button class="btn btn-danger" onclick="if(confirm('Удалить?')) fetch('delete_item.php?id=${item.id}').then(()=>location.reload())">Удалить</button>
            </div>
        `;
    }
    
    document.getElementById('detailContent').innerHTML = `
        <div style="display:flex; gap:2rem; flex-wrap:wrap;">
            <img src="${item.main_img}" style="width:200px; border-radius:16px;" onerror="this.src='image/placeholder.png'">
            <div style="flex:1">
                <h1>${item.name}</h1>
                <p>${item.description}</p>
                ${teacherButtons}
            </div>
        </div>
        <h2 style="margin-top:2rem">Упражнения</h2>
        ${exercisesHtml}
        <h2 style="margin-top:2rem">Комментарии студентов</h2>
        ${commentsHtml}
        <textarea id="newComment" rows="2" style="width:100%; border-radius:20px; padding:12px; margin:1rem 0;" placeholder="Оставить анонимный отзыв..."></textarea>
        <button class="btn btn-primary" id="sendCommentBtn">Отправить</button>
    `;
    
    document.getElementById('sendCommentBtn').onclick = () => {
        const txt = document.getElementById('newComment').value.trim();
        if(txt) {
            fetch('add_comment.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `inventory_id=${item.id}&text=${encodeURIComponent(txt)}`
            }).then(() => {
                fetch('get_item.php?id=' + item.id)
                    .then(r => r.json())
                    .then(data => showDetail(data.item || data));
            });
        } else {
            alert('Напишите комментарий');
        }
    };
    
    document.getElementById('catalogView').style.display = 'none';
    document.getElementById('detailView').style.display = 'block';
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.inventory-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if(e.target.closest('.edit-item, .delete-item')) return;
            const id = this.dataset.id;
            fetch('get_item.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    if(data.success || data.id) {
                        showDetail(data.item || data);
                    } else {
                        alert('Ошибка загрузки');
                    }
                });
        });
    });
    
    document.querySelectorAll('.edit-item').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            openEditModal(this.dataset.id);
        });
    });
    
    document.querySelectorAll('.delete-item').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            if(confirm('Удалить?')) {
                fetch('delete_item.php?id=' + this.dataset.id).then(() => location.reload());
            }
        });
    });
    
    document.getElementById('addInventoryBtn').onclick = () => openEditModal();
    
    document.getElementById('backToCatalogBtn').onclick = () => {
        document.getElementById('catalogView').style.display = 'grid';
        document.getElementById('detailView').style.display = 'none';
    };
    
    document.getElementById('saveItemBtn').onclick = saveItem;
    document.getElementById('closeModalBtn').onclick = closeModal;
});