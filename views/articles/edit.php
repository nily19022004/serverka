<!-- Если статья не найдена - 404 -->
<?php if ($article === null): ?>

<h2>404</h2>
<p>Статья не найдена.</p>

<?php else: ?>

<article class="article-page">
    <h2>Редактирование статьи</h2>

    <!-- Форма редактирования отправляется на /articles/{id}/edit -->
    <form method="post" action="/articles/<?= $article->id ?>/edit" class="edit-form">

        <!-- Поле заголовка с текущим значением -->
        <div class="form-group">
            <label for="name">Заголовок</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($article->name, ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <!-- Поле текста с текущим значением -->
        <div class="form-group">
            <label for="text">Текст статьи</label>
            <textarea
                id="text"
                name="text"
                rows="12"
                required
            ><?= htmlspecialchars($article->text, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <!-- Кнопки: сохранить или отменить (вернуться к статье) -->
        <div class="form-actions">
            <button type="submit" class="btn-save">Сохранить</button>
            <a href="/articles/<?= $article->id ?>" class="btn-cancel">Отмена</a>
        </div>

    </form>
</article>

<?php endif; ?>
