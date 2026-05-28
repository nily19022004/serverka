<?php declare(strict_types=1);

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

$basePath = '/lab9';

function routeStartsWith(string $text, string $prefix): bool {
    return substr($text, 0, strlen($prefix)) === $prefix;
}

function getRoutePath(string $basePath): string {
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

if ($path === '/' || $path === '/articles') {
    $controller->index();
    exit;
}

if ($path === '/articles/create') {
    $controller->create();
    exit;
}

if (preg_match('#^/articles/(\d+)$#', $path, $matches)) {
    $articleId = (int) $matches[1];
    $controller->show($articleId);
    exit;
}

$controller->notFound();