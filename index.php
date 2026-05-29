<?php declare(strict_types=1);

// Автозагрузка классов: ищет файлы в папках Controllers, Database, Models
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

// Базовый путь для маршрутизации
$basePath = '/lab9';

// Проверяет, начинается ли строка с префикса
function routeStartsWith(string $text, string $prefix): bool {
    return substr($text, 0, strlen($prefix)) === $prefix;
}

// Получает путь из URI, отрезая базовый путь
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

// Определяем текущий маршрут и вызываем соответствующий метод контроллера
$path = getRoutePath($basePath);
$controller = new ArticlesController();

// Главная страница / список статей
if ($path === '/' || $path === '/articles') {
    $controller->index();
    exit;
}

// Форма создания статьи
if ($path === '/articles/create') {
    $controller->create();
    exit;
}

// Просмотр конкретной статьи по ID (например /articles/5)
if (preg_match('#^/articles/(\d+)$#', $path, $matches)) {
    $articleId = (int) $matches[1];
    $controller->show($articleId);
    exit;
}

// Если ничего не подошло - 404
$controller->notFound();
