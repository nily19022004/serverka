<?php

declare(strict_types=1);

class Article
{
    public int $id;
    public int $authorId;
    public string $name;
    public string $text;
    public string $createdAt;

    public function __construct(array $row)
    {
        $this->id        = (int) $row['id'];
        $this->authorId  = (int) $row['author_id'];
        $this->name      = $row['name'];
        $this->text      = $row['text'];
        $this->createdAt = $row['created_at'];
    }

    public static function findAll(): array
    {
        $db = Database::getConnection();

        $statement = $db->query('SELECT * FROM articles ORDER BY id DESC');

        $articles = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $articles[] = new self($row);
        }

        return $articles;
    }

    public static function findById(int $id): ?self
    {
        $db = Database::getConnection();

        $statement = $db->prepare('SELECT * FROM articles WHERE id = :id');
        $statement->execute(['id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return new self($row);
    }

    public static function insert(int $authorId, string $name, string $text): self
{
    $db = Database::getConnection();
    $statement = $db->prepare(
        'INSERT INTO articles (author_id, name, text, created_at) VALUES (:author_id, :name, :text, datetime("now", "+3 hours"))'
    );
    $statement->execute([
        'author_id' => $authorId,
        'name' => $name,
        'text' => $text,
    ]);
    $id = (int) $db->lastInsertId();
    return self::findById($id);
}

    public function save(): void
    {
        $db = Database::getConnection();

        $statement = $db->prepare(
            'UPDATE articles SET name = :name, text = :text WHERE id = :id'
        );

        $statement->execute([
            'name' => $this->name,
            'text' => $this->text,
            'id'   => $this->id,
        ]);
    }
}
