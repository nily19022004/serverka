<?php if ($article === null): ?>

<h2>404</h2>
<p>Статья не найдена.</p>

<?php else: ?>

<article class="article-page">
    <h2><?= htmlspecialchars($article->name) ?></h2>

    <!-- ВЫВОДИМ АВТОРА -->
    <p class="author">
        Автор: 
        <?php if ($author !== null): ?>
            <strong><?= htmlspecialchars($author->nickname) ?></strong>
        <?php else: ?>
            <strong>Неизвестный автор</strong>
        <?php endif; ?>
    </p>

    <p class="date">
        Дата: <?= htmlspecialchars($article->createdAt) ?>
    </p>

    <p>
        <?= nl2br(htmlspecialchars($article->text)) ?>
    </p>

    <p>
        <a href="/blog/index.php">← Назад к списку статей</a>
    </p>
</article>

<?php endif; ?>
