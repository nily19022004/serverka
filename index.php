<?php

declare(strict_types=1);

// Автозагрузчик классов — ищет файл по имени класса в трёх папках
spl_autoload_register(function (string $className): void {
    $paths = [
        __DIR__ . '/src/Controllers/' . $className . '.php',
        __DIR__ . '/src/Database/' . $className . '.php',
        __DIR__ . '/src/Models/' . $className . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }
});

$basePath = '/lab-10';

function routeStartsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

function getRoutePath(string $basePath): string
{
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

    if ($path === $basePath) {
        return '/';
    }

    if (routeStartsWith($path, $basePath . '/')) {
        $path = substr($path, strlen($basePath));
    }

    return $path === '' ? '/' : $path;
}

$path = getRoutePath($basePath);

$controller = new ArticlesController();

// Главная и /articles — список всех статей
if ($path === '/' || $path === '/articles') {
    $controller->index();
    exit;
}

// /articles/create — форма создания новой статьи (GET) и сохранение (POST)
// Маршрут стоит ДО /articles/{id}/edit и /articles/{id}, чтобы "create"
// не попало под числовой паттерн
if ($path === '/articles/create') {
    $controller->create();
    exit;
}

// /articles/{id}/edit — форма редактирования (GET) и сохранение (POST)
// Маршрут стоит ДО /articles/{id}, чтобы "edit" не попало под числовой паттерн
if (preg_match('~^/articles/(\d+)/edit$~', $path, $matches)) {
    $articleId = (int) $matches[1];
    $controller->edit($articleId);
    exit;
}

// /articles/{id} — страница конкретной статьи
if (preg_match('#^/articles/(\d+)$#', $path, $matches)) {
    $articleId = (int) $matches[1];
    $controller->show($articleId);
    exit;
}

$controller->notFound();
