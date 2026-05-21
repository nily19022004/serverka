<?php

// Базовый (родительский) класс Lesson.
class Lesson
{
    // protected — свойства доступны внутри этого класса и во всех классах-наследниках, но не снаружи.
    protected string $title;
    protected string $text;
    protected string $assignment;

    // Конструктор базового класса принимает три параметра и сохраняет их в свойства объекта.
    public function __construct(
        string $title,
        string $text,
        string $assignment
    ) {
        $this->title      = $title;
        $this->text       = $text;
        $this->assignment = $assignment;
    }
}

// PaidLesson наследует Lesson с помощью ключевого слова extends.
class PaidLesson extends Lesson
{
    // Дополнительное свойство, которого нет в базовом классе. private — доступно только внутри PaidLesson.
    private float $price;

    // Конструктор наследника принимает все параметры родителя + цену.
    public function __construct(
        string $title,
        string $text,
        string $assignment,
        float $price
    ) {
        // parent::__construct(...) вызывает конструктор родительского класса Lesson. Без этого вызова свойства $title, $text, $assignment не были бы установлены.
        parent::__construct($title, $text, $assignment);

        // Устанавливаем собственное свойство наследника.
        $this->price = $price;
    }

    // Геттер — возвращает цену урока.
    public function getPrice(): float
    {
        return $this->price;
    }

    // Сеттер — позволяет изменить цену урока после создания объекта.
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}

// Создаём объект класса PaidLesson.
$lesson = new PaidLesson(
    "Урок о наследовании в PHP",
    "Лол, кек, чебурек",
    "Ложитесь спать, утро вечера мудренее",
    99.90
);

var_dump($lesson);