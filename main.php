<?php
// Защита от Warning, если файл открыть напрямую без контроллера:
// ??= присваивает значение только если переменная не определена
$content ??= '';
$title ??= null;

/** @var string $content */      // подсказка для IDE: $content — строка
/** @var string|null $title */   // $title может быть строкой или null

// Определяем заголовок страницы
// Если $title не передан или пустой — используем название блога по умолчанию
$pageTitle = $title ?? 'Мой блог';

if (trim($pageTitle) === '') {
    $pageTitle = 'Мой блог';
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <!-- htmlspecialchars экранирует спецсимволы, чтобы заголовок не сломал HTML -->
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

<!-- Общая обёртка — центрирует всё содержимое и задаёт максимальную ширину -->
<div class="wrapper">

    <!-- Шапка сайта — одинакова на всех страницах -->
    <header class="site-header">
        <div class="header-inner">
            <div class="header-text">
                <!-- Заголовок страницы дублируется в шапке -->
                <span class="blog-title"><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="blog-subtitle">Персональный сайт</span>
            </div>
        </div>
    </header>

    <!-- Основная область: навигация слева + контент справа (flexbox) -->
    <div class="content-area">

        <!-- Сайдбар с навигацией — один и тот же на всех страницах -->
        <nav class="sidebar">
            <div class="sidebarHeader">Навигация</div>
            <ul>
                <li><a href="/">Главная страница</a></li>
                <li><a href="/about-me">Обо мне</a></li>
                <!-- Кириллица в href — браузер сам закодирует её в URL -->
                <li><a href="/bye/Ниля">Страница прощания</a></li>
            </ul>
        </nav>

        <!-- Сюда подставляется $content из контроллера — уникальная часть каждой страницы -->
        <main class="main-content">
            <?= $content ?>
        </main>

    </div>

    <!-- Футер — одинаков на всех страницах, год подставляется автоматически -->
    <footer class="site-footer">
        <p>© <?= date('Y') ?> Мой блог &mdash; Все права защищены</p>
    </footer>

</div>

</body>
</html>