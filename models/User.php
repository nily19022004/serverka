<?php

require_once __DIR__ . '/../Database.php';

class User
{
    public $id;
    public $nickname;
    public $email;
    public $createdAt;
    
    public function __construct($row)
    {
        $this->id = $row['id'];
        $this->nickname = $row['nickname'];
        $this->email = $row['email'];
        $this->createdAt = $row['created_at'];
    }
    
    // Найти пользователя по ID
    public static function findById($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row === false) {
            return null;
        }
        
        return new User($row);
    }
    
    // ========= НОВЫЙ МЕТОД: ПОЛУЧИТЬ ВСЕХ ПОЛЬЗОВАТЕЛЕЙ =========
    public static function findAll()
    {
        $db = Database::getConnection();
        $result = $db->query("SELECT * FROM users ORDER BY id");
        
        $users = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new User($row);
        }
        
        return $users;
    }
}