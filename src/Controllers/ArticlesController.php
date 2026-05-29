<?php declare(strict_types=1);

class ArticlesController {
    
    // Главная страница - список всех статей
    public function index(): void {
        $articles = Article::findAll();
        $this->render('articles/index.php', [
            'articles' => $articles,
        ]);
    }

    // Просмотр одной статьи по ID
    public function show(int $id): void {
        $article = Article::findById($id);
        if ($article === null) {
            $this->notFound();
            return;
        }
        // Ищем автора статьи
        $author = User::findById($article->authorId);
        $this->render('articles/show.php', [
            'article' => $article,
            'author' => $author,
        ], $article->name);
    }

    // Создание новой статьи
    public function create(): void {
        // Обработка POST-запроса (отправка формы)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $text = trim($_POST['text'] ?? '');
            
            // Проверка на заполненность полей
            if ($name !== '' && $text !== '') {
                // author_id = 1 (временно, пока нет авторизации)
                $article = Article::insert(1, $name, $text);
                header('Location: /articles/' . $article->id);
                exit;
            }
            
            // Если поля пустые - показываем форму с ошибкой
            $this->render('articles/create.php', [
                'error' => 'Пожалуйста, заполните все поля.',
                'name' => $name,
                'text' => $text,
            ], 'Новая статья');
            return;
        }
        
        // GET-запрос - просто показываем пустую форму
        $this->render('articles/create.php', [
            'error' => null,
            'name' => '',
            'text' => '',
        ], 'Новая статья');
    }

    // Страница 404 - статья не найдена
    public function notFound(): void {
        http_response_code(404);
        $this->render('articles/show.php', [
            'article' => null,
            'author' => null,
        ], '404');
    }

    // Вспомогательный метод для рендеринга представлений
    private function render(string $view, array $params = [], ?string $title = null): void {
        extract($params);           // Превращает ключи массива в переменные
        ob_start();                 // Начинаем буферизацию вывода
        require __DIR__ . '/../../views/' . $view;  // Подключаем view
        $content = ob_get_clean();  // Сохраняем вывод в переменную $content
        require __DIR__ . '/../../main.php';  // Подключаем основной шаблон
    }
}