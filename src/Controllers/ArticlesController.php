<?php

declare(strict_types=1);

class ArticlesController
{
    // Главная страница - список всех статей
    public function index(): void
    {
        $articles = Article::findAll();

        $this->render('articles/index.php', [
            'articles' => $articles,
        ]);
    }

    // Просмотр одной статьи по ID
    public function show(int $id): void
    {
        $article = Article::findById($id);

        if ($article === null) {
            $this->notFound();
            return;
        }

        // Получаем автора статьи из таблицы users (связь через author_id)
        $author = User::findById($article->authorId);

        $this->render('articles/show.php', [
            'article' => $article,
            'author'  => $author,
        ], $article->name);
    }

    // Создание новой статьи
    public function create(): void
    {
        // POST — сохраняем новую статью и редиректим на неё
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $text = trim($_POST['text'] ?? '');

            if ($name !== '' && $text !== '') {
                // author_id = 1 (admin) — заглушка до появления авторизации
                $article = Article::insert(1, $name, $text);
                header('Location: /articles/' . $article->id);
                exit;
            }

            // Если форма не заполнена — снова показываем форму с ошибкой
            $this->render('articles/create.php', [
                'error' => 'Пожалуйста, заполните все поля.',
                'name'  => $name,
                'text'  => $text,
            ], 'Новая статья');
            return;
        }

        // GET — показываем пустую форму
        $this->render('articles/create.php', [
            'error' => null,
            'name'  => '',
            'text'  => '',
        ], 'Новая статья');
    }

    // Редактирование статьи
    public function edit(int $id): void
    {
        $article = Article::findById($id);

        if ($article === null) {
            $this->notFound();
            return;
        }

        // POST — сохраняем изменения и редиректим обратно на статью
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $text = trim($_POST['text'] ?? '');

            if ($name !== '' && $text !== '') {
                $article->name = $name;
                $article->text = $text;
                $article->save();
            }

            header('Location: /articles/' . $id);
            exit;
        }

        // GET — показываем форму редактирования
        $this->render('articles/edit.php', [
            'article' => $article,
        ], 'Редактирование: ' . $article->name);
    }

    // Страница 404 - статья не найдена
    public function notFound(): void
    {
        http_response_code(404);

        $this->render('articles/show.php', [
            'article' => null,
            'author'  => null,
        ], '404');
    }

    // Вспомогательный метод для рендеринга представлений
    private function render(string $view, array $params = [], ?string $title = null): void
    {
        // Превращает ключи массива в переменные
        extract($params);

        // Начинаем буферизацию вывода
        ob_start();
        require __DIR__ . '/../../views/' . $view;
        $content = ob_get_clean();

        // Подключаем основной шаблон
        require __DIR__ . '/../../main.php';
    }
}