<?php

require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../models/User.php';

class ArticlesController
{
    // Главная - список всех статей
    public function index()
    {
        $articles = Article::findAll();
        
        $this->render('articles/index.php', [
            'articles' => $articles
        ]);
    }
    
    // Страница одной статьи
    public function show($id)
    {
        $article = Article::findById($id);
        
        if ($article === null) {
            $this->notFound();
            return;
        }
        
        // Получаем автора статьи из таблицы users
        $author = User::findById($article->authorId);
        
        $this->render('articles/show.php', [
            'article' => $article,
            'author' => $author
        ], $article->name);
    }
    
    // Показать форму добавления статьи
    public function create()
    {
        // Получаем список всех пользователей для выбора автора
        $users = User::findAll();
        
        $this->render('articles/create.php', [
            'users' => $users
        ], 'Добавить статью');
    }
    
    // Сохранить новую статью в базу данных
    public function store()
    {
        // Проверяем, что форма отправлена методом POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /blog/index.php?page=create');
            exit;
        }
        
        // Получаем данные из формы
        $name = $_POST['name'] ?? '';
        $text = $_POST['text'] ?? '';
        $authorId = (int) ($_POST['author_id'] ?? 1);
        
        // Проверяем, что поля не пустые
        $errors = [];
        
        if (trim($name) === '') {
            $errors[] = 'Название статьи не может быть пустым';
        }
        
        if (trim($text) === '') {
            $errors[] = 'Текст статьи не может быть пустым';
        }
        
        // Если есть ошибки, показываем форму снова
        if (!empty($errors)) {
            $users = User::findAll();
            $this->render('articles/create.php', [
                'errors' => $errors,
                'name' => $name,
                'text' => $text,
                'users' => $users
            ], 'Добавить статью');
            return;
        }
        
        // Сохраняем статью в базу данных
        $success = Article::create($name, $text, $authorId);
        
        if ($success) {
            // Перенаправляем на главную страницу с сообщением об успехе
            header('Location: /blog/index.php?page=articles&success=1');
            exit;
        } else {
            $users = User::findAll();
            $this->render('articles/create.php', [
                'errors' => ['Ошибка при сохранении статьи'],
                'name' => $name,
                'text' => $text,
                'users' => $users
            ], 'Добавить статью');
        }
    }
    
    // ========= НОВЫЙ МЕТОД: УДАЛЕНИЕ СТАТЬИ =========
    public function delete($id)
    {
        // Находим статью
        $article = Article::findById($id);
        
        if ($article === null) {
            $this->notFound();
            return;
        }
        
        // Удаляем статью
        $success = Article::delete($id);
        
        if ($success) {
            // Перенаправляем на список статей с сообщением об успехе
            header('Location: /blog/index.php?page=articles&deleted=1');
            exit;
        } else {
            // Если ошибка, показываем страницу с ошибкой
            $this->render('articles/show.php', [
                'article' => $article,
                'author' => User::findById($article->authorId),
                'error' => 'Ошибка при удалении статьи'
            ], $article->name);
        }
    }
    
    // Страница 404
    public function notFound()
    {
        http_response_code(404);
        
        $this->render('articles/show.php', [
            'article' => null,
            'author' => null
        ], '404');
    }
    
    // Подключение шаблона
    private function render($view, $params = [], $title = null)
    {
        extract($params);
        
        ob_start();
        require __DIR__ . '/../views/' . $view;
        $content = ob_get_clean();
        
        global $title;
        $title = $title;
        require __DIR__ . '/../main.php';
    }
}