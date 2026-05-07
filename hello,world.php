<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Hello, World!</title>
</head>
<body>

<table width="100%" border="0">
    <tr>
        <td width="33%" align="left">
            <img src="image/mospoly.png" alt="Логотип МосПолитеха" height="60">
        </td>
        <td width="34%" align="center">
            <h2>Hello, World!</h2>
        </td>
        <td width="33%"></td>
    </tr>
</table>

<main>
    <h3>Динамический контент:</h3>
    
    <?php
        // Динамический контент
        echo "<p><b>Hello, World!</b></p>";
        date_default_timezone_set('Europe/Moscow');
        echo "<p>Сейчас: " . date("d.m.Y H:i:s") . "</p>";
    ?>
    
    <!-- HTML-элемент с простой динамикой -->
    <div id="myElement" style="border:1px solid black; padding:10px; width:300px;">
        Нажми на меня
    </div>
    
    <script>
        let counter = 0;
        let element = document.getElementById('myElement');
        element.onclick = function() {
            counter++;
            this.innerHTML = 'Нажатий: ' + counter;
        };
    </script>
</main>

<footer>
    <p>задание для самостоятельной работы</p>
</footer>

</body>
</html>