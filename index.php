<?php

function addNumbers($a,$b)
{
    return $a + $b;
}

/**
 * @throws Exception
 */
function subtractNumbers($a,$b)
{
    return $a - $b;
}

/**
 * @throws Exception
 */
function multiplyNumbers($a, $b)
{
    return $a * $b;
}

/**
 * @throws Exception
 */
function divideNumbers($a, $b)
{
    if (abs($b) < 0.0000001) {
        throw new Exception('Деление на ноль невозможно');
    }
    return $a / $b;
}

/**
 * @throws Exception
 */
function powerNumbers($a, $b)
{
    $result = $a ** $b;
    if (!is_finite($result)) {
        throw new Exception('Некорректное возведение в степень');
    }
    return $result;
}

/**
 * @throws Exception
 */
function squareRootNumber($number)
{
    if ($number < 0) {
        throw new Exception('Корень из отрицательного числа невозможен');
    }
    return sqrt($number);
}

/**
 * @throws Exception
 */
function naturalLogNumber($number)
{
    if ($number <= 0) {
        throw new Exception('ln можно вычислить только для положительного числа');
    }
    return log($number);
}

/**
 * @throws Exception
 */
function decimalLogNumber($number)
{
    if ($number <= 0) {
        throw new Exception('log можно вычислить только для положительного числа');
    }
    return log10($number);
}

/**
 * @throws Exception
 */
function factorialNumber($number)
{
    if ($number < 0) {
        throw new Exception('Факториал отрицательного числа невозможен');
    }
    if (abs($number - round($number)) > 0.0000001) {
        throw new Exception('Факториал можно вычислить только для целого числа');
    }
    if ($number > 170) {
        throw new Exception('Слишком большое число для факториала');
    }
    $result = 1;
    for ($i = 2; $i <= (int)$number; $i++) {
        $result *= $i;
    }
    return $result;
}

function formatResult( $number)
{
    if (abs($number - round($number)) < 0.0000001) {
        return (string)round($number);
    }
    return rtrim(rtrim(number_format($number, 10, '.', ''), '0'), '.');
}

class CalculatorParser
{
    private $expression = '';
    private  $position = 0;

    /**
     * @throws Exception
     */
    public function calculate($expression)
    {
        $this->expression = strtolower(trim($expression));
        $this->expression = str_replace(',', '.', $this->expression);
        $this->expression = preg_replace('/\s+/', '', $this->expression);
        $this->position = 0;

        if ($this->expression === '') {
            throw new Exception('Введите выражение');
        }
        if (!preg_match('/^[0-9+\-*\/^().!a-z]+$/', $this->expression)) {
            throw new Exception('Выражение содержит недопустимые символы');
        }

        $result = $this->parseExpression();

        if ($this->position !== strlen($this->expression)) {
            throw new Exception('Некорректное выражение');
        }
        if (!is_finite($result)) {
            throw new Exception('Результат невозможно вычислить');
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    private function parseExpression()
    {
        $result = $this->parseTerm();
        while (!$this->isEnd()) {
            if ($this->match('+')) {
                $result = addNumbers($result, $this->parseTerm());
            } elseif ($this->match('-')) {
                $result = subtractNumbers($result, $this->parseTerm());
            } else {
                break;
            }
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    private function parseTerm()
    {
        $result = $this->parsePower();
        while (!$this->isEnd()) {
            if ($this->match('*')) {
                $result = multiplyNumbers($result, $this->parsePower());
            } elseif ($this->match('/')) {
                $result = divideNumbers($result, $this->parsePower());
            } else {
                break;
            }
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    private function parsePower()
    {
        $result = $this->parseUnary();
        if ($this->match('^')) {
            $result = powerNumbers($result, $this->parsePower());
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    private function parseUnary()
    {
        if ($this->match('+')) {
            return $this->parseUnary();
        }
        if ($this->match('-')) {
            return -$this->parseUnary();
        }
        return $this->parsePostfix();
    }

    /**
     * @throws Exception
     */
    private function parsePostfix()
    {
        $result = $this->parsePrimary();
        while ($this->match('!')) {
            $result = factorialNumber($result);
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    private function parsePrimary()
    {
        if ($this->match('(')) {
            $result = $this->parseExpression();
            if (!$this->match(')')) {
                throw new Exception('Не закрыта скобка');
            }
            return $result;
        }
        if ($this->startsWith('sqrt')) {
            return $this->parseFunction('sqrt');
        }
        if ($this->startsWith('ln')) {
            return $this->parseFunction('ln');
        }
        if ($this->startsWith('log')) {
            return $this->parseFunction('log');
        }
        if ($this->startsWith('pi')) {
            $this->position += 2;
            return pi();
        }
        if ($this->startsWith('e')) {
            $this->position += 1;
            return exp(1);
        }
        return $this->parseNumber();
    }

    /**
     * @throws Exception
     */
    private function parseFunction($functionName)
    {
        $this->position += strlen($functionName);
        if (!$this->match('(')) {
            throw new Exception('После функции должна быть открывающая скобка');
        }
        $value = $this->parseExpression();
        if (!$this->match(')')) {
            throw new Exception('После аргумента функции должна быть закрывающая скобка');
        }
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
     * @throws Exception
     */
    private function parseNumber()
    {
        $number   = '';
        $hasDigit = false;
        $hasDot   = false;

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

    private function match($expected)
    {
        if ($this->current() === $expected) {
            $this->position++;
            return true;
        }
        return false;
    }

    private function startsWith($value)
    {
        return substr($this->expression, $this->position, strlen($value)) === $value;
    }

    private function current()
    {
        if ($this->isEnd()) {
            return null;
        }
        return $this->expression[$this->position];
    }

    private function isEnd()
    {
        return $this->position >= strlen($this->expression);
    }
}

// ── Handle POST ────────────────────────────────────────────────────────────────

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expression = isset($_POST['expression']) ? (string)$_POST['expression'] : '';
    try {
        $parser = new CalculatorParser();
        $result = $parser->calculate($expression);
        header(
            'Location: ./index.php?expression=' . urlencode($expression) .
            '&result=' . urlencode(formatResult($result))
        );
        exit;
    } catch (Exception $error) {
        header(
            'Location: ./index.php?expression=' . urlencode($expression) .
            '&error=' . urlencode($error->getMessage())
        );
        exit;
    }
}

$expressionFromGet = isset($_GET['expression']) ? (string)$_GET['expression'] : '';
$resultFromGet     = isset($_GET['result'])     ? (string)$_GET['result']     : '';
$errorFromGet      = isset($_GET['error'])      ? (string)$_GET['error']      : '';

$displayValue = $resultFromGet !== '' ? $resultFromGet : $expressionFromGet;
?>
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


<header class="header">
    <img class="logo" src="image/mospoly.png" alt="logo">
    <h2 class="header-title">Домашняя работа: Calculator</h2>
    <div class="header-spacer"></div>
</header>

<main class="main">
    <div class="calc-wrapper">

        <div class="calc-topbar">
            <span class="calc-label">КАЛЬКУЛЯТОР</span>
        </div>

        <form action="./index.php" method="POST">

            <div class="display-area">
                <?php if ($expressionFromGet !== '' && $resultFromGet !== ''): ?>
                    <div class="display-expr"><?= htmlspecialchars($expressionFromGet) ?></div>
                <?php endif; ?>
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
                <?php if ($errorFromGet !== ''): ?>
                    <div class="error-msg"><?= htmlspecialchars($errorFromGet) ?></div>
                <?php endif; ?>
            </div>

            <div class="btn-grid">
                <!-- Row 1: functions -->
                <button type="button" data-value="sqrt(" class="btn btn--fn">√</button>
                <button type="button" data-value="ln("   class="btn btn--fn">ln</button>
                <button type="button" data-value="log("  class="btn btn--fn">log</button>
                <button type="button" data-value="!"     class="btn btn--fn">x!</button>

                <!-- Row 2: constants, power, open-paren -->
                <button type="button" data-value="pi"   class="btn btn--fn">π</button>
                <button type="button" data-value="e"    class="btn btn--fn">e</button>
                <button type="button" data-value="^"    class="btn btn--fn">xʸ</button>
                <button type="button" data-value="("    class="btn btn--fn">(</button>

                <!-- Row 3: C, backspace, close-paren, divide -->
                <button type="button" id="clear"        class="btn btn--action">C</button>
                <button type="button" id="backspace"    class="btn btn--action">⌫</button>
                <button type="button" data-value=")"    class="btn btn--fn">)</button>
                <button type="button" data-value="/"    class="btn btn--op">÷</button>

                <!-- Row 4 -->
                <button type="button" data-value="7"   class="btn">7</button>
                <button type="button" data-value="8"   class="btn">8</button>
                <button type="button" data-value="9"   class="btn">9</button>
                <button type="button" data-value="*"   class="btn btn--op">×</button>

                <!-- Row 5 -->
                <button type="button" data-value="4"   class="btn">4</button>
                <button type="button" data-value="5"   class="btn">5</button>
                <button type="button" data-value="6"   class="btn">6</button>
                <button type="button" data-value="-"   class="btn btn--op">−</button>

                <!-- Row 6 -->
                <button type="button" data-value="1"   class="btn">1</button>
                <button type="button" data-value="2"   class="btn">2</button>
                <button type="button" data-value="3"   class="btn">3</button>
                <button type="button" data-value="+"   class="btn btn--op">+</button>

                <!-- Row 7 -->
                <button type="button" data-value="0"   class="btn btn--wide">0</button>
                <button type="button" data-value="."   class="btn">.</button>
                <button type="submit"                  class="btn btn--eq">=</button>
            </div>

        </form>


    </div>
</main>

<footer class="footer">задание для самостоятельной работы</footer>

<script src="./script.js"></script>
</body>
</html>