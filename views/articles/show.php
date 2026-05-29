<!-- Если статья не найдена - показываем 404 -->
<?php if ($article === null): ?>
<h2>404</h2>
<p>Статья не найдена.</p>
<?php else: ?>
<article class="article-page">
    <!-- Заголовок статьи -->
    <h2><?= htmlspecialchars($article->name, ENT_QUOTES, 'UTF-8') ?></h2>
    
    <!-- Автор статьи -->
    <p class="author">
        Автор: <?php if ($author !== null): ?>
            <strong><?= htmlspecialchars($author->nickname, ENT_QUOTES, 'UTF-8') ?></strong>
        <?php else: ?>
            <strong>не найден</strong>
        <?php endif; ?>
    </p>
    
    <!-- Дата создания -->
    <p class="date">
        Дата создания: <?= htmlspecialchars($article->createdAt, ENT_QUOTES, 'UTF-8') ?>
    </p>
    
    <!-- Полный текст статьи (nl2br сохраняет переносы строк) -->
    <p>
        <?= nl2br(htmlspecialchars($article->text, ENT_QUOTES, 'UTF-8')) ?>
    </p>
    
    <!-- Ссылка назад -->
    <p>
        <a href="/articles" class="btn-edit">← Назад к списку статей</a>
    </p>
</article>
<?php endif; ?>
