<?php declare(strict_types=1);

$basePath = '/lab9';

// Проверяет, начинается ли строка с префикса
function startsWith(string $text, string $prefix): bool {
    return substr($text, 0, strlen($prefix)) === $prefix;
}

// Нормализует путь, отрезая базовый путь
function normalizePath(string $path, string $basePath): string {
    if ($path === $basePath) {
        return '/';
    }
    if (startsWith($path, $basePath . '/')) {
        $path = substr($path, strlen($basePath));
    }
    return $path === '' ? '/' : $path;
}

// Отдаёт статические файлы (CSS, JS, изображения) напрямую
function serveStaticFile(string $path, string $basePath): void {
    $path = normalizePath($path, $basePath);
    $projectRoot = realpath(__DIR__ . '/..');
    $labRoot = realpath(__DIR__);
    $candidates = [
        realpath(__DIR__ . $path),
        realpath(__DIR__ . '/..' . $path),
    ];
    foreach ($candidates as $file) {
        if ($file === false || !is_file($file)) {
            continue;
        }
        // Безопасность: файл должен быть в пределах проекта
        $isLabFile = $labRoot !== false && startsWith($file, $labRoot);
        $isProjectFile = $projectRoot !== false && startsWith($file, $projectRoot);
        if (!$isLabFile && !$isProjectFile) {
            continue;
        }
        // Определяем MIME-тип по расширению
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $contentTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
        ];
        if (isset($contentTypes[$extension])) {
            header('Content-Type: ' . $contentTypes[$extension]);
        }
        readfile($file);
        exit;
    }
}

// Получаем запрошенный путь и пытаемся отдать статику
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
serveStaticFile($requestPath, $basePath);
// Если не статика - передаём управление index.php
require __DIR__ . '/index.php';
