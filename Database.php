<?php

class Database
{
    private static $connection = null;

    public static function getConnection()
    {
        if (self::$connection === null) {
            // Папка для базы данных
            $dataDir = __DIR__ . '/data';
            
            // Создаём папку, если её нет
            if (!is_dir($dataDir)) {
                mkdir($dataDir, 0777, true);
            }
            
            // Путь к файлу базы данных
            $dbPath = $dataDir . '/blog.sqlite';
            
            // Подключаемся к SQLite
            self::$connection = new PDO('sqlite:' . $dbPath);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Создаём таблицы и заполняем данными
            self::initDatabase();
        }
        
        return self::$connection;
    }
    
    private static function initDatabase()
    {
        // Таблица пользователей (с московским временем)
        self::$connection->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nickname TEXT NOT NULL,
                email TEXT NOT NULL,
                created_at TEXT DEFAULT (datetime('now', '+3 hours'))
            )
        ");
        
        // Таблица статей (с московским временем)
        self::$connection->exec("
            CREATE TABLE IF NOT EXISTS articles (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                author_id INTEGER NOT NULL,
                name TEXT NOT NULL,
                text TEXT NOT NULL,
                created_at TEXT DEFAULT (datetime('now', '+3 hours'))
            )
        ");
        
        // Проверяем, есть ли данные
        $count = self::$connection->query("SELECT COUNT(*) FROM users")->fetchColumn();
        
        // Если нет данных — добавляем демо-данные (с московским временем)
        if ($count == 0) {
            // Пользователи
            self::$connection->exec("
                INSERT INTO users (nickname, email, created_at) VALUES
                ('admin', 'admin@blog.com', datetime('now', '+3 hours')),
                ('Анна', 'anna@blog.com', datetime('now', '+3 hours')),
                ('Петр', 'petr@blog.com', datetime('now', '+3 hours'))
            ");
            
            // Статьи
            self::$connection->exec("
                INSERT INTO articles (author_id, name, text, created_at) VALUES
                (1, 'Как я начал вести блог', 'Текст первой статьи. Здесь я рассказываю о своем опыте ведения блога. Это очень интересно и полезно для саморазвития.', datetime('now', '+3 hours')),
                (2, 'Мои путешествия', 'Текст второй статьи. Я люблю путешествовать и открывать новые места. В этой статье делюсь впечатлениями о поездках.', datetime('now', '+3 hours')),
                (3, 'Программирование для новичков', 'Текст третьей статьи. Программирование - это просто. Главное начать и не бояться ошибок.', datetime('now', '+3 hours'))
            ");
        }
    }
}