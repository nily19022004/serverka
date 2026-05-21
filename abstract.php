<?php

// abstract class — абстрактный класс. Нельзя создать его объект напрямую (new HumanAbstract()). Служит шаблоном: задаёт общую структуру для всех классов-наследников.
abstract class HumanAbstract
{
    // private — имя доступно только внутри этого класса, даже наследники не могут обратиться к нему напрямую.
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    // Обычный публичный метод — конкретная реализация, общая для всех наследников.
    public function getName(): string
    {
        return $this->name;
    }

    // abstract — абстрактный метод: у него нет тела (реализации). Каждый наследник ОБЯЗАН реализовать этот метод самостоятельно.
    abstract public function getGreetings(): string;
    abstract public function getMyNameIs(): string;

    // Конкретный метод, использующий абстрактные методы. Работает одинаково для всех наследников, но результат зависит от их реализации getGreetings() и getMyNameIs().
    public function introduceYourself(): string
    {
        // Собираем строку из трёх частей: приветствие + "меня зовут" + имя.
        return $this->getGreetings() . "! " .
            $this->getMyNameIs() . " " .
            $this->getName() . ".";
    }
}

// RussianHuman наследует HumanAbstract и реализует оба абстрактных метода на русском языке.
class RussianHuman extends HumanAbstract
{
    public function getGreetings(): string
    {
        return "Привет";
    }

    public function getMyNameIs(): string
    {
        return "Меня зовут";
    }
}

// EnglishHuman реализует те же методы, но на английском. Метод introduceYourself() одинаков для обоих — полиморфизм в действии.
class EnglishHuman extends HumanAbstract
{
    public function getGreetings(): string
    {
        return "Hello";
    }

    public function getMyNameIs(): string
    {
        return "My name is";
    }
}

// Создаём объекты конкретных классов (не абстрактного).
$russian = new RussianHuman("Иван");
$english = new EnglishHuman("John");

// Вызываем один и тот же метод — но результат разный у каждого объекта.
echo $russian->introduceYourself() . "<br>";
echo $english->introduceYourself();