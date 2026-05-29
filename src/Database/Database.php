<?php

declare(strict_types=1);

class Database
{
    private static ?PDO $connection = null;

    // Получение соединения с БД (паттерн Singleton)
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            // Путь к папке с данными
            $dataDir = dirname(__DIR__, 2) . '/data';

            // Создаём папку, если её нет
            if (!is_dir($dataDir)) {
                mkdir($dataDir, 0777, true);
            }

            $dbPath = $dataDir . '/blog.sqlite';

            // Создаём соединение с SQLite
            self::$connection = new PDO('sqlite:' . $dbPath);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Создаём таблицы и добавляем демо-данные
            self::createTables();
            self::insertDemoData();
        }

        return self::$connection;
    }

    // Создание таблиц, если их нет
    private static function createTables(): void
    {
        $db = self::$connection;

        // Таблица пользователей
        $db->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nickname VARCHAR(128) NOT NULL UNIQUE,
                email VARCHAR(255) NOT NULL UNIQUE,
                is_confirmed INTEGER NOT NULL DEFAULT 0,
                role VARCHAR(20) NOT NULL,
                password_hash VARCHAR(255) NOT NULL,
                auth_token VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Таблица статей
        $db->exec("
            CREATE TABLE IF NOT EXISTS articles (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                author_id INTEGER NOT NULL,
                name VARCHAR(255) NOT NULL,
                text TEXT NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    // Добавление демонстрационных данных, если таблицы пусты
    private static function insertDemoData(): void
    {
        $db = self::$connection;

        // Добавляем пользователей
        $usersCount = (int) $db->query("SELECT COUNT(*) FROM users")->fetchColumn();

        if ($usersCount === 0) {
            $db->exec("
                INSERT INTO users 
                    (nickname, email, is_confirmed, role, password_hash, auth_token, created_at)
                VALUES
                    ('admin', 'admin@gmail.com', 1, 'admin', 'hash1', 'token1', CURRENT_TIMESTAMP),
                    ('user', 'user@gmail.com', 1, 'user', 'hash2', 'token2', CURRENT_TIMESTAMP)
            ");
        }

        // Добавляем статьи
        $articlesCount = (int) $db->query("SELECT COUNT(*) FROM articles")->fetchColumn();

        if ($articlesCount === 0) {
            $db->exec("
                INSERT INTO articles
                    (author_id, name, text, created_at)
                VALUES
                    (1, 'Статья 1', 'Первая статья Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum', CURRENT_TIMESTAMP),
                    (1, 'Статья 2', 'Текст второй статьи There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which dont look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isnt anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internetю', CURRENT_TIMESTAMP)
            ");
        }
    }
}