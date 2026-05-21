<?php

global $db;
$currentId = $_GET['id'] ?? 0;

$list = $db->query("
    SELECT * FROM contacts
    ORDER BY surname, name
");

$first = $db->querySingle("
    SELECT id FROM contacts
    ORDER BY surname, name
    LIMIT 1
");

if (!$currentId) {
    $currentId = $first;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $db->prepare("
        UPDATE contacts SET
            surname = :surname,
            name = :name,
            patronymic = :patronymic,
            gender = :gender,
            birthdate = :birthdate,
            phone = :phone,
            address = :address,
            email = :email,
            comment = :comment
        WHERE id = :id
    ");

    $stmt->bindValue(':surname', $_POST['surname']);
    $stmt->bindValue(':name', $_POST['name']);
    $stmt->bindValue(':patronymic', $_POST['patronymic']);
    $stmt->bindValue(':gender', $_POST['gender']);
    $stmt->bindValue(':birthdate', $_POST['birthdate']);
    $stmt->bindValue(':phone', $_POST['phone']);
    $stmt->bindValue(':address', $_POST['address']);
    $stmt->bindValue(':email', $_POST['email']);
    $stmt->bindValue(':comment', $_POST['comment']);
    $stmt->bindValue(':id', $currentId);

    $stmt->execute();
}

$current = $db->querySingle("
    SELECT * FROM contacts
    WHERE id = $currentId
", true);

?>

<div class="edit-layout">

    <div class="records-list">

        <?php
        while ($row = $list->fetchArray(SQLITE3_ASSOC)) {

            $class = ($row['id'] == $currentId)
                ? 'record-link current'
                : 'record-link';

            echo "
    <a class='$class'
       href='index.php?page=edit&id={$row['id']}'>
       {$row['surname']} {$row['name']}
    </a>";
        }
        ?>

    </div>

    <form method="POST" class="contact-form">

        <label>
            <input type="text"
                   name="surname"
                   value="<?= $current['surname'] ?>">
        </label>

        <label>
            <input type="text"
                   name="name"
                   value="<?= $current['name'] ?>">
        </label>

        <label>
            <input type="text"
                   name="patronymic"
                   value="<?= $current['patronymic'] ?>">
        </label>

        <label>
            <input type="text"
                   name="gender"
                   value="<?= $current['gender'] ?>">
        </label>

        <label>
            <input type="date"
                   name="birthdate"
                   value="<?= $current['birthdate'] ?>">
        </label>

        <label>
            <input type="text"
                   name="phone"
                   value="<?= $current['phone'] ?>">
        </label>

        <label>
            <input type="text"
                   name="address"
                   value="<?= $current['address'] ?>">
        </label>

        <label>
            <input type="email"
                   name="email"
                   value="<?= $current['email'] ?>">
        </label>

        <label>
            <textarea name="comment"><?= $current['comment'] ?></textarea>
        </label>

        <button class="form-btn">
            Сохранить
        </button>

    </form>

</div>