<?php

require_once 'config.php';
require_once 'menu.php';
require_once 'viewer.php';

$page = $_GET['page'] ?? 'view';
$sort = $_GET['sort'] ?? 'created';
$pageNumber = (int)($_GET['p'] ?? 1);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Записная книжка</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h1>Записная книжка</h1>

    <?php echo renderMenu($page, $sort); ?>

    <?php

    switch ($page) {

        case 'add':
            require_once 'add.php';
            break;

        case 'edit':
            require_once 'edit.php';
            break;

        case 'delete':
            require_once 'delete.php';
            break;

        default:
            echo renderViewer($sort, $pageNumber);
    }

    ?>

</div>

</body>
</html>