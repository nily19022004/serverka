<?php

declare(strict_types=1); // Строгая типизация — PHP будет ругаться, если передать не тот тип

// Подключаем контроллер — он содержит всю логику страниц
require __DIR__ . '/controllers/BlogController.php';

// Базовый путь, на котором развёрнута лаба на сервере
// Нужен, чтобы роутер правильно отрезал префикс и работал с чистыми путями
$basePath = '/lab-07-routing';

// Вспомогательная функция: проверяет, начинается ли строка с нужного префикса
function routeStartsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

// Возвращает «чистый» путь запроса без basePath
// Например: /lab-07-routing/about-me → /about-me
function getRoutePath(string $basePath): string
{
    // Берём путь из URL (без query string и фрагментов)
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

    // Если путь совпадает с basePath — это главная страница
    if ($path === $basePath) {
        return '/';
    }

    // Если путь начинается с basePath/ — отрезаем префикс
    if (routeStartsWith($path, $basePath . '/')) {
        $path = substr($path, strlen($basePath));
    }

    return $path === '' ? '/' : $path;
}

$path = getRoutePath($basePath);

// Создаём контроллер — один объект на весь запрос
$controller = new BlogController();

// Роутинг: сравниваем путь и вызываем нужный метод контроллера

// Главная страница — список статей
if ($path === '/') {
    $controller->index();
    exit;
}

// Страница «Обо мне»
if ($path === '/about-me') {
    $controller->aboutMe();
    exit;
}

// Страница прощания с динамическим именем в URL
// Регулярка захватывает всё после /bye/ как имя (любые символы кроме /)
// Флаг #u — поддержка UTF-8 (кириллица в URL)
if (preg_match('#^/bye/([^/]+)$#u', $path, $matches)) {
    $name = rawurldecode($matches[1]); // Декодируем URL-кодирование (%D0%98%D0%B2%D0%B0%D0%BD → Влад)
    $controller->sayBye($name);
    exit;
}

// Страница приветствия с динамическим именем в URL
if (preg_match('#^/hello/([^/]+)$#u', $path, $matches)) {
    $name = rawurldecode($matches[1]);
    $controller->sayHello($name);
    exit;
}

// Ни один маршрут не подошёл — отдаём 404
$controller->notFound();