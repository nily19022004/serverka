<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Все лабораторные работы</title>
    <style>
        /* Немного стилей для красоты, это не обязательно */
        body { font-family: sans-serif; line-height: 1.6; max-width: 800px; margin: 20px auto; padding: 0 20px; }
        .lab-list { list-style: none; padding: 0; }
        .lab-list li { margin: 15px 0; border: 1px solid #ddd; border-radius: 5px; }
        .lab-link { display: block; padding: 15px; background: #f4f4f4; text-decoration: none; font-weight: bold; color: #333; cursor: pointer; }
        .lab-link:hover { background: #e9e9e9; }
        .lab-content { padding: 20px; display: none; border-top: 1px solid #ddd; }
        iframe { width: 100%; min-height: 500px; border: none; }
    </style>
    <script>
        function showLab(labId) {
            // 1. Скрываем все блоки с содержимым лабораторных
            const allContents = document.querySelectorAll('.lab-content');
            allContents.forEach(content => content.style.display = 'none');

            // 2. Показываем только тот блок, на который нажали
            const selectedContent = document.getElementById('content-' + labId);
            if (selectedContent) {
                selectedContent.style.display = 'block';
            } else {
                console.error('Блок с id content-' + labId + ' не найден');
            }
        }
    </script>
</head>
<body>

    <h1>Мои лабораторные работы</h1>

    <ul class="lab-list">
        <li>
            <div class="lab-link" onclick="showLab('lab1')">📂 Лабораторная работа №1</div>
            <div id="content-lab1" class="lab-content">
                <iframe src="/lab1/index.php" title="Лабораторная работа 1"></iframe>
            </div>
        </li>
        <li>
            <div class="lab-link" onclick="showLab('lab2')">📂 Лабораторная работа №2</div>
            <div id="content-lab2" class="lab-content">
                <iframe src="/lab2/index.php" title="Лабораторная работа 2"></iframe>
            </div>
        </li>
        <li>
            <div class="lab-link" onclick="showLab('lab2')">📂 Лабораторная работа №3</div>
            <div id="content-lab2" class="lab-content">
                <iframe src="/lab2/index.php" title="Лабораторная работа 2"></iframe>
            </div>
        </li>
        <li>
            <div class="lab-link" onclick="showLab('lab2')">📂 Лабораторная работа №4</div>
            <div id="content-lab2" class="lab-content">
                <iframe src="/lab2/index.php" title="Лабораторная работа 2"></iframe>
            </div>
        </li>
        <li>
            <div class="lab-link" onclick="showLab('lab2')">📂 Лабораторная работа №5</div>
            <div id="content-lab2" class="lab-content">
                <iframe src="/lab2/index.php" title="Лабораторная работа 2"></iframe>
            </div>
        </li>
        <li>
            <div class="lab-link" onclick="showLab('lab2')">📂 Лабораторная работа №6</div>
            <div id="content-lab2" class="lab-content">
                <iframe src="/lab2/index.php" title="Лабораторная работа 2"></iframe>
            </div>
        </li>
    </ul>

    <p style="margin-top: 30px; font-size: 0.9em; color: #555;">задание для самостоятельной работы</p>

</body>
</html>