<?php

// Класс Cat демонстрирует принцип инкапсуляции:
class Cat
{
    // private — свойства доступны только внутри этого класса.
    private string $name;
    private string $color;

    // Конструктор вызывается автоматически при создании объекта (new Cat(...)) принимает имя и цвет кота и сохраняет их в свойствах объекта.
    public function __construct(string $name, string $color)
    {
        $this->name  = $name;
        $this->color = $color;
    }

    // Геттер — публичный метод для получения приватного свойства $color.
    public function getColor(): string
    {
        return $this->color;
    }

    // Метод выводит приветствие от кота.
    public function sayHello(): void
    {
        // Переменные можно вставлять прямо в строку в двойных кавычках.
        echo "Мяу! Меня зовут $this->name. ";
        echo "Я $this->color цвета.<br>";
    }
}

// Создаём два разных объекта класса Cat.
// Каждый объект хранит свои собственные значения name и color.
$cat1 = new Cat("Мурка", "рыжего");
$cat2 = new Cat("Барсик", "серого");

$cat1->sayHello();
$cat2->sayHello();

// Получаем цвет через геттер — напрямую обратиться к $cat1->color нельзя (private).
echo $cat1->getColor();