<?php
$pageTitle = $title ?? 'Мой блог';

if (trim($pageTitle) === '') {
    $pageTitle = 'Мой блог';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="/blog/style.css">
</head>
<body>

<div class="wrapper">

    <header class="site-header">
        <div class="header-inner">
            <div class="header-text">
                <span class="blog-title">Мой блог</span>
                <span class="blog-subtitle">Персональный сайт</span>
            </div>
        </div>
    </header>

    <div class="content-area">

        <nav class="sidebar">
            <div class="sidebarHeader">Навигация</div>
            <ul>
                <li><a href="/blog/index.php">Главная страница</a></li>
                <li><a href="/blog/index.php?page=articles">Статьи</a></li>
                <li><a href="/blog/index.php?page=create">Добавить статью</a></li>
                <li><hr style="margin: 10px 0;"></li>
                <li><a href="/blog/index.php?page=article&id=1">Статья №1</a></li>
                <li><a href="/blog/index.php?page=article&id=2">Статья №2</a></li>
                <li><a href="/blog/index.php?page=article&id=3">Статья №3</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <?= $content ?>
        </main>

    </div>

    <footer class="site-footer">
        <p>© <?= date('Y') ?> Мой блог — Все права защищены</p>
    </footer>

</div>

</body>
</html>