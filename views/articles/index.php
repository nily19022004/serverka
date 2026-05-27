<h2>Список статей</h2>

<?php if (empty($articles)): ?>
    <p>Статей пока нет.</p>
<?php else: ?>
    <?php foreach ($articles as $article): ?>
        <div class="article-card">
            <h3>
                <a href="/blog/index.php?page=article&id=<?= $article->id ?>">
                    <?= htmlspecialchars($article->name) ?>
                </a>
            </h3>

            <p>
                <?= htmlspecialchars(substr($article->text, 0, 120)) ?>...
            </p>

            <p class="date">
                Дата: <?= htmlspecialchars($article->createdAt) ?>
            </p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>