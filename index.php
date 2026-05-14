<?php
// Уравнение: X / 8 = 6
$equation = "X/8=6";

// 1. Разделяем левую и правую часть по знаку "="
$parts = explode("=", $equation);
$left = $parts[0];  // "X/8"
$right = $parts[1]; // "6"

// 2. Проверяем, где находится X (слева или справа от "=")
$isXonLeft = (strpos($left, 'X') !== false);

// 3. Запоминаем выражение с X и известное число
if ($isXonLeft) {
    $expression = $left;  // часть с X
    $value = $right;       // известное число
} else {
    $expression = $right;
    $value = $left;
}
//Если X справа меняем их местами логически.

// 4. Ищем арифметический знак (+, -, *, /) в выражении
$operators = ['+', '-', '*', '/'];
$operator = null;
$parts_expr = [];

foreach ($operators as $op) {
    if (strpos($expression, $op) !== false) { //ищет позицию оператора в строке
        $operator = $op;                 // нашли оператор
        $parts_expr = explode($op, $expression); // делим на две части
        break; // дальше искать не нужно
    }
}

// 5. Определяем, где X: слева или справа от оператора
$isXfirst = (strpos($parts_expr[0], 'X') !== false);
if ($isXfirst) {
    $a = 'X';                  // X слева
    $b = $parts_expr[1];       // число справа
} else {
    $a = $parts_expr[0];       // число слева
    $b = 'X';                  // X справа
}

// 6. Решаем уравнение по правилам математики
switch ($operator) {
    case '+':
        // X + b = value  →  X = value - b
        // a + X = value  →  X = value - a
        $result = ($isXfirst) ? $value - $b : $value - $a;
        break;
    case '-':
        if ($isXfirst) {
            // X - b = value  →  X = value + b
            $result = $value + $b;
        } else {
            // a - X = value  →  X = a - value
            $result = $a - $value;
        }
        break;
    case '*':
        // X * b = value  →  X = value / b
        // a * X = value  →  X = value / a
        $result = ($isXfirst) ? $value / $b : $value / $a;
        break;
    case '/':
        if ($isXfirst) {
            // X / b = value  →  X = value * b
            $result = $value * $b;
        } else {
            // a / X = value  →  X = a / value
            $result = $a / $value;
        }
        break;
}

// 7. Выводим результат
echo "Уравнение: $equation\n";
echo "Результат: X = " . $result . "\n";
?>