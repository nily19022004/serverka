<?php
// Файл: trigonometric.php
// Функция для вычисления тригонометрических выражений

/**
 * Вычисляет тригонометрическую функцию от угла в градусах
 * @param string $function - название функции (sin, cos, tan, cot, sec, csc)
 * @param float $degrees - угол в градусах
 * @return float - результат вычисления
 * @throws Exception - если функция неизвестна
 */
function calculateTrigFunction($function, $degrees) {
    // Переводим градусы в радианы
    $radians = deg2rad($degrees);
    
    // Используем symbolic reference (переменная как имя функции)
    // Проверяем, существует ли такая встроенная функция
    switch ($function) {
        case 'sin':
            return sin($radians);
        case 'cos':
            return cos($radians);
        case 'tan':
            return tan($radians);
        case 'cot':
            // Котангенс = 1/tan
            $tanValue = tan($radians);
            if (abs($tanValue) < 0.0000001) {
                throw new Exception('Котангенс от угла ' . $degrees . '° не определен');
            }
            return 1 / $tanValue;
        case 'sec':
            // Секанс = 1/cos
            $cosValue = cos($radians);
            if (abs($cosValue) < 0.0000001) {
                throw new Exception('Секанс от угла ' . $degrees . '° не определен');
            }
            return 1 / $cosValue;
        case 'csc':
            // Косеканс = 1/sin
            $sinValue = sin($radians);
            if (abs($sinValue) < 0.0000001) {
                throw new Exception('Косеканс от угла ' . $degrees . '° не определен');
            }
            return 1 / $sinValue;
        default:
            throw new Exception("Неизвестная тригонометрическая функция: $function");
    }
}

/**
 * Парсит и вычисляет выражение, содержащее тригонометрические функции
 * Пример: "4/3*cos(30)" -> 4/3 * cos(30°)
 * @param string $expression - выражение для вычисления
 * @return float - результат
 * @throws Exception
 */
function evaluateWithTrig($expression) {
    // Находим все тригонометрические функции в выражении
    // Паттерн: имя_функции(число)
    preg_match_all('/(sin|cos|tan|cot|sec|csc)\((\d+(?:\.\d+)?)\)/', $expression, $matches, PREG_SET_ORDER);
    
    $resultExpression = $expression;
    
    // Заменяем каждую функцию на её числовое значение
    foreach ($matches as $match) {
        $fullMatch = $match[0];      // например "cos(30)"
        $funcName = $match[1];       // например "cos"
        $degrees = (float)$match[2]; // например 30
        
        $value = calculateTrigFunction($funcName, $degrees);
        $resultExpression = str_replace($fullMatch, $value, $resultExpression);
    }
    
    // Теперь в выражении остались только числа и арифметические операции
    if (class_exists('CalculatorParser')) {
        $parser = new CalculatorParser();
        return $parser->calculate($resultExpression);
    } else {
        throw new Exception('CalculatorParser не найден. Убедитесь, что этот файл подключен после index.php');
    }
}
?>