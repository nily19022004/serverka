<?php
// D:\Desktop\study\2 семестр\serv\main.php
// Главный шаблон (layout) — обёртка для всех страниц
// Содержит общие элементы: хедер, сайдбар, футер

// Задаём значения по умолчанию, если они не переданы из контроллера
$content  ??= '';           // HTML-код конкретной страницы
$title    ??= null;         // Заголовок страницы (для <title>)
$pageTitle = $title ?? 'Кулинарный сайт';   // Если title не задан — используем "Кулинарный сайт"
if (trim($pageTitle) === '') {
    $pageTitle = 'Кулинарный сайт';          // Защита от пустого заголовка
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> — Рецепты</title>
    <link rel="stylesheet" href="/styles/styles.css">   <!-- Подключаем CSS -->
</head>
<body>
<!-- Табличная вёрстка для структуры страницы (хедер, контент, сайдбар, футер) -->
<table class="layout">
    <tr>
        <td colspan="2" class="header">
            <!-- Логотип-ссылка на главную -->
            <a href="/recipes" class="header-logo">Кулинарный сайт</a>
            <div class="header-sub">Лучшие рецепты на каждый день</div>
        </td>
    </tr>
    <tr>
        <!-- Основной контент (сюда вставляется HTML из $content) -->
        <td class="main-content">
            <?= $content ?>
        </td>
        
        <!-- Боковая панель (сайдбар) с навигацией -->
        <td class="sidebar">
            <div class="sidebarHeader">Навигация</div>
            <ul>
                <li><a href="/recipes">Все рецепты</a></li>
                <li><a href="/recipes/create">+ Добавить рецепт</a></li>
            </ul>
            <div class="sidebarHeader" style="margin-top:20px">Сложность</div>
            <ul>
                <li><a href="/recipes?difficulty=1">★☆☆ Лёгкие</a></li>
                <li><a href="/recipes?difficulty=2">★★☆ Средние</a></li>
                <li><a href="/recipes?difficulty=3">★★★ Сложные</a></li>
            </ul>
        </td>
    </tr>
    <tr>
        <!-- Подвал (футер) с копирайтом -->
        <td class="footer" colspan="2">© <?= date('Y') ?> Кулинарный сайт — Все права защищены</td>
    </tr>
</table>
</body>
</html>