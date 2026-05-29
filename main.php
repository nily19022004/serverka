<?php
// Переменные, передаваемые из контроллера
$content ??= '';      // Основное содержимое страницы
$title ??= null;      // Заголовок страницы

// Формируем финальный заголовок
$pageTitle = $title ?? 'Мой блог';
if (trim($pageTitle) === '') {
    $pageTitle = 'Мой блог';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <!-- Заголовок страницы с защитой от XSS -->
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/styles/styles.css">
</head>
<body>
<!-- Табличная вёрстка для layout'а -->
<table class="layout">
    <tr>
        <!-- Шапка сайта -->
        <td colspan="2" class="header">
            <?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>
            <div class="header-sub">Персональный сайт</div>
        </td>
    </tr>
    <tr>
        <!-- Основной контент (подставляется из view) -->
        <td class="main-content">
            <?= $content ?>
        </td>
        <!-- Боковое меню с навигацией -->
        <td class="sidebar">
            <div class="sidebarHeader">Меню</div>
            <ul>
                <li><a href="/">Главная страница</a></li>
                <li><a href="/articles">Все статьи</a></li>
                <li><a href="/articles/create">Написать статью</a></li>
                <li><a href="/articles/1">Статья №1</a></li>
                <li><a href="/articles/2">Статья №2</a></li>
            </ul>
        </td>
    </tr>
    <tr>
        <!-- Подвал с динамическим годом -->
        <td class="footer" colspan="2">© <?= date('Y') ?> Мой блог — Все права защищены</td>
    </tr>
</table>
</body>
</html>