<?php

declare(strict_types=1);

class Comment
{
    public int $id;
    public int $recipeId;
    public string $author;
    public string $text;
    public string $createdAt;

    public function __construct(array $row)
{
    $this->id        = (int) $row['id'];
    $this->recipeId  = (int) $row['recipe_id'];
    $this->author    = $row['author'];
    $this->text      = $row['text'];
    $this->createdAt = $this->convertToMoscowTime($row['created_at']);
}

    public static function findByRecipeId(int $recipeId): array
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('SELECT * FROM comments WHERE recipe_id = :rid ORDER BY id ASC');
        $stmt->execute(['rid' => $recipeId]);

        $comments = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = new self($row);
        }

        return $comments;
    }

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

        $id  = (int) $db->lastInsertId();
        $row = $db->prepare('SELECT * FROM comments WHERE id = :id');
        $row->execute(['id' => $id]);

        return new self($row->fetch(PDO::FETCH_ASSOC));
    }

    public static function deleteById(int $id): void
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('DELETE FROM comments WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public static function deleteByRecipeId(int $recipeId): void
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('DELETE FROM comments WHERE recipe_id = :rid');
        $stmt->execute(['rid' => $recipeId]);
    }
    private function convertToMoscowTime(string $datetime): string
{
    $date = new DateTime($datetime, new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone('Europe/Moscow'));
    return $date->format('Y-m-d H:i:s');
}
}

