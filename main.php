<?php $content ??= ''; $title ??= null; $pageTitle = $title ?? 'Мой блог'; if (trim($pageTitle) === '') { $pageTitle = 'Мой блог'; } ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/lab9/styles/styles.css">
</head>
<body>
<table class="layout">
    <tr>
        <td colspan="2" class="header">
            <?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>
            <div class="header-sub">Персональный сайт</div>
        </td>
    </tr>
    <tr>
        <td class="main-content">
            <?= $content ?>
        </td>
        <td class="sidebar">
            <div class="sidebarHeader">Меню</div>
            <ul>
                <li><a href="/lab9/">Главная страница</a></li>
                <li><a href="/lab9/articles">Все статьи</a></li>
                <li><a href="/lab9/articles/create">Написать статью</a></li>
                <li><a href="/lab9/articles/1">Статья №1</a></li>
                <li><a href="/lab9/articles/2">Статья №2</a></li>
            </ul>
        </td>
    </tr>
    <tr>
        <td class="footer" colspan="2">© <?= date('Y') ?> Мой блог — Все права защищены</td>
    </tr>
</table>
</body>
</html>