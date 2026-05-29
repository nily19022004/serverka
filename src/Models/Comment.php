<?php
// D:\Desktop\study\2 семестр\serv\src\Models\Comment.php

declare(strict_types=1);

/**
 * Модель комментария
 * Отвечает за работу с комментариями в базе данных
 */
class Comment
{
    // Свойства, соответствующие полям таблицы comments
    public int $id;          // Уникальный ID комментария
    public int $recipeId;    // ID рецепта, к которому относится комментарий
    public string $author;   // Имя автора
    public string $text;     // Текст комментария
    public string $createdAt;// Дата создания (в московском времени)

    /**
     * Конструктор: создаёт объект Comment из строки БД
     * @param array $row Ассоциативный массив с полями из таблицы comments
     */
    public function __construct(array $row)
    {
        $this->id        = (int) $row['id'];          // Приводим ID к int
        $this->recipeId  = (int) $row['recipe_id'];   // Приводим recipe_id к int
        $this->author    = $row['author'];            // Имя автора (строка)
        $this->text      = $row['text'];              // Текст комментария
        // Конвертируем дату из UTC в московское время
        $this->createdAt = $this->convertToMoscowTime($row['created_at']);
    }

    /**
     * Находит все комментарии для указанного рецепта
     * @param int $recipeId ID рецепта
     * @return Comment[] Массив объектов Comment
     */
    public static function findByRecipeId(int $recipeId): array
    {
        $db   = Database::getConnection();                         // Получаем соединение с БД
        $stmt = $db->prepare('SELECT * FROM comments WHERE recipe_id = :rid ORDER BY id ASC');
        $stmt->execute(['rid' => $recipeId]);                      // Выполняем запрос

        $comments = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {            // Проходим по всем строкам
            $comments[] = new self($row);                          // Создаём объект для каждой строки
        }

        return $comments;
    }

    /**
     * Добавляет новый комментарий в базу данных
     * @param int    $recipeId ID рецепта
     * @param string $author   Имя автора
     * @param string $text     Текст комментария
     * @return self Созданный объект Comment (с присвоенным ID)
     */
    public static function insert(int $recipeId, string $author, string $text): self
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare(
            'INSERT INTO comments (recipe_id, author, text, created_at)
             VALUES (:recipe_id, :author, :text, CURRENT_TIMESTAMP)'
        );
        $stmt->execute([
            'recipe_id' => $recipeId,
            'author'    => $author,
            'text'      => $text,
        ]);

        $id  = (int) $db->lastInsertId();                          // Получаем автоматически созданный ID
        $row = $db->prepare('SELECT * FROM comments WHERE id = :id');
        $row->execute(['id' => $id]);

        return new self($row->fetch(PDO::FETCH_ASSOC));            // Возвращаем созданный комментарий
    }

    /**
     * Удаляет комментарий по его ID
     * @param int $id ID комментария
     */
    public static function deleteById(int $id): void
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('DELETE FROM comments WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    /**
     * Удаляет все комментарии для указанного рецепта
     * Используется при удалении самого рецепта
     * @param int $recipeId ID рецепта
     */
    public static function deleteByRecipeId(int $recipeId): void
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('DELETE FROM comments WHERE recipe_id = :rid');
        $stmt->execute(['rid' => $recipeId]);
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