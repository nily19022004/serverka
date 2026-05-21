<?php

// Интерфейс — контракт: любой класс, реализующий его, обязан иметь метод calculateSquare().
interface CalculateSquare
{
    // Метод без тела — только объявление. Реализацию пишет каждый класс самостоятельно.
    public function calculateSquare(): float;
}

// Circle реализует интерфейс CalculateSquare через ключевое слово implements. Это значит, PHP проверит: метод calculateSquare() должен быть реализован.
class Circle implements CalculateSquare
{
    private float $radius;

    public function __construct(float $radius)
    {
        $this->radius = $radius;
    }

    // Реализация метода интерфейса: площадь круга = π * r². M_PI — встроенная константа PHP со значением числа π.
    public function calculateSquare(): float
    {
        return M_PI * $this->radius ** 2; // ** — оператор возведения в степень
    }
}

// Rectangle тоже реализует тот же интерфейс, но по-другому. Один интерфейс — разные реализации: это и есть смысл полиморфизма.
class Rectangle implements CalculateSquare
{
    private float $width;
    private float $height;

    public function __construct(float $width, float $height)
    {
        $this->width  = $width;
        $this->height = $height;
    }

    // Реализация метода интерфейса: площадь прямоугольника = ширина * высота.
    public function calculateSquare(): float
    {
        return $this->width * $this->height;
    }
}

// Mouse не реализует CalculateSquare — просто пустой класс для демонстрации.
class Mouse
{
}

// Массив с объектами разных классов — два реализуют интерфейс, один нет.
$objects = [
    new Circle(5),
    new Rectangle(4, 6),
    new Mouse()
];

foreach ($objects as $object) {

    // instanceof проверяет, реализует ли объект данный интерфейс (или является ли экземпляром класса). Это безопасный способ вызвать метод, не зная заранее тип объекта.
    if ($object instanceof CalculateSquare) {

        // get_class() возвращает имя класса объекта в виде строки.
        echo "Объект класса " . get_class($object) .
            " имеет площадь: " .
            $object->calculateSquare() . "<br>";

    } else {

        // Mouse не реализует интерфейс — попасть в эту ветку.
        echo "Объект класса " . get_class($object) .
            " не реализует интерфейс CalculateSquare.<br>";
    }
}