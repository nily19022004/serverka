<?php

declare(strict_types=1);

spl_autoload_register(function (string $className): void {
    $paths = [
        __DIR__ . '/src/Controllers/' . $className . '.php',
        __DIR__ . '/src/Database/'    . $className . '.php',
        __DIR__ . '/src/Models/'      . $className . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }
});

$basePath = '/course';

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

$path       = getRoutePath($basePath);
$controller = new RecipesController();

if ($path === '/' || $path === '/recipes') {
    $controller->index();
    exit;
}

if ($path === '/recipes/create') {
    $controller->create();
    exit;
}

if (preg_match('~^/recipes/(\d+)/edit$~', $path, $m)) {
    $controller->edit((int) $m[1]);
    exit;
}

if (preg_match('~^/recipes/(\d+)/delete$~', $path, $m)) {
    $controller->delete((int) $m[1]);
    exit;
}

if (preg_match('~^/recipes/(\d+)/comments/(\d+)/delete$~', $path, $m)) {
    $controller->deleteComment((int) $m[1], (int) $m[2]);
    exit;
}

if (preg_match('~^/recipes/(\d+)$~', $path, $m)) {
    $controller->show((int) $m[1]);
    exit;
}

$controller->notFound();
