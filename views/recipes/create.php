<article class="recipe-page">
    <h2>Новый рецепт</h2>

    <?php if ($error !== null): ?>
        <p class="form-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post" action="/recipes/create" class="edit-form">

        <div class="form-group">
            <label for="name">Название рецепта *</label>
            <input type="text" id="name" name="name"
                   value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="Например: Борщ классический" required>
        </div>

        <div class="form-group">
            <label for="description">Краткое описание *</label>
            <textarea id="description" name="description" rows="3"
                      placeholder="Короткое описание блюда" required><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Сложность *</label>
                <div class="star-picker" id="starPicker">
                    <?php for ($i = 1; $i <= 3; $i++): ?>
                        <span class="star-btn <?= $difficulty >= $i ? 'active' : '' ?>"
                              data-val="<?= $i ?>">★</span>
                    <?php endfor; ?>
                </div>
                <input type="hidden" id="difficulty" name="difficulty" value="<?= $difficulty ?>">
            </div>

            <div class="form-group">
                <label for="cook_time">Время приготовления</label>
                <input type="text" id="cook_time" name="cook_time"
                       value="<?= htmlspecialchars($cook_time, ENT_QUOTES, 'UTF-8') ?>"
                       placeholder="Например: 30 мин">
            </div>
        </div>

        <div class="form-group">
            <label for="ingredients">Ингредиенты * <small>(каждый с новой строки)</small></label>
            <textarea id="ingredients" name="ingredients" rows="8"
                      placeholder="Мука — 200 г&#10;Яйца — 2 шт.&#10;Молоко — 100 мл"
                      required><?= htmlspecialchars($ingredients, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="form-group">
            <label for="steps">Шаги приготовления * <small>(каждый с новой строки)</small></label>
            <textarea id="steps" name="steps" rows="10"
                      placeholder="1. Смешать муку и яйца.&#10;2. Добавить молоко.&#10;3. Обжарить на сковороде."
                      required><?= htmlspecialchars($steps, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">Опубликовать рецепт</button>
            <a href="/recipes" class="btn-cancel">Отмена</a>
        </div>
    </form>
</article>

<script>
(function () {
    var stars  = document.querySelectorAll('.star-btn');
    var hidden = document.getElementById('difficulty');

    function update(val) {
        hidden.value = val;
        stars.forEach(function (s) {
            s.classList.toggle('active', parseInt(s.dataset.val) <= val);
        });
    }

    stars.forEach(function (s) {
        s.addEventListener('click', function () { update(parseInt(s.dataset.val)); });
        s.addEventListener('mouseenter', function () {
            stars.forEach(function (b) {
                b.classList.toggle('hover', parseInt(b.dataset.val) <= parseInt(s.dataset.val));
            });
        });
    });

    document.getElementById('starPicker').addEventListener('mouseleave', function () {
        stars.forEach(function (b) { b.classList.remove('hover'); });
    });
})();
</script>
