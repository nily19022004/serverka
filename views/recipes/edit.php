<?php
// D:\Desktop\study\2 семестр\serv\views\recipes\edit.php
// Представление: форма для редактирования существующего рецепта
// Принцип аналогичен create.php, но поля предзаполнены данными из $recipe
?>

<article class="recipe-page">
    <h2>Редактирование рецепта</h2>

    <!-- Форма отправляется на /recipes/{id}/edit -->
    <form method="post" action="/recipes/<?= $recipe->id ?>/edit" class="edit-form">

        <div class="form-group">
            <label for="name">Название рецепта *</label>
            <input type="text" id="name" name="name"
                   value="<?= htmlspecialchars($recipe->name, ENT_QUOTES, 'UTF-8') ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="description">Краткое описание *</label>
            <textarea id="description" name="description" rows="3" required><?= htmlspecialchars($recipe->description, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Сложность *</label>
                <div class="star-picker" id="starPicker">
                    <?php for ($i = 1; $i <= 3; $i++): ?>
                        <!-- active — если текущая сложность рецепта >= номеру звезды -->
                        <span class="star-btn <?= $recipe->difficulty >= $i ? 'active' : '' ?>"
                              data-val="<?= $i ?>">★</span>
                    <?php endfor; ?>
                </div>
                <input type="hidden" id="difficulty" name="difficulty" value="<?= $recipe->difficulty ?>">
            </div>

            <div class="form-group">
                <label for="cook_time">Время приготовления</label>
                <input type="text" id="cook_time" name="cook_time"
                       value="<?= htmlspecialchars($recipe->cookTime, ENT_QUOTES, 'UTF-8') ?>"
                       placeholder="Например: 30 мин">
            </div>
        </div>

        <div class="form-group">
            <label for="ingredients">Ингредиенты * <small>(каждый с новой строки)</small></label>
            <textarea id="ingredients" name="ingredients" rows="8" required><?= htmlspecialchars($recipe->ingredients, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="form-group">
            <label for="steps">Шаги приготовления * <small>(каждый с новой строки)</small></label>
            <textarea id="steps" name="steps" rows="10" required><?= htmlspecialchars($recipe->steps, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">Сохранить изменения</button>
            <a href="/recipes/<?= $recipe->id ?>" class="btn-cancel">Отмена</a>
        </div>
    </form>
</article>

<!-- JavaScript для выбора сложности (аналогичен create.php) -->
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