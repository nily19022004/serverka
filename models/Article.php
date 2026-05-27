<?php

require_once __DIR__ . '/../Database.php';

class Article
{
    public $id;
    public $authorId;
    public $name;
    public $text;
    public $createdAt;
    
    public function __construct($row)
    {
        $this->id = $row['id'];
        $this->authorId = $row['author_id'];
        $this->name = $row['name'];
        $this->text = $row['text'];
        $this->createdAt = $row['created_at'];
    }
    
    // Получить все статьи
    public static function findAll()
    {
        $db = Database::getConnection();
        $result = $db->query("SELECT * FROM articles ORDER BY id DESC");
        
        $articles = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $articles[] = new Article($row);
        }
        
        return $articles;
    }
    
    // Найти статью по ID
    public static function findById($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM articles WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row === false) {
            return null;
        }
        
        return new Article($row);
    }
    
    // Создание статьи с московским временем
    public static function create($name, $text, $authorId)
    {
        $db = Database::getConnection();
        
        // Московское время (UTC+3)
        $stmt = $db->prepare("
            INSERT INTO articles (author_id, name, text, created_at) 
            VALUES (:author_id, :name, :text, datetime('now', '+3 hours'))
        ");
        
        return $stmt->execute([
            'author_id' => $authorId,
            'name' => $name,
            'text' => $text
        ]);
    }
    
    // ========= НОВЫЙ МЕТОД: УДАЛЕНИЕ СТАТЬИ =========
    public static function delete($id)
    {
        $db = Database::getConnection();
        
        $stmt = $db->prepare("DELETE FROM articles WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
