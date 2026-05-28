<?php declare(strict_types=1);

class ArticlesController {
    public function index(): void {
        $articles = Article::findAll();
        $this->render('articles/index.php', [
            'articles' => $articles,
        ]);
    }

    public function show(int $id): void {
        $article = Article::findById($id);
        if ($article === null) {
            $this->notFound();
            return;
        }
        $author = User::findById($article->authorId);
        $this->render('articles/show.php', [
            'article' => $article,
            'author' => $author,
        ], $article->name);
    }

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $text = trim($_POST['text'] ?? '');
            if ($name !== '' && $text !== '') {
                $article = Article::insert(1, $name, $text);
                header('Location: /articles/' . $article->id);
                exit;
            }
            $this->render('articles/create.php', [
                'error' => 'Пожалуйста, заполните все поля.',
                'name' => $name,
                'text' => $text,
            ], 'Новая статья');
            return;
        }
        $this->render('articles/create.php', [
            'error' => null,
            'name' => '',
            'text' => '',
        ], 'Новая статья');
    }

    public function notFound(): void {
        http_response_code(404);
        $this->render('articles/show.php', [
            'article' => null,
            'author' => null,
        ], '404');
    }

    private function render(string $view, array $params = [], ?string $title = null): void {
        extract($params);
        ob_start();
        require __DIR__ . '/../../views/' . $view;
        $content = ob_get_clean();
        require __DIR__ . '/../../main.php';
    }
}