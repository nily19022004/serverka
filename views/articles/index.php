<h2>Список статей</h2>

<!-- Сообщение об успешном добавлении -->
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="success-message">
        <p>Статья успешно добавлена!</p>
    </div>
<?php endif; ?>

<!-- Сообщение об успешном удалении -->
<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
    <div class="success-message">
        <p>Статья успешно удалена!</p>
    </div>
<?php endif; ?>

<!-- Список статей -->
<?php if (empty($articles)): ?>
    <p>Статей пока нет. <a href="/blog/index.php?page=create">Добавить первую статью</a></p>
<?php else: ?>
    <?php foreach ($articles as $article): ?>
        <div class="article-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="flex: 1;">
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
                
                <!-- Кнопка удаления -->
                <div style="margin-left: 15px;">
                    <a href="/blog/index.php?page=delete&id=<?= $article->id ?>" 
                       class="delete-btn"
                       onclick="return confirm('Вы уверены, что хотите удалить статью «<?= htmlspecialchars($article->name) ?>»?')">
                        Удалить
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<style>
    .delete-btn {
        background: #ff4444;
        color: white;
        padding: 5px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 12px;
        transition: background 0.2s;
        display: inline-block;
    }
    
    .delete-btn:hover {
        background: #cc0000;
        text-decoration: none;
    }
    
    .success-message {
        background: #eeffee;
        border-left: 4px solid #00aa00;
        padding: 12px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
    
    .success-message p {
        margin: 0;
        color: #00aa00;
    }
</style>