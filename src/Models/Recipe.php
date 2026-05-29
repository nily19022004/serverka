<?php
// D:\Desktop\study\2 семестр\serv\src\Models\Recipe.php

declare(strict_types=1);

/**
 * Модель рецепта
 * Отвечает за работу с рецептами в базе данных
 */
class Recipe
{
    // Свойства, соответствующие полям таблицы recipes
    public int $id;             // Уникальный ID рецепта
    public string $name;        // Название рецепта
    public string $description; // Краткое описание
    public string $ingredients; // Список ингредиентов
    public string $steps;       // Шаги приготовления
    public int $difficulty;     // Сложность (1, 2 или 3)
    public string $cookTime;    // Время приготовления (текстовая строка)
    public string $createdAt;   // Дата создания (в московском времени)

    /**
     * Конструктор: создаёт объект Recipe из строки БД
     * @param array $row Ассоциативный массив с полями из таблицы recipes
     */
    public function __construct(array $row)
    {
        $this->id          = (int) $row['id'];              // Приводим ID к int
        $this->name        = $row['name'];                  // Название (строка)
        $this->description = $row['description'];           // Описание (строка)
        $this->ingredients = $row['ingredients'];           // Ингредиенты (строка)
        $this->steps       = $row['steps'];                 // Шаги (строка)
        $this->difficulty  = (int) $row['difficulty'];      // Сложность (int)
        $this->cookTime    = $row['cook_time'];             // Время приготовления (строка)
        $this->createdAt   = $this->convertToMoscowTime($row['created_at']); // Дата в московском времени
    }

    /**
     * Находит все рецепты (с возможной фильтрацией по сложности)
     * @param int|null $difficulty Фильтр по сложности (1,2,3) или null для всех
     * @return Recipe[] Массив объектов Recipe
     */
    public static function findAll(?int $difficulty = null): array
    {
        $db = Database::getConnection();

        // Если передан фильтр по сложности — используем WHERE
        if ($difficulty !== null) {
            $stmt = $db->prepare('SELECT * FROM recipes WHERE difficulty = :d ORDER BY id DESC');
            $stmt->execute(['d' => $difficulty]);
        } else {
            // Иначе выбираем все рецепты
            $stmt = $db->query('SELECT * FROM recipes ORDER BY id DESC');
        }

        $recipes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {      // Проходим по всем строкам
            $recipes[] = new self($row);                     // Создаём объект для каждой строки
        }

        return $recipes;
    }

    /**
     * Находит один рецепт по его ID
     * @param int $id ID рецепта
     * @return self|null Объект Recipe или null, если не найден
     */
    public static function findById(int $id): ?self
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('SELECT * FROM recipes WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row  = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row !== false ? new self($row) : null;       // Если есть строка — создаём объект
    }

    /**
     * Создаёт новый рецепт в базе данных
     * @return self Созданный объект Recipe (с присвоенным ID)
     */
    public static function insert(
        string $name,
        string $description,
        string $ingredients,
        string $steps,
        int $difficulty,
        string $cookTime
    ): self {
        $db   = Database::getConnection();
        $stmt = $db->prepare(
            'INSERT INTO recipes (name, description, ingredients, steps, difficulty, cook_time, created_at)
             VALUES (:name, :description, :ingredients, :steps, :difficulty, :cook_time, CURRENT_TIMESTAMP)'
        );
        $stmt->execute([
            'name'        => $name,
            'description' => $description,
            'ingredients' => $ingredients,
            'steps'       => $steps,
            'difficulty'  => $difficulty,
            'cook_time'   => $cookTime,
        ]);

        // Возвращаем только что созданный рецепт, найдя его по последнему ID
        return self::findById((int) $db->lastInsertId());
    }

    /**
     * Сохраняет изменения текущего рецепта в базу данных
     * Обновляет все поля, кроме id и created_at
     */
    public function save(): void
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare(
            'UPDATE recipes SET name=:name, description=:description, ingredients=:ingredients,
             steps=:steps, difficulty=:difficulty, cook_time=:cook_time WHERE id=:id'
        );
        $stmt->execute([
            'name'        => $this->name,
            'description' => $this->description,
            'ingredients' => $this->ingredients,
            'steps'       => $this->steps,
            'difficulty'  => $this->difficulty,
            'cook_time'   => $this->cookTime,
            'id'          => $this->id,
        ]);
    }

    /**
     * Генерирует HTML-строку со звёздами сложности
     * ★ — заполненная звезда, ☆ — пустая
     * @return string HTML со звёздами (например, "★★☆")
     */
    public function starsHtml(): string
    {
        $html = '';
        for ($i = 1; $i <= 3; $i++) {
            $html .= $i <= $this->difficulty ? '★' : '☆';   // Заполненная или пустая звезда
        }
        return $html;
    }

    /**
     * Удаляет рецепт по его ID (комментарии удаляются отдельно в контроллере)
     * @param int $id ID рецепта
     */
    public static function deleteById(int $id): void
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('DELETE FROM recipes WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    /**
     * Конвертирует дату из UTC в московское время (UTC+3)
     * @param string $datetime Дата в формате SQLite (UTC)
     * @return string Дата в формате 'Y-m-d H:i:s' по Москве
     */
    private function convertToMoscowTime(string $datetime): string
    {
        $date = new DateTime($datetime, new DateTimeZone('UTC'));          // Создаём объект в UTC
        $date->setTimezone(new DateTimeZone('Europe/Moscow'));             // Меняем часовой пояс на Москву
        return $date->format('Y-m-d H:i:s');                               // Форматируем строку
    }
}