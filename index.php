<?php
session_start();
$isTeacher = isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'teacher' || $_SESSION['user_role'] === 'admin');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Спортивный клуб ЗПТ | Главная</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #fef7e6;
            color: #2d2d2d;
            line-height: 1.5;
        }
        :root {
            --bordeaux: #6b1a2a;
            --bordeaux-light: #8a2538;
            --bordeaux-dark: #4d1220;
            --shadow: 0 20px 35px -12px rgba(0,0,0,0.1);
            --border-radius-lg: 32px;
            --border-radius-md: 24px;
        }
        h1, h2, h3 {
            font-family: 'Montserrat', sans-serif;
        }
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        /* кнопка входа */
        .auth-btn {
            position: fixed;
            top: 20px;
            right: 30px;
            background-color: var(--bordeaux);
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 500;
            font-family: 'Roboto', sans-serif;
            transition: 0.2s;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .auth-btn:hover {
            background-color: var(--bordeaux-light);
        }
        /* шапка */
        .hero-block {
            margin: 2rem auto 0 auto;
            max-width: 1300px;
            padding: 0 1.5rem;
        }
        .hero-image {
            background-image: url('image/photo1.png');
            background-size: cover;
            background-position: center;
            border-radius: 32px;
            position: relative;
            min-height: 360px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            box-shadow: var(--shadow);
            padding-bottom: 2rem;
        }
        .nav-rectangle {
            background-color: rgba(107, 26, 42, 0.94);
            backdrop-filter: blur(6px);
            padding: 1rem 2.5rem;
            border-radius: 60px;
            display: flex;
            gap: 3rem;
            justify-content: center;
            flex-wrap: wrap;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
            margin-bottom: -1rem;
        }
        .nav-rectangle a {
            color: white;
            text-decoration: none;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            padding: 0.5rem 0;
            transition: 0.2s;
            border-bottom: 2px solid transparent;
        }
        .nav-rectangle a:hover, .nav-rectangle a.active {
            border-bottom-color: #f5cf9e;
            color: #f5cf9e;
        }
        /* блоки */
        .white-card {
            background: white;
            border-radius: var(--border-radius-md);
            padding: 2rem;
            margin-bottom: 3rem;
            box-shadow: var(--shadow);
        }
        .style-card {
            background: white;
            border-radius: var(--border-radius-lg);
            margin-bottom: 3rem;
            display: flex;
            flex-wrap: wrap;
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .style-text {
            flex: 1.2;
            padding: 2.5rem;
        }
        .style-photo {
            flex: 0.9;
            background-image: url('image/photo2.png');
            background-size: cover;
            background-position: center;
            min-height: 280px;
        }
        .gym-showcase {
            position: relative;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            margin-bottom: 3rem;
            box-shadow: var(--shadow);
        }
        .gym-bg {
            width: 100%;
            height: 500px;
            object-fit: cover;
            display: block;
        }
        .full-width-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(90deg, rgba(107,26,42,0.95) 0%, rgba(107,26,42,0.92) 100%);
            backdrop-filter: blur(5px);
            padding: 2rem 2.5rem;
            color: white;
        }
        .full-width-overlay h3 {
            color: #f5cf9e;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }
        .full-width-overlay p {
            max-width: 90%;
        }
        footer {
            background-color: var(--bordeaux-dark);
            color: #e0cbd0;
            padding: 2.5rem 2rem 1.5rem;
            margin-top: 2rem;
        }
        .footer-container {
            max-width: 1300px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 2rem;
        }
        .footer-nav {
            display: flex;
            flex-direction: row;
            gap: 1.5rem;
        }
        .footer-nav a {
            color: #efcfd6;
            text-decoration: none;
            transition: 0.2s;
        }
        .footer-nav a:hover {
            color: #f5cf9e;
        }
        .copyright {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.2);
            font-size: 0.8rem;
        }
        
        /* ===== АДАПТИВ ===== */
        
        /* Планшеты (до 992px) */
        @media (max-width: 992px) {
            .container {
                padding: 0 1.2rem;
            }
            .style-text {
                padding: 1.8rem;
            }
            .full-width-overlay {
                padding: 1.5rem;
            }
            .full-width-overlay h3 {
                font-size: 1.5rem;
            }
        }
        
        /* Мобильные устройства (до 768px) */
        @media (max-width: 768px) {
            .hero-block {
                margin: 1rem auto 0;
                padding: 0 1rem;
            }
            .hero-image {
                min-height: 250px;
                border-radius: 24px;
            }
            .nav-rectangle {
                padding: 0.6rem 1.2rem;
                gap: 1.2rem;
                border-radius: 40px;
                margin-bottom: -0.8rem;
            }
            .nav-rectangle a {
                font-size: 0.9rem;
                padding: 0.3rem 0;
            }
            /* кнопка входа */
            .auth-btn {
                position: fixed;
                top: auto;
                bottom: 20px;
                right: 20px;
                left: auto;
                font-size: 0.85rem;
                padding: 0.4rem 1rem;
                box-shadow: 0 2px 12px rgba(0,0,0,0.3);
                z-index: 1000;
            }
            /* заголовки */
            h1 {
                font-size: 1.6rem;
            }
            h2 {
                font-size: 1.3rem;
            }
            /* белые карточки */
            .white-card {
                padding: 1.2rem;
                margin-bottom: 1.5rem;
                border-radius: 20px;
            }
            /* блок с оторванным углом */
            .style-card {
                flex-direction: column;
                border-radius: 24px;
                margin-bottom: 1.5rem;
            }
            .style-text {
                padding: 1.5rem;
                order: 1;
            }
            .style-photo {
                min-height: 200px;
                order: 0;
                border-radius: 24px 24px 0 0;
                background-size: cover;
                background-position: center;
            }
            /* блок с залом */
            .gym-showcase {
                border-radius: 24px;
                margin-bottom: 1.5rem;
            }
            .gym-bg {
                height: 350px;
            }
            .full-width-overlay {
                position: relative;
                padding: 1.2rem;
            }
            .full-width-overlay h3 {
                font-size: 1.3rem;
            }
            .full-width-overlay p {
                max-width: 100%;
                font-size: 0.9rem;
            }
            /* подвал */
            footer {
                padding: 1.5rem;
            }
            .footer-container {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            .footer-nav {
                justify-content: center;
                gap: 1.5rem;
            }
        }
        
        /* Маленькие телефоны (до 480px) */
        @media (max-width: 480px) {
            .hero-image {
                min-height: 200px;
                border-radius: 20px;
            }
            .nav-rectangle {
                padding: 0.5rem 1rem;
                gap: 0.8rem;
            }
            .nav-rectangle a {
                font-size: 0.8rem;
            }
            .auth-btn {
                bottom: 15px;
                right: 15px;
                font-size: 0.75rem;
                padding: 0.3rem 0.8rem;
            }
            h1 {
                font-size: 1.3rem;
            }
            h2 {
                font-size: 1.1rem;
            }
            .white-card {
                padding: 1rem;
            }
            .style-text {
                padding: 1rem;
            }
            .style-text p {
                font-size: 0.9rem;
            }
            .style-photo {
                min-height: 160px;
            }
            .gym-bg {
                height: 250px;
            }
            .full-width-overlay {
                padding: 1rem;
            }
            .full-width-overlay h3 {
                font-size: 1.1rem;
            }
            .full-width-overlay p {
                font-size: 0.8rem;
            }
            .footer-nav {
                gap: 1rem;
            }
            .footer-nav a {
                font-size: 0.85rem;
            }
            .copyright {
                font-size: 0.7rem;
            }
        }
        
        /* Планшеты горизонтально (до 1024px) */
        @media (min-width: 769px) and (max-width: 1024px) {
            .style-text {
                padding: 2rem;
            }
            .gym-bg {
                height: 400px;
            }
            .full-width-overlay {
                padding: 1.5rem;
            }
            .full-width-overlay h3 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<!-- кнопка входа -->
<?php if ($isTeacher): ?>
    <a href="logout.php" class="auth-btn">выход</a>
<?php else: ?>
    <a href="login.php" class="auth-btn">вход</a>
<?php endif; ?>

<div class="hero-block">
    <div class="hero-image">
        <div class="nav-rectangle">
            <a href="index.php" class="active">Главная</a>
            <a href="catalog.php">Каталог</a>
            <a href="news.php">Новости</a>
        </div>
    </div>
</div>

<main>
    <div class="container" style="margin-top: 2rem;">
        <div class="white-card">
            <h1 style="font-size: 2.6rem;">Добро пожаловать в Спортивный клуб ЗПТ</h1>
            <p style="font-size: 1.2rem;">Твой надёжный помощник в мире спорта и активного образа жизни.</p>
        </div>

        <div class="white-card">
            <h2>О проекте</h2>
            <p>Интерактивный ресурс создан, чтобы студенты и преподаватели всегда знали, какой спортивный инвентарь есть в зале. Здесь можно узнать технику упражнений, оставить анонимное сообщение о неисправности, а учителя физкультуры — оперативно добавлять и редактировать данные.</p>
            <p style="margin-top: 1rem;"><strong>Основной сайт техникума:</strong> <a href="http://zpt.edu22.info/?page_id=4" target="_blank" style="color: var(--bordeaux);">zpt.edu22.info</a></p>
        </div>

        <div class="style-card">
            <div class="style-text">
                <h2>Физкультура — это стиль жизни</h2>
                <p>Наши преподаватели и тренеры вдохновляют студентов на подвиги. Регулярные занятия в спортзале укрепляют здоровье, повышают настроение и развивают командный дух.</p>
                <p>Мы гордимся, что в «Заринском политехническом техникуме» спорт стал важной частью образования. Присоединяйся к Спортивному клубу ЗПТ!</p>
            </div>
            <div class="style-photo"></div>
        </div>

        <div class="gym-showcase">
            <img class="gym-bg" src="image/photo3.png" alt="Спортивный зал ЗПТ">
            <div class="full-width-overlay">
                <h3>🏆 Наш спортивный зал — гордость техникума</h3>
                <p>Просторное светлое помещение площадью более 400 м² с профессиональным покрытием. В зале есть зоны для игровых видов спорта (футбол, баскетбол, волейбол), сектор для гимнастики и отдельная зона с кардио- и силовым оборудованием. В арсенале: футбольные и баскетбольные мячи (30+ штук), скакалки (25 пар), гимнастические козлы, конусы для дриблинга (20 шт.), координационные лестницы, маты, шведские стенки и турники. Регулярно проводится обновление инвентаря. Тёплые раздевалки, душевые и зона отдыха сделают тренировки максимально комфортными. Приходи — каждый студент найдёт занятие по душе!</p>
            </div>
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

</body>
</html>