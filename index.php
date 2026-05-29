<?php
// D:\Desktop\study\2 семестр\serv\index.php
// Точка входа в приложение (front controller)
// Маршрутизация всех HTTP-запросов

declare(strict_types=1);

/**
 * Автозагрузчик классов
 * Автоматически подключает файлы с классами по их имени
 */
spl_autoload_register(function (string $className): void {
    // Возможные пути, где может находиться класс
    $paths = [
        __DIR__ . '/src/Controllers/' . $className . '.php',  // Контроллеры
        __DIR__ . '/src/Database/'    . $className . '.php',  // Database
        __DIR__ . '/src/Models/'      . $className . '.php',  // Модели
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;   // Подключаем найденный файл
            return;
        }
    }
});

// Базовый путь к приложению (подпапка на сервере)
$basePath = '/course';

/**
 * Проверяет, начинается ли строка с указанного префикса
 */
function routeStartsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

/**
 * Извлекает путь из URI, отбрасывая базовый путь '/course'
 */
function getRoutePath(string $basePath): string
{
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

    // Если запрос идёт ровно на базовый путь — возвращаем '/'
    if ($path === $basePath) {
        return '/';
    }

    // Если путь начинается с /course/ — отрезаем префикс
    if (routeStartsWith($path, $basePath . '/')) {
        $path = substr($path, strlen($basePath));
    }

    return $path === '' ? '/' : $path;
}

// Получаем "чистый" путь (без /course)
$path       = getRoutePath($basePath);
$controller = new RecipesController();   // Создаём контроллер

// Маршрутизация: сопоставляем URL с методами контроллера

// Главная страница / список рецептов
if ($path === '/' || $path === '/recipes') {
    $controller->index();
    exit;
}

// Форма создания нового рецепта
if ($path === '/recipes/create') {
    $controller->create();
    exit;
}

// Редактирование рецепта: /recipes/{id}/edit
if (preg_match('~^/recipes/(\d+)/edit$~', $path, $m)) {
    $controller->edit((int) $m[1]);
    exit;
}

// Удаление рецепта: /recipes/{id}/delete
if (preg_match('~^/recipes/(\d+)/delete$~', $path, $m)) {
    $controller->delete((int) $m[1]);
    exit;
}

// Удаление комментария: /recipes/{recipeId}/comments/{commentId}/delete
if (preg_match('~^/recipes/(\d+)/comments/(\d+)/delete$~', $path, $m)) {
    $controller->deleteComment((int) $m[1], (int) $m[2]);
    exit;
}

// Просмотр одного рецепта: /recipes/{id}
if (preg_match('~^/recipes/(\d+)$~', $path, $m)) {
    $controller->show((int) $m[1]);
    exit;
}

// Если ни один маршрут не подошёл — страница 404
$controller->notFound();