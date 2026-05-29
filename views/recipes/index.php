<?php
// D:\Desktop\study\2 семестр\serv\views\recipes\index.php
// Представление: список всех рецептов (главная страница)
?>

<div class="list-header">
    <h2>
        <?php if ($difficulty !== null): ?>
            <!-- Если выбран фильтр сложности — показываем соответствующий заголовок -->
            <?= ['', '★☆☆ Лёгкие', '★★☆ Средние', '★★★ Сложные'][$difficulty] ?> рецепты
        <?php else: ?>
            <!-- Иначе — показываем "Все рецепты" -->
            Все рецепты
        <?php endif; ?>
    </h2>
</div>

<?php if (empty($recipes)): ?>
    <!-- Если рецептов нет — выводим сообщение и ссылку на добавление -->
    <p class="empty-msg">Рецептов пока нет. <a href="/recipes/create">Добавьте первый!</a></p>
<?php else: ?>
    <!-- Перебираем все рецепты и выводим каждый в виде карточки -->
    <?php foreach ($recipes as $recipe): ?>
        <article class="recipe-card">
            <div class="recipe-card-top">
                <!-- Ссылка на страницу рецепта -->
                <h3><a href="/recipes/<?= $recipe->id ?>"><?= htmlspecialchars($recipe->name, ENT_QUOTES, 'UTF-8') ?></a></h3>
                <!-- Звёзды сложности -->
                <span class="stars"><?= $recipe->starsHtml() ?></span>
            </div>
            <!-- Краткое описание (обрезаем до 130 символов) -->
            <p class="recipe-desc"><?= htmlspecialchars(mb_substr($recipe->description, 0, 130), ENT_QUOTES, 'UTF-8') ?>...</p>
            <div class="recipe-meta">
                <?php if ($recipe->cookTime !== ''): ?>
                    <!-- Время приготовления, если указано -->
                    <span>Время: <?= htmlspecialchars($recipe->cookTime, ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
                <!-- Дата добавления рецепта -->
                <span class="date">Добавлен: <?= htmlspecialchars($recipe->createdAt, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        </article>
    <?php endforeach; ?>
<?php endif; ?>