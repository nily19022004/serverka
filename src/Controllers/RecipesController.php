<?php

declare(strict_types=1);

class RecipesController
{
    public function index(): void
    {
        $difficulty = isset($_GET['difficulty']) && in_array((int)$_GET['difficulty'], [1,2,3], true)
            ? (int)$_GET['difficulty']
            : null;

        $recipes = Recipe::findAll($difficulty);

        $this->render('recipes/index.php', [
            'recipes'    => $recipes,
            'difficulty' => $difficulty,
        ]);
    }

    public function show(int $id): void
    {
        $recipe = Recipe::findById($id);

        if ($recipe === null) {
            $this->notFound();
            return;
        }

        $comments = Comment::findByRecipeId($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $author = trim($_POST['author'] ?? '');
            $text   = trim($_POST['text'] ?? '');

            if ($author !== '' && $text !== '') {
                Comment::insert($id, $author, $text);
            }

            header('Location: /recipes/' . $id . '#comments');
            exit;
        }

        $this->render('recipes/show.php', [
            'recipe'   => $recipe,
            'comments' => $comments,
        ], $recipe->name);
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name        = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $ingredients = trim($_POST['ingredients'] ?? '');
            $steps       = trim($_POST['steps'] ?? '');
            $difficulty  = max(1, min(3, (int)($_POST['difficulty'] ?? 1)));
            $cookTime    = trim($_POST['cook_time'] ?? '');

            if ($name !== '' && $description !== '' && $ingredients !== '' && $steps !== '') {
                $recipe = Recipe::insert($name, $description, $ingredients, $steps, $difficulty, $cookTime);
                header('Location: /recipes/' . $recipe->id);
                exit;
            }

            $this->render('recipes/create.php', [
                'error'       => 'Пожалуйста, заполните все обязательные поля.',
                'name'        => $name,
                'description' => $description,
                'ingredients' => $ingredients,
                'steps'       => $steps,
                'difficulty'  => $difficulty,
                'cook_time'   => $cookTime,
            ], 'Новый рецепт');
            return;
        }

        $this->render('recipes/create.php', [
            'error'       => null,
            'name'        => '',
            'description' => '',
            'ingredients' => '',
            'steps'       => '',
            'difficulty'  => 1,
            'cook_time'   => '',
        ], 'Новый рецепт');
    }

    public function edit(int $id): void
    {
        $recipe = Recipe::findById($id);

        if ($recipe === null) {
            $this->notFound();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name        = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $ingredients = trim($_POST['ingredients'] ?? '');
            $steps       = trim($_POST['steps'] ?? '');
            $difficulty  = max(1, min(3, (int)($_POST['difficulty'] ?? 1)));
            $cookTime    = trim($_POST['cook_time'] ?? '');

            if ($name !== '' && $description !== '' && $ingredients !== '' && $steps !== '') {
                $recipe->name        = $name;
                $recipe->description = $description;
                $recipe->ingredients = $ingredients;
                $recipe->steps       = $steps;
                $recipe->difficulty  = $difficulty;
                $recipe->cookTime    = $cookTime;
                $recipe->save();
            }

            header('Location: /recipes/' . $id);
            exit;
        }

        $this->render('recipes/edit.php', [
            'recipe' => $recipe,
        ], 'Редактирование: ' . $recipe->name);
    }

    public function delete(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /recipes/' . $id);
            exit;
        }

        Comment::deleteByRecipeId($id);
        Recipe::deleteById($id);

        header('Location: /recipes');
        exit;
    }

    public function deleteComment(int $recipeId, int $commentId): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /recipes/' . $recipeId . '#comments');
            exit;
        }

        Comment::deleteById($commentId);

        header('Location: /recipes/' . $recipeId . '#comments');
        exit;
    }

    public function notFound(): void
    {
        http_response_code(404);
        $this->render('recipes/show.php', [
            'recipe'   => null,
            'comments' => [],
        ], '404');
    }

    private function render(string $view, array $params = [], ?string $title = null): void
    {
        extract($params);

        ob_start();
        require __DIR__ . '/../../views/' . $view;
        $content = ob_get_clean();

        require __DIR__ . '/../../main.php';
    }
}
