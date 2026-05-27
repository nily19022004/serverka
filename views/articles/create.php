<h2>Добавить новую статью</h2>

<!-- Сообщения об ошибках -->
<?php if (!empty($errors)): ?>
    <div class="error-message">
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Форма добавления статьи -->
<form method="POST" action="/blog/index.php?page=store" class="article-form">
    
    <!-- Поле: Название статьи -->
    <div class="form-group">
        <label for="name">Название статьи:</label>
        <input type="text" 
               id="name" 
               name="name" 
               value="<?= htmlspecialchars($name ?? '') ?>" 
               required
               placeholder="Введите название статьи">
    </div>
    
    <!-- Поле: Выбор автора -->
    <div class="form-group">
        <label for="author_id">Автор:</label>
        <select id="author_id" name="author_id" required>
            <option value="">-- Выберите автора --</option>
            <?php foreach ($users as $user): ?>
                <option value="<?= $user->id ?>" 
                    <?= (isset($author_id) && $author_id == $user->id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($user->nickname) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <!-- Поле: Текст статьи -->
    <div class="form-group">
        <label for="text">Текст статьи:</label>
        <textarea id="text" 
                  name="text" 
                  rows="12" 
                  required
                  placeholder="Введите текст статьи..."><?= htmlspecialchars($text ?? '') ?></textarea>
    </div>
    
    <!-- Кнопки -->
    <div class="form-group">
        <button type="submit" class="btn-submit">Сохранить статью</button>
        <a href="/blog/index.php" class="btn-cancel">Отмена</a>
    </div>
    
</form>

<!-- Стили для формы -->
<style>
    .article-form {
        max-width: 800px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #333;
    }
    
    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        box-sizing: border-box;
    }
    
    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #333;
        box-shadow: 0 0 5px rgba(51, 51, 51, 0.2);
    }
    
    .form-group textarea {
        resize: vertical;
        font-family: Arial, sans-serif;
    }
    
    .btn-submit {
        background: #333;
        color: #fff;
        border: none;
        padding: 10px 24px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.2s;
    }
    
    .btn-submit:hover {
        background: #555;
    }
    
    .btn-cancel {
        margin-left: 10px;
        color: #666;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 6px;
        transition: background 0.2s;
    }
    
    .btn-cancel:hover {
        background: #f0f0f0;
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
        margin: 5px 0;
        color: #ff0000;
    }
</style>
