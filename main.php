<?php
$content  ??= '';
$title    ??= null;
$pageTitle = $title ?? 'Кулинарный сайт';
if (trim($pageTitle) === '') {
    $pageTitle = 'Кулинарный сайт';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> — Рецепты</title>
    <link rel="stylesheet" href="/styles/styles.css">
</head>
<body>
<table class="layout">
    <tr>
        <td colspan="2" class="header">
            <a href="/recipes" class="header-logo">Кулинарный сайт</a>
            <div class="header-sub">Лучшие рецепты на каждый день</div>
        </td>
    </tr>
    <tr>
        <td class="main-content">
            <?= $content ?>
        </td>
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
        <td class="footer" colspan="2">© <?= date('Y') ?> Кулинарный сайт — Все права защищены</td>
    </tr>
</table>
</body>
</html>
