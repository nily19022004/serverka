<div class="list-header">
    <h2>Список статей</h2>
    <a href="/articles/create" class="btn-create">+ Написать статью</a>
</div>

<?php if (empty($articles)): ?>
    <p>Статей пока нет.</p>
<?php else: ?>
    <?php foreach ($articles as $article): ?>
        <article class="article-card">
            <h3>
                <a href="/articles/<?= $article->id ?>">
                    <?= htmlspecialchars($article->name, ENT_QUOTES, 'UTF-8') ?>
                </a>
            </h3>

            <p>
                <?= htmlspecialchars(mb_substr($article->text, 0, 120), ENT_QUOTES, 'UTF-8') ?>...
            </p>

            <p class="date">
                Дата создания: <?= htmlspecialchars($article->createdAt, ENT_QUOTES, 'UTF-8') ?>
            </p>
        </article>
    <?php endforeach; ?>
<?php endif; ?>
