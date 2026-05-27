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
        
        // ПОЛУЧАЕМ АВТОРА СТАТЬИ ИЗ БАЗЫ ДАННЫХ
        $author = User::findById($article->authorId);
        
        $this->render('articles/show.php', [
            'article' => $article,
            'author' => $author
        ], $article->name);
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