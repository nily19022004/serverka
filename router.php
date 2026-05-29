<?php
// D:\Desktop\study\2 семестр\serv\router.php
// Точка входа для веб-сервера (обрабатывает и статические файлы, и PHP-маршруты)

declare(strict_types=1);

$basePath = '/course';

/**
 * Проверяет, начинается ли строка с указанного префикса
 */
function startsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

/**
 * Нормализует путь, отбрасывая базовый путь '/course'
 */
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

/**
 * Отдаёт статические файлы (CSS, JS, изображения)
 * Если запрошенный файл существует — отправляет его с правильным Content-Type и завершает скрипт
 */
function serveStaticFile(string $path, string $basePath): void
{
    $path = normalizePath($path, $basePath);

    $projectRoot = realpath(__DIR__ . '/..');   // Корень проекта (на уровень выше)
    $labRoot     = realpath(__DIR__);            // Текущая папка (serv)

    // Возможные расположения файла (относительно текущей папки или корня проекта)
    $candidates = [
        realpath(__DIR__ . $path),
        realpath(__DIR__ . '/..' . $path),
    ];

    foreach ($candidates as $file) {
        if ($file === false || !is_file($file)) {
            continue;   // Файл не существует — проверяем следующий кандидат
        }

        // Безопасность: файл должен лежать внутри lab (serv) или внутри корня проекта
        $isLabFile     = $labRoot     !== false && startsWith($file, $labRoot);
        $isProjectFile = $projectRoot !== false && startsWith($file, $projectRoot);

        if (!$isLabFile && !$isProjectFile) {
            continue;   // Файл вне разрешённых директорий — игнорируем
        }

        // MIME-типы для разных расширений файлов
        $contentTypes = [
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
        ];

        $ext = pathinfo($file, PATHINFO_EXTENSION);   // Расширение файла

        if (isset($contentTypes[$ext])) {
            header('Content-Type: ' . $contentTypes[$ext]);   // Устанавливаем правильный Content-Type
        }

        readfile($file);   // Отдаём содержимое файла
        exit;              // Завершаем скрипт (дальнейшая обработка не нужна)
    }
}

// Получаем путь из URI
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

// Пробуем отдать статический файл (если это CSS, JS, изображение)
serveStaticFile($requestPath, $basePath);

// Если это не статический файл — передаём управление в index.php (маршрутизация приложения)
require __DIR__ . '/index.php';