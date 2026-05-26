<?php

declare(strict_types=1);

// Контроллер отвечает за логику каждой страницы.
// Он готовит HTML-контент и заголовок, затем передаёт их в шаблон (main.php).
class BlogController
{
    // Главная страница — выводит список статей
    public function index(): void
    {
        $content = '
            <h2>Статья 1</h2>
            <p>Текст первой статьи</p>
            <hr>

            <h2>Статья 2</h2>
            <p>Текст второй статьи</p>
        ';

        // title не передаём — в шаблоне сработает заголовок по умолчанию «Мой блог»
        $this->render($content);
    }

    // Страница «Обо мне»
    public function aboutMe(): void
    {
        $content = '
            <h2>Обо мне</h2>
            <p>Привет! Это страница с краткой информацией обо мне.</p>
            <p>Текст обо мне</p>
        ';

        $this->render($content, 'Обо мне');
    }

    // Страница прощания с именем из URL
    public function sayBye(string $name): void
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        $content = '
            <h2>Страница прощания</h2>
            <p>Пока, ' . $safeName . '</p>
        ';

        $this->render($content, 'Страница прощания');
    }

    // Страница приветствия с именем из URL
    public function sayHello(string $name): void
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        $content = '
            <h2>Страница приветствия</h2>
            <p>Привет, ' . $safeName . '!</p>
            <p>Добро пожаловать на мой сайт!</p>
        ';

        $this->render($content, 'Страница приветствия');
    }

    // Страница 404 — маршрут не найден
    public function notFound(): void
    {
        // Устанавливаем HTTP-статус 404, чтобы браузер и поисковики знали: страницы нет
        http_response_code(404);

        $content = '
            <h2>404</h2>
            <p>Страница не найдена.</p>
        ';

        $this->render($content, '404');
    }

    // Приватный метод — подключает шаблон main.php.
    // $content и $title становятся доступны внутри шаблона,
    // потому что require выполняется в этом же скоупе метода.
    private function render(string $content, ?string $title = null): void
    {
        require __DIR__ . '/../main.php';
    }
}