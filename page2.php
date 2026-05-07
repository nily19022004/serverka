<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Headers Result - МосПолитех</title>
</head>
<body>

<!-- HEADER -->
<table width="100%" border="0">
    <tr>
        <td width="33%" align="left">
            <img src="image/mospoly.png" alt="Логотип МосПолитеха" height="60">
        </td>
        <td width="34%" align="center">
            <h2>Headers Result</h2>
        </td>
        <td width="33%">\(em)
    </tr>
</tr>

<main>
    <h3>Результат работы функции get_headers:</h3>
    
    <?php
        // Получаем заголовки сайта httpbin.org
        $headers = get_headers('https://httpbin.org/post');
        
        // Преобразуем массив в текст для вывода в textarea
        $headers_text = implode("\n", $headers);
    ?>
    
    <textarea rows="15" cols="80" readonly><?php echo $headers_text; ?></textarea>
    
    <p>
        <a href="index.php">← Назад на 1 страницу</a>
    </p>
</main>

<footer>
    <p>задание для самостоятельной работы</p>
</footer>

</body>
</html>