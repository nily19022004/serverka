<?php
// D:\Desktop\study\2 семестр\serv\views\recipes\create.php
// Представление: форма для создания нового рецепта
?>

<article class="recipe-page">
    <h2>Новый рецепт</h2>

    <?php if ($error !== null): ?>
        <!-- Показываем сообщение об ошибке, если оно передано -->
        <p class="form-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <!-- Форма отправляется методом POST на тот же URL (/recipes/create) -->
    <form method="post" action="/recipes/create" class="edit-form">

        <!-- Поле: название рецепта -->
        <div class="form-group">
            <label for="name">Название рецепта *</label>
            <input type="text" id="name" name="name"
                   value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="Например: Борщ классический" required>
        </div>

        <!-- Поле: краткое описание -->
        <div class="form-group">
            <label for="description">Краткое описание *</label>
            <textarea id="description" name="description" rows="3"
                      placeholder="Короткое описание блюда" required><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <!-- Строка с двумя полями: сложность и время приготовления -->
        <div class="form-row">
            <!-- Сложность (звёздный picker) -->
            <div class="form-group">
                <label>Сложность *</label>
                <div class="star-picker" id="starPicker">
                    <?php for ($i = 1; $i <= 3; $i++): ?>
                        <!-- active — если текущая сложность >= номеру звезды -->
                        <span class="star-btn <?= $difficulty >= $i ? 'active' : '' ?>"
                              data-val="<?= $i ?>">★</span>
                    <?php endfor; ?>
                </div>
                <!-- Скрытое поле, в которое записывается выбранная сложность (1,2,3) -->
                <input type="hidden" id="difficulty" name="difficulty" value="<?= $difficulty ?>">
            </div>

            <!-- Время приготовления -->
            <div class="form-group">
                <label for="cook_time">Время приготовления</label>
                <input type="text" id="cook_time" name="cook_time"
                       value="<?= htmlspecialchars($cook_time, ENT_QUOTES, 'UTF-8') ?>"
                       placeholder="Например: 30 мин">
            </div>
        </div>

        <!-- Ингредиенты (текстовое поле, каждый ингредиент с новой строки) -->
        <div class="form-group">
            <label for="ingredients">Ингредиенты * <small>(каждый с новой строки)</small></label>
            <textarea id="ingredients" name="ingredients" rows="8"
                      placeholder="Мука — 200 г&#10;Яйца — 2 шт.&#10;Молоко — 100 мл"
                      required><?= htmlspecialchars($ingredients, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <!-- Шаги приготовления (текстовое поле, каждый шаг с новой строки) -->
        <div class="form-group">
            <label for="steps">Шаги приготовления * <small>(каждый с новой строки)</small></label>
            <textarea id="steps" name="steps" rows="10"
                      placeholder="1. Смешать муку и яйца.&#10;2. Добавить молоко.&#10;3. Обжарить на сковороде."
                      required><?= htmlspecialchars($steps, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <!-- Кнопки: сохранить и отмена -->
        <div class="form-actions">
            <button type="submit" class="btn-save">Опубликовать рецепт</button>
            <a href="/recipes" class="btn-cancel">Отмена</a>
        </div>
    </form>
</article>

<!-- JavaScript для выбора сложности (звёздочки) -->
<script>
(function () {
    var stars  = document.querySelectorAll('.star-btn');      // Все звёздочки
    var hidden = document.getElementById('difficulty');      // Скрытое поле со значением

    // Функция обновления активных звёзд и скрытого поля
    function update(val) {
        hidden.value = val;                                   // Записываем выбранное значение
        stars.forEach(function (s) {
            // Звезда активна, если её номер <= выбранному значению
            s.classList.toggle('active', parseInt(s.dataset.val) <= val);
        });
    }

    // Обработчик клика на звёздочку — выбираем сложность
    stars.forEach(function (s) {
        s.addEventListener('click', function () { update(parseInt(s.dataset.val)); });
        // При наведении подсвечиваем звёзды до текущей (эффект предпросмотра)
        s.addEventListener('mouseenter', function () {
            stars.forEach(function (b) {
                b.classList.toggle('hover', parseInt(b.dataset.val) <= parseInt(s.dataset.val));
            });
        });
    });

    // При уходе мыши с области звёзд — убираем эффект предпросмотра
    document.getElementById('starPicker').addEventListener('mouseleave', function () {
        stars.forEach(function (b) { b.classList.remove('hover'); });
    });
})();
</script>