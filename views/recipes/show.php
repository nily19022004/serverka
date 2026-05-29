<?php
// D:\Desktop\study\2 семестр\serv\views\recipes\show.php
// Представление: страница одного рецепта со всеми комментариями
?>

<?php if ($recipe === null): ?>
    <!-- Блок 404 — рецепт не найден -->
    <h2>404</h2>
    <p>Рецепт не найден.</p>
    <p><a href="/recipes">← Назад к рецептам</a></p>

<?php else: ?>

<article class="recipe-page">
    <!-- Заголовок рецепта и звёзды сложности -->
    <div class="recipe-page-header">
        <h2><?= htmlspecialchars($recipe->name, ENT_QUOTES, 'UTF-8') ?></h2>
        <span class="stars stars-lg"><?= $recipe->starsHtml() ?></span>
    </div>

    <!-- Краткое описание -->
    <p class="recipe-page-desc"><?= htmlspecialchars($recipe->description, ENT_QUOTES, 'UTF-8') ?></p>

    <!-- Мета-информация: время приготовления и дата добавления -->
    <div class="recipe-meta" style="margin-bottom:20px">
        <?php if ($recipe->cookTime !== ''): ?>
            <span>Время приготовления: <strong><?= htmlspecialchars($recipe->cookTime, ENT_QUOTES, 'UTF-8') ?></strong></span>
        <?php endif; ?>
        <span class="date">Добавлен: <?= htmlspecialchars($recipe->createdAt, ENT_QUOTES, 'UTF-8') ?></span>
    </div>

    <!-- Две колонки: ингредиенты и шаги приготовления -->
    <div class="recipe-sections">
        <!-- Ингредиенты -->
        <section class="recipe-section">
            <h3>Ингредиенты</h3>
            <ul class="ingredients-list">
                <?php 
                // Разбиваем строку ингредиентов по переводу строки, убираем пустые строки, обрезаем пробелы
                foreach (array_filter(array_map('trim', explode("\n", $recipe->ingredients))) as $ing): ?>
                    <li><?= htmlspecialchars($ing, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </section>

        <!-- Шаги приготовления -->
        <section class="recipe-section">
            <h3>Приготовление</h3>
            <ol class="steps-list">
                <?php 
                foreach (array_filter(array_map('trim', explode("\n", $recipe->steps))) as $step): 
                    // Удаляем нумерацию в начале строки (например, "1. "), если она есть
                    $text = preg_replace('/^\d+\.\s*/', '', $step); ?>
                    <li><?= htmlspecialchars($text, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ol>
        </section>
    </div>

    <!-- Кнопки действий: редактировать, удалить, назад к списку -->
    <p class="recipe-actions">
        <a href="/recipes/<?= $recipe->id ?>/edit" class="btn-edit">Редактировать</a>
        &nbsp;
        <form method="post" action="/recipes/<?= $recipe->id ?>/delete" class="inline-form"
              onsubmit="return confirm('Удалить рецепт?')">   <!-- Подтверждение перед удалением -->
            <button type="submit" class="btn-delete">Удалить рецепт</button>
        </form>
        &nbsp;
        <a href="/recipes">← Все рецепты</a>
    </p>
</article>

<!-- Блок комментариев (якорь #comments для ссылок) -->
<section class="comments-section" id="comments">
    <h3>Комментарии (<?= count($comments) ?>)</h3>

    <?php if (empty($comments)): ?>
        <!-- Если комментариев нет -->
        <p class="empty-msg">Комментариев пока нет. Будьте первым!</p>
    <?php else: ?>
        <!-- Перебираем и выводим все комментарии -->
        <?php foreach ($comments as $comment): ?>
            <div class="comment-card">
                <div class="comment-header">
                    <strong class="comment-author"><?= htmlspecialchars($comment->author, ENT_QUOTES, 'UTF-8') ?></strong>
                    <div class="comment-header-right">
                        <span class="date"><?= htmlspecialchars($comment->createdAt, ENT_QUOTES, 'UTF-8') ?></span>
                        <!-- Форма для удаления комментария -->
                        <form method="post" action="/recipes/<?= $recipe->id ?>/comments/<?= $comment->id ?>/delete"
                              class="inline-form" onsubmit="return confirm('Удалить комментарий?')">
                            <button type="submit" class="btn-delete-comment">Удалить</button>
                        </form>
                    </div>
                </div>
                <!-- Текст комментария с поддержкой переносов строк (nl2br) -->
                <p class="comment-text"><?= nl2br(htmlspecialchars($comment->text, ENT_QUOTES, 'UTF-8')) ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Форма добавления нового комментария -->
    <div class="comment-form-wrap">
        <h4>Оставить комментарий</h4>
        <form method="post" action="/recipes/<?= $recipe->id ?>" class="comment-form">
            <div class="form-group">
                <label for="author">Ваше имя</label>
                <input type="text" id="author" name="author" placeholder="Введите имя" required maxlength="128">
            </div>
            <div class="form-group">
                <label for="text">Комментарий</label>
                <textarea id="text" name="text" rows="4" placeholder="Поделитесь впечатлениями о рецепте..." required></textarea>
            </div>
            <button type="submit" class="btn-submit">Отправить</button>
        </form>
    </div>
</section>

<?php endif; ?>