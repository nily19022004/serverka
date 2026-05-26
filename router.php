<?php

declare(strict_types=1);

// Базовый путь лабораторной на сервере — нужен для корректного разбора URL
$basePath = '/lab-07-routing';

// Проверяет, начинается ли строка с нужного префикса
function startsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

// Убирает basePath из начала пути, возвращая «чистый» путь
// Например: /lab-08-view/style.css → /style.css
function normalizePath(string $path, string $basePath): string
{
    if ($path === $basePath) {
        return '/';
    }

    if (startsWith($path, $basePath . '/')) {
        $path = substr($path, strlen($basePath));
    }

    return $path === '' ? '/' : $path;
}

// Отдаёт статические файлы (CSS, JS, картинки) напрямую, минуя PHP-логику.
// Если файл найден — отправляет его с правильным Content-Type и завершает скрипт.
function serveStaticFile(string $path, string $basePath): void
{
    $path = normalizePath($path, $basePath);

    // Корни, в которых разрешено искать файлы (защита от path traversal)
    $projectRoot = realpath(__DIR__ . '/..');
    $labRoot = realpath(__DIR__);

    // Ищем файл сначала в папке лабы, потом в корне проекта
    $candidates = [
        realpath(__DIR__ . $path),
        realpath(__DIR__ . '/..' . $path),
    ];

    foreach ($candidates as $file) {
        // Пропускаем несуществующие пути
        if ($file === false || !is_file($file)) {
            continue;
        }

        // Проверяем, что файл находится внутри разрешённых папок
        $isLabFile = $labRoot !== false && startsWith($file, $labRoot);
        $isProjectFile = $projectRoot !== false && startsWith($file, $projectRoot);

        if (!$isLabFile && !$isProjectFile) {
            continue;
        }

        // Определяем Content-Type по расширению файла
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        $contentTypes = [
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
        ];

        if (isset($contentTypes[$extension])) {
            header('Content-Type: ' . $contentTypes[$extension]);
        }

        // Отправляем содержимое файла и выходим — PHP дальше не нужен
        readfile($file);
        exit;
    }

    // Файл не найден — продолжаем выполнение, пойдём в index.php
}

// Получаем путь из текущего запроса (без параметров ?foo=bar)
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

// Пробуем отдать статику. Если не статика — идём дальше
serveStaticFile($requestPath, $basePath);

// Передаём управление роутеру/контроллеру
require __DIR__ . '/index.php';