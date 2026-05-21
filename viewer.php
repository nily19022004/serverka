<?php

function renderViewer($sort, $page): string
{
    global $db;

    $allowedSorts = [
        'created' => 'id ASC',
        'surname' => 'surname ASC',
        'birthdate' => 'birthdate ASC'
    ];

    $order = $allowedSorts[$sort] ?? 'id ASC';

    $limit = 10;
    $offset = ($page - 1) * $limit;

    $countResult = $db->querySingle(
        "SELECT COUNT(*) FROM contacts"
    );

    $pages = ceil($countResult / $limit);

    $result = $db->query("
        SELECT * FROM contacts
        ORDER BY $order
        LIMIT $limit OFFSET $offset
    ");

    $html = "<table class='contacts-table'>";

    $html .= "
    <tr>
        <th>Фамилия</th>
        <th>Имя</th>
        <th>Отчество</th>
        <th>Пол</th>
        <th>Дата рождения</th>
        <th>Телефон</th>
        <th>Email</th>
    </tr>";

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {

        $html .= "
        <tr>
            <td>{$row['surname']}</td>
            <td>{$row['name']}</td>
            <td>{$row['patronymic']}</td>
            <td>{$row['gender']}</td>
            <td>{$row['birthdate']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['email']}</td>
        </tr>";
    }

    $html .= "</table>";

    if ($pages > 1) {

        $html .= "<div class='pagination'>";

        for ($i = 1; $i <= $pages; $i++) {

            $class = ($i == $page)
                ? 'page-link active'
                : 'page-link';

            $html .= "
                <a class='$class'
                   href='index.php?page=view&sort=$sort&p=$i'>
                    $i
                </a>
            ";
        }

        $html .= "</div>";
    }

    return $html;
}
