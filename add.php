<?php

global $db;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $db->prepare("
        INSERT INTO contacts
        (
            surname,
            name,
            patronymic,
            gender,
            birthdate,
            phone,
            address,
            email,
            comment
        )
        VALUES
        (
            :surname,
            :name,
            :patronymic,
            :gender,
            :birthdate,
            :phone,
            :address,
            :email,
            :comment
        )
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

    $result = $stmt->execute();

    if ($result) {
        $message = "<div class='message success'>
            Запись добавлена
        </div>";
    } else {
        $message = "<div class='message error'>
            Ошибка: запись не добавлена
        </div>";
    }
}

echo $message;
?>

<form method="POST" class="contact-form">

    <label>
        <input type="text" name="surname" placeholder="Фамилия" required>
    </label>
    <label>
        <input type="text" name="name" placeholder="Имя" required>
    </label>
    <label>
        <input type="text" name="patronymic" placeholder="Отчество">
    </label>

    <label>
        <select name="gender">
            <option>Мужской</option>
            <option>Женский</option>
        </select>
    </label>

    <label>
        <input type="date" name="birthdate">
    </label>
    <label>
        <input type="text" name="phone" placeholder="Телефон">
    </label>
    <label>
        <input type="text" name="address" placeholder="Адрес">
    </label>
    <label>
        <input type="email" name="email" placeholder="Email">
    </label>

    <label>
<textarea name="comment"
          placeholder="Комментарий"></textarea>
    </label>

    <button class="form-btn">
        Добавить
    </button>

</form>