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
}