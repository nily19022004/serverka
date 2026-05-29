<?php

declare(strict_types=1);

// Базовый путь для маршрутизации (префикс URL)
$basePath = '/lab-09';

// Проверяет, начинается ли строка с заданного префикса
function startsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

// Нормализует путь запроса, отрезая базовый путь
function normalizePath(string $path, string $basePath): string
{
    // Если запрошен базовый путь - возвращаем корень
    if ($path === $basePath) {
        return '/';
    }

    // Отрезаем базовый путь, если он есть в начале
    if (startsWith($path, $basePath . '/')) {
        $path = substr($path, strlen($basePath));
    }

    return $path === '' ? '/' : $path;
}

// Отдаёт статические файлы (CSS, JS, изображения) напрямую, минуя PHP
function serveStaticFile(string $path, string $basePath): void
{
    // Нормализуем путь
    $path = normalizePath($path, $basePath);

    // Корневые директории проекта
    $projectRoot = realpath(__DIR__ . '/..');
    $labRoot = realpath(__DIR__);

    // Возможные пути к файлу
    $candidates = [
        realpath(__DIR__ . $path),
        realpath(__DIR__ . '/..' . $path),
    ];

    foreach ($candidates as $file) {
        // Пропускаем, если файл не существует
        if ($file === false || !is_file($file)) {
            continue;
        }

        // Безопасность: файл должен быть внутри проекта
        $isLabFile = $labRoot !== false && startsWith($file, $labRoot);
        $isProjectFile = $projectRoot !== false && startsWith($file, $projectRoot);

        if (!$isLabFile && !$isProjectFile) {
            continue;
        }

        // Определяем MIME-тип по расширению файла
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        $contentTypes = [
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
        ];

        // Устанавливаем правильный Content-Type
        if (isset($contentTypes[$extension])) {
            header('Content-Type: ' . $contentTypes[$extension]);
        }

        // Отдаём файл и завершаем скрипт
        readfile($file);
        exit;
    }
}

// Получаем путь из URI запроса
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

// Пытаемся отдать статический файл
serveStaticFile($requestPath, $basePath);

// Если не статика - передаём управление index.php
require __DIR__ . '/index.php';
