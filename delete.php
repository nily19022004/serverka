<?php

global $db;
$message = '';

if (isset($_GET['id'])) {

    $id = (int)$_GET['id'];

    $surname = $db->querySingle("
        SELECT surname
        FROM contacts
        WHERE id = $id
    ");

    $db->exec("
        DELETE FROM contacts
        WHERE id = $id
    ");

    $message = "
    <div class='message success'>
        Запись с фамилией $surname удалена
    </div>";
}

echo $message;

$result = $db->query("
    SELECT * FROM contacts
    ORDER BY surname
");

echo "<div class='delete-list'>";

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {

    $short =
        $row['surname'] . ' ' .
        mb_substr($row['name'], 0, 1) . '.';

    echo "
    <a class='delete-link'
       href='index.php?page=delete&id={$row['id']}'>
        $short
    </a>";
}

echo "</div>";
