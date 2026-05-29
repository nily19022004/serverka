<?php declare(strict_types=1);

class Article {
    public int $id;
    public int $authorId;
    public string $name;
    public string $text;
    public string $createdAt;

    // Конструктор - создаёт объект статьи из строки БД
    public function __construct(array $row) {
        $this->id = (int) $row['id'];
        $this->authorId = (int) $row['author_id'];
        $this->name = $row['name'];
        $this->text = $row['text'];
        $this->createdAt = $this->convertToMoscowTime($row['created_at']);
    }

    // Конвертация времени из UTC в московское
    private function convertToMoscowTime(string $utcTime): string {
        $datetime = new DateTime($utcTime, new DateTimeZone('UTC'));
        $datetime->setTimezone(new DateTimeZone('Europe/Moscow'));
        return $datetime->format('Y-m-d H:i:s');
    }

    // Получить все статьи (сортировка от новых к старым)
    public static function findAll(): array {
        $db = Database::getConnection();
        $statement = $db->query('SELECT * FROM articles ORDER BY id DESC');
        $articles = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $articles[] = new self($row);
        }
        return $articles;
    }

    // Найти статью по ID
    public static function findById(int $id): ?self {
        $db = Database::getConnection();
        $statement = $db->prepare('SELECT * FROM articles WHERE id = :id');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }
        return new self($row);
    }

    // Создать новую статью
    public static function insert(int $authorId, string $name, string $text): self {
        $db = Database::getConnection();
        $statement = $db->prepare(
            'INSERT INTO articles (author_id, name, text, created_at) VALUES (:author_id, :name, :text, datetime("now"))'
        );
        $statement->execute([
            'author_id' => $authorId,
            'name' => $name,
            'text' => $text,
        ]);
        $id = (int) $db->lastInsertId();
        return self::findById($id);
    }
}
