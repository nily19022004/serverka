<div class="list-header">
    <h2>Список статей</h2>
    <!-- Кнопка для создания новой статьи -->
    <a href="/articles/create" class="btn-create">+ Написать статью</a>
</div>

<!-- Если статей нет - выводим сообщение -->
<?php if (empty($articles)): ?>
    <p>Статей пока нет.</p>
<?php else: ?>
    <!-- Перебираем все статьи и выводим карточки -->
    <?php foreach ($articles as $article): ?>
        <article class="article-card">
            <h3>
                <!-- Ссылка на полную версию статьи -->
                <a href="/articles/<?= $article->id ?>">
                    <?= htmlspecialchars($article->name, ENT_QUOTES, 'UTF-8') ?>
                </a>
            </h3>

            <!-- Анонс: первые 120 символов текста -->
            <p>
                <?= htmlspecialchars(mb_substr($article->text, 0, 120), ENT_QUOTES, 'UTF-8') ?>...
            </p>

            <!-- Дата создания статьи -->
            <p class="date">
                Дата создания: <?= htmlspecialchars($article->createdAt, ENT_QUOTES, 'UTF-8') ?>
            </p>
        </article>
    <?php endforeach; ?>
<?php endif; ?>