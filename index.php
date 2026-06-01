<?php

// ВРЕМЕННАЯ ПРОВЕРКА (удалить потом)
$testExpression = trim(file_get_contents(__DIR__ . '/Task/expression.txt'));
echo "<!-- Тест: выражение из файла = $testExpression -->\n";
echo "<!-- Результат: " . (4/3 * cos(deg2rad(30))) . " -->\n";

require_once __DIR__ . '/trigonometric.php';
// ==================== БАЗОВЫЕ МАТЕМАТИЧЕСКИЕ ФУНКЦИИ ====================

/**
 * Сложение двух чисел
 * @param float $a - первое слагаемое
 * @param float $b - второе слагаемое
 * @return float - сумма
 */
function addNumbers($a,$b)
{
    return $a + $b;
}

/**
 * Вычитание двух чисел
 * @param float $a - уменьшаемое
 * @param float $b - вычитаемое
 * @return float - разность
 * @throws Exception
 */
function subtractNumbers($a,$b)
{
    return $a - $b;
}

/**
 * Умножение двух чисел
 * @param float $a - первый множитель
 * @param float $b - второй множитель
 * @return float - произведение
 * @throws Exception
 */
function multiplyNumbers($a, $b)
{
    return $a * $b;
}

/**
 * Деление двух чисел с проверкой деления на ноль
 * @param float $a - делимое
 * @param float $b - делитель
 * @return float - частное
 * @throws Exception - если делитель близок к нулю (погрешность 1e-7)
 */
function divideNumbers($a, $b)
{
    // Проверяем, что делитель не равен нулю с учетом погрешности чисел с плавающей точкой
    if (abs($b) < 0.0000001) {
        throw new Exception('Деление на ноль невозможно');
    }
    return $a / $b;
}

/**
 * Возведение в степень
 * @param float $a - основание
 * @param float $b - показатель степени
 * @return float - результат возведения в степень
 * @throws Exception - если результат выходит за пределы допустимых значений
 */
function powerNumbers($a, $b)
{
    $result = $a ** $b;  // Оператор ** для возведения в степень
    if (!is_finite($result)) {  // Проверка на бесконечность или не число
        throw new Exception('Некорректное возведение в степень');
    }
    return $result;
}

/**
 * Квадратный корень числа
 * @param float $number - число (должно быть неотрицательным)
 * @return float - квадратный корень
 * @throws Exception - если число отрицательное
 */
function squareRootNumber($number)
{
    if ($number < 0) {
        throw new Exception('Корень из отрицательного числа невозможен');
    }
    return sqrt($number);  // Встроенная функция sqrt()
}

/**
 * Натуральный логарифм (ln)
 * @param float $number - число (должно быть положительным)
 * @return float - натуральный логарифм
 * @throws Exception - если число <= 0
 */
function naturalLogNumber($number)
{
    if ($number <= 0) {
        throw new Exception('ln можно вычислить только для положительного числа');
    }
    return log($number);  // log() без основания = натуральный логарифм
}

/**
 * Десятичный логарифм (log10)
 * @param float $number - число (должно быть положительным)
 * @return float - десятичный логарифм
 * @throws Exception - если число <= 0
 */
function decimalLogNumber($number)
{
    if ($number <= 0) {
        throw new Exception('log можно вычислить только для положительного числа');
    }
    return log10($number);  // log10() - десятичный логарифм
}

/**
 * Факториал числа (n!)
 * @param float $number - целое неотрицательное число (максимум 170)
 * @return int - факториал числа
 * @throws Exception - при отрицательном, нецелом или слишком большом числе
 */
function factorialNumber($number)
{
    // Проверка на отрицательное число
    if ($number < 0) {
        throw new Exception('Факториал отрицательного числа невозможен');
    }
    // Проверка на целое число (с учетом погрешности)
    if (abs($number - round($number)) > 0.0000001) {
        throw new Exception('Факториал можно вычислить только для целого числа');
    }
    // 170! - максимальное значение, которое помещается в float (дальше бесконечность)
    if ($number > 170) {
        throw new Exception('Слишком большое число для факториала');
    }
    // Вычисление факториала через цикл: 5! = 1*2*3*4*5 = 120
    $result = 1;
    for ($i = 2; $i <= (int)$number; $i++) {
        $result *= $i;
    }
    return $result;
}

/**
 * Форматирование числа для красивого вывода
 * Убирает лишние нули и десятичную точку, если число целое
 * @param float $number - число для форматирования
 * @return string - отформатированная строка
 */
function formatResult( $number)
{
    // Если число целое (с учетом погрешности) - выводим без десятичной части
    if (abs($number - round($number)) < 0.0000001) {
        return (string)round($number);
    }
    // Иначе форматируем с 10 знаками после запятой и удаляем лишние нули
    return rtrim(rtrim(number_format($number, 10, '.', ''), '0'), '.');
}

// ==================== КЛАСС ПАРСЕРА МАТЕМАТИЧЕСКИХ ВЫРАЖЕНИЙ ====================
// Реализует метод рекурсивного спуска для разбора и вычисления выражений
// Грамматика выражений:
//   Expression -> Term { ('+'|'-') Term }
//   Term -> Power { ('*'|'/') Power }
//   Power -> Unary { '^' Power }
//   Unary -> ['+'|'-'] Postfix
//   Postfix -> Primary { '!' }
//   Primary -> Number | '(' Expression ')' | Function | Constant

class CalculatorParser
{
    private $expression = '';   // Исходное выражение
    private $position = 0;       // Текущая позиция при разборе

    /**
     * Главный метод: вычисляет переданное выражение
     * @param string $expression - математическое выражение
     * @return float - результат вычисления
     * @throws Exception - при ошибках в выражении
     */
    public function calculate($expression)
    {
        // Подготовка выражения: убираем пробелы, приводим к нижнему регистру
        $this->expression = strtolower(trim($expression));
        $this->expression = str_replace(',', '.', $this->expression);  // Замена запятой на точку
        $this->expression = preg_replace('/\s+/', '', $this->expression);  // Удаление всех пробелов
        $this->position = 0;

        // Проверка на пустое выражение
        if ($this->expression === '') {
            throw new Exception('Введите выражение');
        }
        // Валидация допустимых символов: цифры, операции, буквы для функций, скобки
        if (!preg_match('/^[0-9+\-*\/^().!a-z]+$/', $this->expression)) {
            throw new Exception('Выражение содержит недопустимые символы');
        }

        // Запуск рекурсивного разбора
        $result = $this->parseExpression();

        // Проверка, что вся строка разобрана (не осталось лишних символов)
        if ($this->position !== strlen($this->expression)) {
            throw new Exception('Некорректное выражение');
        }
        // Проверка, что результат - конечное число
        if (!is_finite($result)) {
            throw new Exception('Результат невозможно вычислить');
        }

        return $result;
    }

    /**
     * Разбор выражения: обрабатывает сложение и вычитание
     * Expression -> Term { ('+'|'-') Term }
     * @return float
     * @throws Exception
     */
    private function parseExpression()
    {
        $result = $this->parseTerm();  // Получаем первый терм
        // Пока не достигнут конец, обрабатываем операторы + и -
        while (!$this->isEnd()) {
            if ($this->match('+')) {
                $result = addNumbers($result, $this->parseTerm());  // Сложение
            } elseif ($this->match('-')) {
                $result = subtractNumbers($result, $this->parseTerm());  // Вычитание
            } else {
                break;  // Если следующий символ не + и не -, выходим
            }
        }
        return $result;
    }

    /**
     * Разбор терма: обрабатывает умножение и деление
     * Term -> Power { ('*'|'/') Power }
     * @return float
     * @throws Exception
     */
    private function parseTerm()
    {
        $result = $this->parsePower();  // Получаем первый множитель/делимое
        while (!$this->isEnd()) {
            if ($this->match('*')) {
                $result = multiplyNumbers($result, $this->parsePower());  // Умножение
            } elseif ($this->match('/')) {
                $result = divideNumbers($result, $this->parsePower());  // Деление
            } else {
                break;
            }
        }
        return $result;
    }

    /**
     * Разбор степени: обрабатывает возведение в степень (правоассоциативно)
     * Power -> Unary { '^' Power }
     * @return float
     * @throws Exception
     */
    private function parsePower()
    {
        $result = $this->parseUnary();  // Получаем основание
        if ($this->match('^')) {
            // Правоассоциативность: 2^3^4 = 2^(3^4)
            $result = powerNumbers($result, $this->parsePower());
        }
        return $result;
    }

    /**
     * Разбор унарных операторов: + и -
     * Unary -> ['+'|'-'] Postfix
     * @return float
     * @throws Exception
     */
    private function parseUnary()
    {
        if ($this->match('+')) {
            return $this->parseUnary();  // Унарный плюс ничего не меняет
        }
        if ($this->match('-')) {
            return -$this->parseUnary();  // Унарный минус меняет знак
        }
        return $this->parsePostfix();
    }

    /**
     * Разбор постфиксных операторов: факториал
     * Postfix -> Primary { '!' }
     * @return float
     * @throws Exception
     */
    private function parsePostfix()
    {
        $result = $this->parsePrimary();  // Получаем число или выражение
        // Обрабатываем все знаки факториала (например, 5!!)
        while ($this->match('!')) {
            $result = factorialNumber($result);
        }
        return $result;
    }

    /**
     * Разбор первичных элементов: числа, скобки, функции, константы
     * Primary -> Number | '(' Expression ')' | Function | Constant
     * @return float
     * @throws Exception
     */
    private function parsePrimary()
    {
        // Обработка выражения в скобках
        if ($this->match('(')) {
            $result = $this->parseExpression();
            if (!$this->match(')')) {
                throw new Exception('Не закрыта скобка');
            }
            return $result;
        }
        // Обработка функции квадратного корня
        if ($this->startsWith('sqrt')) {
            return $this->parseFunction('sqrt');
        }
        // Обработка натурального логарифма
        if ($this->startsWith('ln')) {
            return $this->parseFunction('ln');
        }
        // Обработка десятичного логарифма
        if ($this->startsWith('log')) {
            return $this->parseFunction('log');
        }
        // Константа π (пи)
        if ($this->startsWith('pi')) {
            $this->position += 2;
            return pi();  // Встроенная константа π
        }
        // Константа e (число Эйлера)
        if ($this->startsWith('e')) {
            $this->position += 1;
            return exp(1);  // e^1 = e
        }
        // Если ничего из вышеперечисленного - парсим число
        return $this->parseNumber();
    }

    /**
     * Разбор математических функций (sqrt, ln, log)
     * @param string $functionName - имя функции
     * @return float
     * @throws Exception
     */
    private function parseFunction($functionName)
    {
        $this->position += strlen($functionName);  // Пропускаем имя функции
        if (!$this->match('(')) {
            throw new Exception('После функции должна быть открывающая скобка');
        }
        $value = $this->parseExpression();  // Парсим аргумент функции
        if (!$this->match(')')) {
            throw new Exception('После аргумента функции должна быть закрывающая скобка');
        }
        // Вызываем соответствующую функцию
        if ($functionName === 'sqrt') {
            return squareRootNumber($value);
        }
        if ($functionName === 'ln') {
            return naturalLogNumber($value);
        }
        if ($functionName === 'log') {
            return decimalLogNumber($value);
        }
        throw new Exception('Неизвестная функция');
    }

    /**
     * Разбор числа (целого или с плавающей точкой)
     * @return float
     * @throws Exception
     */
    private function parseNumber()
    {
        $number   = '';
        $hasDigit = false;
        $hasDot   = false;

        // Считываем символы, пока это цифры или десятичная точка
        while (!$this->isEnd()) {
            $char = $this->current();
            if ($char !== null && ctype_digit($char)) {
                $number .= $char;
                $hasDigit = true;
                $this->position++;
                continue;
            }
            if ($char === '.' && !$hasDot) {
                $number .= $char;
                $hasDot = true;
                $this->position++;
                continue;
            }
            break;
        }

        if (!$hasDigit) {
            throw new Exception('Ожидалось число');
        }
        return (float)$number;
    }

    /**
     * Проверяет, соответствует ли текущий символ ожидаемому, и продвигает позицию
     * @param string $expected - ожидаемый символ
     * @return bool - true если совпадает
     */
    private function match($expected)
    {
        if ($this->current() === $expected) {
            $this->position++;
            return true;
        }
        return false;
    }

    /**
     * Проверяет, начинается ли оставшаяся часть строки с заданной подстроки
     * @param string $value - искомая подстрока
     * @return bool
     */
    private function startsWith($value)
    {
        return substr($this->expression, $this->position, strlen($value)) === $value;
    }

    /**
     * Возвращает текущий символ или null, если достигнут конец
     * @return string|null
     */
    private function current()
    {
        if ($this->isEnd()) {
            return null;
        }
        return $this->expression[$this->position];
    }

    /**
     * Проверяет, достигнут ли конец строки
     * @return bool
     */
    private function isEnd()
    {
        return $this->position >= strlen($this->expression);
    }
}

// ==================== ОБРАБОТКА HTTP ЗАПРОСОВ ====================

// Обработка POST-запроса (отправка формы)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expression = isset($_POST['expression']) ? (string)$_POST['expression'] : '';
    try {
        // Создаем парсер и вычисляем выражение
        $parser = new CalculatorParser();
        $result = $parser->calculate($expression);
        // Перенаправляем на ту же страницу с результатом в GET-параметрах
        header(
            'Location: ./index.php?expression=' . urlencode($expression) .
            '&result=' . urlencode(formatResult($result))
        );
        exit;
    } catch (Exception $error) {
        // При ошибке перенаправляем с сообщением об ошибке
        header(
            'Location: ./index.php?expression=' . urlencode($expression) .
            '&error=' . urlencode($error->getMessage())
        );
        exit;
    }
}

// Получение данных из GET-параметров (после перенаправления)
$expressionFromGet = isset($_GET['expression']) ? (string)$_GET['expression'] : '';
$resultFromGet     = isset($_GET['result'])     ? (string)$_GET['result']     : '';
$errorFromGet      = isset($_GET['error'])      ? (string)$_GET['error']      : '';

// Определяем, что показывать в поле ввода: результат или исходное выражение
$displayValue = $resultFromGet !== '' ? $resultFromGet : $expressionFromGet;
?>

<!-- ==================== HTML ИНТЕРФЕЙС КАЛЬКУЛЯТОРА ==================== -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Калькулятор</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600&family=IBM+Plex+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
</head>
<body>

<!-- ШАПКА САЙТА -->
<header class="header">
    <img class="logo" src="image/mospoly.png" alt="logo">
    <h2 class="header-title">Домашняя работа: Calculator</h2>
    <div class="header-spacer"></div>
</header>

<!-- ОСНОВНОЙ КОНТЕНТ -->
<main class="main">
    <div class="calc-wrapper">

        <div class="calc-topbar">
            <span class="calc-label">КАЛЬКУЛЯТОР</span>
        </div>

        <!-- Форма калькулятора. action="./index.php" - отправляем на этот же файл -->
        <form action="./index.php" method="POST">

            <div class="display-area">
                <!-- Если есть выражение и результат - показываем выражение мелким шрифтом -->
                <?php if ($expressionFromGet !== '' && $resultFromGet !== ''): ?>
                    <div class="display-expr"><?= htmlspecialchars($expressionFromGet) ?></div>
                <?php endif; ?>
                <!-- Поле ввода/вывода -->
                <label for="display"></label><input
                        class="display<?= $errorFromGet !== '' ? ' display--error' : '' ?>"
                        id="display"
                        type="text"
                        name="expression"
                        value="<?= htmlspecialchars($displayValue) ?>"
                        placeholder="0"
                        autocomplete="off"
                        spellcheck="false"
                >
                <!-- Вывод сообщения об ошибке -->
                <?php if ($errorFromGet !== ''): ?>
                    <div class="error-msg"><?= htmlspecialchars($errorFromGet) ?></div>
                <?php endif; ?>
            </div>

            <!-- СЕТКА КНОПОК КАЛЬКУЛЯТОРА -->
            <div class="btn-grid">
                <!-- Ряд 1: функции -->
                <button type="button" data-value="sqrt(" class="btn btn--fn">√</button>
                <button type="button" data-value="ln("   class="btn btn--fn">ln</button>
                <button type="button" data-value="log("  class="btn btn--fn">log</button>
                <button type="button" data-value="!"     class="btn btn--fn">x!</button>

                <!-- Ряд 2: константы, степень, открывающая скобка -->
                <button type="button" data-value="pi"   class="btn btn--fn">π</button>
                <button type="button" data-value="e"    class="btn btn--fn">e</button>
                <button type="button" data-value="^"    class="btn btn--fn">xʸ</button>
                <button type="button" data-value="("    class="btn btn--fn">(</button>

                <!-- Ряд 3: очистка, удаление, закрывающая скобка, деление -->
                <button type="button" id="clear"        class="btn btn--action">C</button>
                <button type="button" id="backspace"    class="btn btn--action">⌫</button>
                <button type="button" data-value=")"    class="btn btn--fn">)</button>
                <button type="button" data-value="/"    class="btn btn--op">÷</button>

                <!-- Ряд 4 -->
                <button type="button" data-value="7"   class="btn">7</button>
                <button type="button" data-value="8"   class="btn">8</button>
                <button type="button" data-value="9"   class="btn">9</button>
                <button type="button" data-value="*"   class="btn btn--op">×</button>

                <!-- Ряд 5 -->
                <button type="button" data-value="4"   class="btn">4</button>
                <button type="button" data-value="5"   class="btn">5</button>
                <button type="button" data-value="6"   class="btn">6</button>
                <button type="button" data-value="-"   class="btn btn--op">−</button>

                <!-- Ряд 6 -->
                <button type="button" data-value="1"   class="btn">1</button>
                <button type="button" data-value="2"   class="btn">2</button>
                <button type="button" data-value="3"   class="btn">3</button>
                <button type="button" data-value="+"   class="btn btn--op">+</button>

                <!-- Ряд 7: ноль (широкая кнопка), точка, равно -->
                <button type="button" data-value="0"   class="btn btn--wide">0</button>
                <button type="button" data-value="."   class="btn">.</button>
                <button type="submit"                  class="btn btn--eq">=</button>
            </div>

        </form>

    </div>
</main>

<!-- ПОДВАЛ -->
<footer class="footer">задание для самостоятельной работы</footer>

<!-- ПОДКЛЮЧЕНИЕ JAVASCRIPT ДЛЯ ОБРАБОТКИ НАЖАТИЙ КНОПОК -->
<script src="./script.js"></script>
</body>
</html>