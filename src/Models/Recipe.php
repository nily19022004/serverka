<?php

declare(strict_types=1);

class Recipe
{
    public int $id;
    public string $name;
    public string $description;
    public string $ingredients;
    public string $steps;
    public int $difficulty;
    public string $cookTime;
    public string $createdAt;

    public function __construct(array $row)
{
    $this->id          = (int) $row['id'];
    $this->name        = $row['name'];
    $this->description = $row['description'];
    $this->ingredients = $row['ingredients'];
    $this->steps       = $row['steps'];
    $this->difficulty  = (int) $row['difficulty'];
    $this->cookTime    = $row['cook_time'];
    $this->createdAt   = $this->convertToMoscowTime($row['created_at']);
}

    public static function findAll(?int $difficulty = null): array
    {
        $db = Database::getConnection();

        if ($difficulty !== null) {
            $stmt = $db->prepare('SELECT * FROM recipes WHERE difficulty = :d ORDER BY id DESC');
            $stmt->execute(['d' => $difficulty]);
        } else {
            $stmt = $db->query('SELECT * FROM recipes ORDER BY id DESC');
        }

        $recipes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $recipes[] = new self($row);
        }

        return $recipes;
    }

    public static function findById(int $id): ?self
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('SELECT * FROM recipes WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row  = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row !== false ? new self($row) : null;
    }

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

        return self::findById((int) $db->lastInsertId());
    }

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

    public function starsHtml(): string
    {
        $html = '';
        for ($i = 1; $i <= 3; $i++) {
            $html .= $i <= $this->difficulty ? '★' : '☆';
        }
        return $html;
    }

    public static function deleteById(int $id): void
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('DELETE FROM recipes WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
    private function convertToMoscowTime(string $datetime): string
{
    $date = new DateTime($datetime, new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone('Europe/Moscow'));
    return $date->format('Y-m-d H:i:s');
}
}
