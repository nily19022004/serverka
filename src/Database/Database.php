<?php

declare(strict_types=1);

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $dataDir = dirname(__DIR__, 2) . '/data';

            if (!is_dir($dataDir)) {
                mkdir($dataDir, 0777, true);
            }

            $dbPath = $dataDir . '/recipes.sqlite';

            self::$connection = new PDO('sqlite:' . $dbPath);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            self::createTables();
            self::insertDemoData();
        }

        return self::$connection;
    }

    private static function createTables(): void
    {
        $db = self::$connection;

        $db->exec("
            CREATE TABLE IF NOT EXISTS recipes (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                ingredients TEXT NOT NULL,
                steps TEXT NOT NULL,
                difficulty INTEGER NOT NULL DEFAULT 1,
                cook_time VARCHAR(64) NOT NULL DEFAULT '',
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $db->exec("
            CREATE TABLE IF NOT EXISTS comments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                recipe_id INTEGER NOT NULL,
                author VARCHAR(128) NOT NULL,
                text TEXT NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    private static function insertDemoData(): void
    {
        $db = self::$connection;

        $count = (int) $db->query("SELECT COUNT(*) FROM recipes")->fetchColumn();

        if ($count === 0) {
            $db->exec("
                INSERT INTO recipes (name, description, ingredients, steps, difficulty, cook_time, created_at) VALUES
                (
                    'Борщ классический',
                    'Наваристый украинский борщ со свёклой, капустой и сметаной — блюдо, которое согревает в любую погоду.',
                    'Говядина на кости — 500 г
Свёкла — 2 шт.
Капуста — 300 г
Картофель — 3 шт.
Морковь — 1 шт.
Лук репчатый — 1 шт.
Томатная паста — 2 ст. л.
Чеснок — 3 зубчика
Сметана, укроп — по вкусу',
                    '1. Сварить бульон из говядины (1,5–2 часа на медленном огне).
2. Нарезать свёклу соломкой, потушить с томатной пастой 10 минут.
3. Добавить в бульон картофель, варить 10 минут.
4. Добавить капусту и зажарку из лука и моркови.
5. Добавить свёклу, варить ещё 10 минут.
6. В конце добавить чеснок, посолить. Подавать со сметаной.',
                    2,
                    '2 ч 30 мин',
                    CURRENT_TIMESTAMP
                ),
                (
                    'Паста карбонара',
                    'Классическая итальянская паста со сливочным соусом из яиц и сыра без добавления сливок.',
                    'Спагетти — 400 г
Гуанчале или бекон — 150 г
Яйца — 3 шт. + 2 желтка
Пармезан тёртый — 80 г
Чеснок — 1 зубчик
Чёрный перец, соль — по вкусу',
                    '1. Отварить спагетти до состояния аль денте.
2. Обжарить бекон с чесноком до хруста.
3. Взбить яйца с желтками и сыром, добавить перец.
4. Смешать горячие спагетти с беконом, снять с огня.
5. Влить яичный соус, быстро перемешать — соус загустеет от тепла пасты.',
                    2,
                    '25 мин',
                    CURRENT_TIMESTAMP
                ),
                (
                    'Омлет с сыром',
                    'Простой и быстрый завтрак — пышный омлет с сырной корочкой.',
                    'Яйца — 3 шт.
Молоко — 3 ст. л.
Сыр тёртый — 50 г
Сливочное масло — 10 г
Соль, перец — по вкусу',
                    '1. Взбить яйца с молоком, солью и перцем.
2. Разогреть сковороду со сливочным маслом.
3. Влить яичную смесь, жарить на среднем огне под крышкой 3 минуты.
4. Посыпать сыром, накрыть крышкой ещё на 1 минуту.',
                    1,
                    '10 мин',
                    CURRENT_TIMESTAMP
                ),
                (
                    'Говядина веллингтон',
                    'Изысканное блюдо высокой кухни: сочная говяжья вырезка, запечённая в слоёном тесте с грибным дюксель.',
                    'Говяжья вырезка — 800 г
Слоёное тесто — 500 г
Шампиньоны — 400 г
Прошутто — 100 г
Яичный желток — 2 шт.
Горчица дижонская — 2 ст. л.
Соль, перец, тимьян — по вкусу',
                    '1. Обжарить вырезку со всех сторон до корочки, смазать горчицей, дать остыть.
2. Приготовить дюксель: мелко нарезать грибы, обжарить до полного испарения влаги.
3. Выложить на плёнку слой прошутто, намазать дюксель, завернуть мясо в рулет. Охладить 30 мин.
4. Завернуть рулет в тесто, смазать желтком.
5. Запекать при 200°C: 25 мин для medium rare, 35 мин для well done.
6. Дать отдохнуть 10 минут перед нарезкой.',
                    3,
                    '1 ч 40 мин',
                    CURRENT_TIMESTAMP
                )
            ");

            $db->exec("
                INSERT INTO comments (recipe_id, author, text, created_at) VALUES
                (1, 'Мария', 'Отличный рецепт! Делала вчера, семья в восторге. Добавила немного сахара в свёклу.', CURRENT_TIMESTAMP),
                (1, 'Алексей', 'Борщ получился насыщенным. Варил 3 часа — бульон стал золотым!', CURRENT_TIMESTAMP),
                (2, 'Ольга', 'Сначала боялась делать без сливок, но вышло намного лучше классики. Главное — не пересушить яйца.', CURRENT_TIMESTAMP)
            ");
        }
    }
}
