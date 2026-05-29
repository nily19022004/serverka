<div class="list-header">
    <h2>
        <?php if ($difficulty !== null): ?>
            <?= ['', '★☆☆ Лёгкие', '★★☆ Средние', '★★★ Сложные'][$difficulty] ?> рецепты
        <?php else: ?>
            Все рецепты
        <?php endif; ?>
    </h2>
</div>

<?php if (empty($recipes)): ?>
    <p class="empty-msg">Рецептов пока нет. <a href="/recipes/create">Добавьте первый!</a></p>
<?php else: ?>
    <?php foreach ($recipes as $recipe): ?>
        <article class="recipe-card">
            <div class="recipe-card-top">
                <h3><a href="/recipes/<?= $recipe->id ?>"><?= htmlspecialchars($recipe->name, ENT_QUOTES, 'UTF-8') ?></a></h3>
                <span class="stars"><?= $recipe->starsHtml() ?></span>
            </div>
            <p class="recipe-desc"><?= htmlspecialchars(mb_substr($recipe->description, 0, 130), ENT_QUOTES, 'UTF-8') ?>...</p>
            <div class="recipe-meta">
                <?php if ($recipe->cookTime !== ''): ?>
                    <span>Время: <?= htmlspecialchars($recipe->cookTime, ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
                <span class="date">Добавлен: <?= htmlspecialchars($recipe->createdAt, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        </article>
    <?php endforeach; ?>
<?php endif; ?>
