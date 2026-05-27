<?php if ($article === null): ?>

<h2>404</h2>
<p>Статья не найдена.</p>

<?php else: ?>

<article class="article-page">
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div style="flex: 1;">
            <h2><?= htmlspecialchars($article->name) ?></h2>
        </div>
        
        <!-- Кнопка удаления на странице статьи -->
        <div>
            <a href="/blog/index.php?page=delete&id=<?= $article->id ?>" 
               class="delete-btn"
               onclick="return confirm('Вы уверены, что хотите удалить статью «<?= htmlspecialchars($article->name) ?>»?')">
                Удалить статью
            </a>
        </div>
    </div>
    
    <!-- Выводим автора -->
    <p class="author">
        Автор: 
        <?php if ($author !== null): ?>
            <strong><?= htmlspecialchars($author->nickname) ?></strong>
        <?php else: ?>
            <strong>Неизвестный автор</strong>
        <?php endif; ?>
    </p>
    
    <p class="date">
        Дата создания: <?= htmlspecialchars($article->createdAt) ?>
    </p>
    
    <?php if (isset($error)): ?>
        <div class="error-message">
            <p>Ошибка: <?= htmlspecialchars($error) ?></p>
        </div>
    <?php endif; ?>
    
    <div class="article-text">
        <?= nl2br(htmlspecialchars($article->text)) ?>
    </div>
    
    <hr>
    
    <p>
        <a href="/blog/index.php">Назад к списку статей</a>
    </p>
</article>

<style>
    .delete-btn {
        background: #ff4444;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        transition: background 0.2s;
        display: inline-block;
    }
    
    .delete-btn:hover {
        background: #cc0000;
        text-decoration: none;
    }
    
    .error-message {
        background: #ffeeee;
        border-left: 4px solid #ff0000;
        padding: 12px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
    
    .error-message p {
        margin: 0;
        color: #ff0000;
    }
</style>

<?php endif; ?>