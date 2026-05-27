<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/models/Article.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/controllers/ArticlesController.php';

$controller = new ArticlesController();

// Получаем параметр page из URL
$page = $_GET['page'] ?? '';

// Маршрутизация через GET параметры
if ($page === '' || $page === 'articles') {
    $controller->index();
} 
elseif ($page === 'article' && isset($_GET['id'])) {
    $articleId = (int) $_GET['id'];
    $controller->show($articleId);
} 
else {
    $controller->notFound();
}