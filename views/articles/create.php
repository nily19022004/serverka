<article class="article-page">
    <h2>Новая статья</h2>

    <!-- Показываем ошибку, если есть -->
    <?php if ($error !== null): ?>
        <p class="form-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <!-- Форма создания статьи -->
    <form method="post" action="/articles/create" class="edit-form">

        <!-- Поле заголовка -->
        <div class="form-group">
            <label for="name">Заголовок</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                placeholder="Введите заголовок статьи"
                required
            >
        </div>

        <!-- Поле текста статьи -->
        <div class="form-group">
            <label for="text">Текст статьи</label>
            <textarea
                id="text"
                name="text"
                rows="12"
                placeholder="Введите текст статьи"
                required
            ><?= htmlspecialchars($text, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <!-- Кнопки управления -->
        <div class="form-actions">
            <button type="submit" class="btn-save">Опубликовать</button>
            <a href="/articles" class="btn-cancel">Отмена</a>
        </div>

    </form>
</article>