<?php

declare(strict_types=1);

$basePath = '/lab-09';

function startsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

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

function serveStaticFile(string $path, string $basePath): void
{
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

        $isLabFile = $labRoot !== false && startsWith($file, $labRoot);
        $isProjectFile = $projectRoot !== false && startsWith($file, $projectRoot);

        if (!$isLabFile && !$isProjectFile) {
            continue;
        }

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

        readfile($file);
        exit;
    }
}

$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

serveStaticFile($requestPath, $basePath);

require __DIR__ . '/index.php';
