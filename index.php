<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Feedback Form - МосПолитех</title>
</head>
<body>

<!-- HEADER -->
<table width="100%" border="0">
    <tr>
        <td width="33%" align="left">
            <img src="image/mospoly.png" alt="Логотип МосПолитеха" height="60">
        </td>
        <td width="34%" align="center">
            <h2>Feedback Form</h2>
        </td>
        <td width="33%"></td>
    </tr>
</table>

<main>
    <h3>Форма обратной связи</h3>
    
    <form method="POST" action="https://httpbin.org/post">
        <p>
            <label>Имя пользователя:<br>
            <input type="text" name="username" required size="40">
            </label>
        </p>
        
        <p>
            <label>E-mail пользователя:<br>
            <input type="email" name="email" required size="40">
            </label>
        </p>
        
        <p>
            <label>Тип обращения:<br>
            <select name="type">
                <option value="complaint">Жалоба</option>
                <option value="suggestion">Предложение</option>
                <option value="thanks">Благодарность</option>
            </select>
            </label>
        </p>
        
        <p>
            <label>Текст обращения:<br>
            <textarea name="message" rows="5" cols="40" required></textarea>
            </label>
        </p>
        
        <p>
            <label>Вариант ответа:<br>
            <input type="checkbox" name="response[]" value="sms"> СМС
            <input type="checkbox" name="response[]" value="email"> E-mail
            </label>
        </p>
        
        <p>
            <input type="submit" value="Отправить">
        </p>
    </form>
    
    <p>
        <a href="page2.php">Перейти на 2 страницу →</a>
    </p>
</main>

<footer>
    <p>задание для самостоятельной работы</p>
</footer>

</body>
</html>