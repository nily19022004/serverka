<?php
// D:\Desktop\study\2 семестр\serv\src\Controllers\RecipesController.php

declare(strict_types=1); // Включает строгую типизацию для всего файла

/**
 * Контроллер рецептов
 * Обрабатывает все HTTP-запросы, связанные с рецептами и комментариями
 */
class RecipesController
{
    /**
     * Отображает список всех рецептов с возможностью фильтрации по сложности
     * GET /recipes или /?difficulty=1|2|3
     */
    public function index(): void
    {
        // Получаем параметр difficulty из GET-запроса, проверяем что он 1, 2 или 3
        $difficulty = isset($_GET['difficulty']) && in_array((int)$_GET['difficulty'], [1,2,3], true)
            ? (int)$_GET['difficulty']   // Приводим к целому числу
            : null;                       // Если параметра нет или он некорректный — null

        // Загружаем все рецепты из модели (с учётом фильтра, если передан)
        $recipes = Recipe::findAll($difficulty);

        // Рендерим представление и передаём туда данные
        $this->render('recipes/index.php', [
            'recipes'    => $recipes,      // Массив объектов Recipe
            'difficulty' => $difficulty,   // Текущий фильтр (или null)
        ]);
    }

    /**
     * Показывает один рецепт и все его комментарии
     * Также обрабатывает POST-запрос на добавление нового комментария
     * GET/POST /recipes/{id}
     */
    public function show(int $id): void
    {
        // Загружаем рецепт по ID
        $recipe = Recipe::findById($id);

        // Если рецепт не найден — показываем страницу 404
        if ($recipe === null) {
            $this->notFound();
            return;
        }

        // Загружаем все комментарии для этого рецепта
        $comments = Comment::findByRecipeId($id);

        // Обработка отправки формы комментария
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Получаем и очищаем данные из POST
            $author = trim($_POST['author'] ?? '');   // Имя автора, убираем пробелы
            $text   = trim($_POST['text'] ?? '');     // Текст комментария, убираем пробелы

            // Добавляем комментарий только если оба поля не пустые
            if ($author !== '' && $text !== '') {
                Comment::insert($id, $author, $text); // Сохраняем в БД
            }

            // Редирект обратно на страницу рецепта, к якорю #comments
            header('Location: /recipes/' . $id . '#comments');
            exit; // Обязательно завершаем скрипт после редиректа
        }

        // Рендерим страницу с рецептом и комментариями (GET-запрос или после редиректа)
        $this->render('recipes/show.php', [
            'recipe'   => $recipe,   // Объект Recipe
            'comments' => $comments, // Массив объектов Comment
        ], $recipe->name);           // Заголовок страницы — имя рецепта
    }

    /**
     * Создание нового рецепта
     * GET /recipes/create — показывает форму
     * POST /recipes/create — обрабатывает отправку формы
     */
    public function create(): void
    {
        // Обработка отправленной формы
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Получаем и очищаем все поля формы
            $name        = trim($_POST['name'] ?? '');           // Название рецепта
            $description = trim($_POST['description'] ?? '');    // Описание
            $ingredients = trim($_POST['ingredients'] ?? '');    // Ингредиенты
            $steps       = trim($_POST['steps'] ?? '');          // Шаги приготовления
            // Сложность: приводим к int, ограничиваем диапазоном 1-3
            $difficulty  = max(1, min(3, (int)($_POST['difficulty'] ?? 1)));
            $cookTime    = trim($_POST['cook_time'] ?? '');      // Время приготовления (текст)

            // Проверяем, что все обязательные поля заполнены
            if ($name !== '' && $description !== '' && $ingredients !== '' && $steps !== '') {
                // Сохраняем рецепт в БД, получаем созданный объект
                $recipe = Recipe::insert($name, $description, $ingredients, $steps, $difficulty, $cookTime);
                // Редиректим на страницу нового рецепта
                header('Location: /recipes/' . $recipe->id);
                exit;
            }

            // Если есть пустые обязательные поля — показываем форму заново с ошибкой
            $this->render('recipes/create.php', [
                'error'       => 'Пожалуйста, заполните все обязательные поля.', // Сообщение об ошибке
                'name'        => $name,          // Подставляем уже введённые данные
                'description' => $description,
                'ingredients' => $ingredients,
                'steps'       => $steps,
                'difficulty'  => $difficulty,
                'cook_time'   => $cookTime,
            ], 'Новый рецепт');
            return;
        }

        // GET-запрос: показываем пустую форму создания рецепта
        $this->render('recipes/create.php', [
            'error'       => null,   // Ошибки нет
            'name'        => '',
            'description' => '',
            'ingredients' => '',
            'steps'       => '',
            'difficulty'  => 1,      // По умолчанию сложность 1 (лёгкий)
            'cook_time'   => '',
        ], 'Новый рецепт');
    }

    /**
     * Редактирование существующего рецепта
     * GET /recipes/{id}/edit — показывает форму с текущими данными
     * POST /recipes/{id}/edit — сохраняет изменения
     */
    public function edit(int $id): void
    {
        // Загружаем рецепт по ID
        $recipe = Recipe::findById($id);

        // Если рецепт не найден — 404
        if ($recipe === null) {
            $this->notFound();
            return;
        }

        // Обработка отправки формы редактирования
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Получаем и очищаем данные из POST
            $name        = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $ingredients = trim($_POST['ingredients'] ?? '');
            $steps       = trim($_POST['steps'] ?? '');
            $difficulty  = max(1, min(3, (int)($_POST['difficulty'] ?? 1)));
            $cookTime    = trim($_POST['cook_time'] ?? '');

            // Обновляем только если обязательные поля не пустые
            if ($name !== '' && $description !== '' && $ingredients !== '' && $steps !== '') {
                // Обновляем свойства объекта-рецепта
                $recipe->name        = $name;
                $recipe->description = $description;
                $recipe->ingredients = $ingredients;
                $recipe->steps       = $steps;
                $recipe->difficulty  = $difficulty;
                $recipe->cookTime    = $cookTime;
                $recipe->save(); // Сохраняем изменения в БД
            }

            // После сохранения редиректим на страницу рецепта
            header('Location: /recipes/' . $id);
            exit;
        }

        // GET-запрос: показываем форму с текущими данными рецепта
        $this->render('recipes/edit.php', [
            'recipe' => $recipe, // Передаём объект Recipe в представление
        ], 'Редактирование: ' . $recipe->name);
    }

    /**
     * Удаление рецепта (вместе со всеми комментариями)
     * POST /recipes/{id}/delete
     */
    public function delete(int $id): void
    {
        // Безопасность: удаление только через POST-запрос (не через GET)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /recipes/' . $id); // Редирект на страницу рецепта
            exit;
        }

        // Удаляем все комментарии, связанные с рецептом (каскадное удаление вручную)
        Comment::deleteByRecipeId($id);
        // Удаляем сам рецепт
        Recipe::deleteById($id);

        // Редиректим на список всех рецептов
        header('Location: /recipes');
        exit;
    }

    /**
     * Удаление отдельного комментария
     * POST /recipes/{recipeId}/comments/{commentId}/delete
     */
    public function deleteComment(int $recipeId, int $commentId): void
    {
        // Безопасность: удаление только через POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /recipes/' . $recipeId . '#comments');
            exit;
        }

        // Удаляем комментарий по его ID
        Comment::deleteById($commentId);

        // Редиректим обратно на страницу рецепта, к комментариям
        header('Location: /recipes/' . $recipeId . '#comments');
        exit;
    }

    /**
     * Страница 404 — рецепт не найден
     * Отправляет HTTP-код 404 и рендерит заглушку
     */
    public function notFound(): void
    {
        http_response_code(404); // Устанавливаем правильный HTTP-статус
        $this->render('recipes/show.php', [
            'recipe'   => null,   // Нет рецепта — покажем 404
            'comments' => [],
        ], '404');
    }

    /**
     * Приватный метод для рендеринга представлений
     * Использует буферизацию вывода (ob_start / ob_get_clean)
     * 
     * @param string      $view   Путь к файлу шаблона внутри папки views/
     * @param array       $params Массив переменных, доступных в шаблоне (через extract)
     * @param string|null $title  Заголовок страницы (передаётся в main.php)
     */
    private function render(string $view, array $params = [], ?string $title = null): void
    {
        // Превращаем ключи массива $params в переменные (например, ['recipes' => [...]])
        extract($params);

        // Начинаем буферизацию вывода
        ob_start();
        // Подключаем файл представления (он будет выводить HTML в буфер)
        require __DIR__ . '/../../views/' . $view;
        // Получаем содержимое буфера и очищаем его
        $content = ob_get_clean();

        // Подключаем основной шаблон (с хедером, сайдбаром, футером)
        // Внутри main.php переменная $content будет содержать HTML конкретной страницы
        require __DIR__ . '/../../main.php';
    }
}