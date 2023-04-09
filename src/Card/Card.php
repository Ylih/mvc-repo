<?php

namespace App\Card;

class Card
{
    protected $value;
    protected $name;
    protected $type;

    public function __construct($type, $name, $value)
    {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getAsString(): string
    {
        return "{$this->name} of {$this->type}";
    }

    public function getAsLowRes(): string
    {
        $types = [
            "hearts" => '♥',
            "clubs" => '♣',
            "diamonds" => '♦',
            "spades" => '♠',
        ];
        $letter = [
            1 => "A",
            2 => "2",
            3 => "3",
            4 => "4",
            5 => "5",
            6 => "6",
            7 => "7",
            8 => "8",
            9 => "9",
            10 => "10",
            11 => "J",
            12 => "Q",
            13 => "K",
        ];
        return "[{$letter[$this->getValue()]}{$types[$this->getType()]}]";
    }

    public function getAsArray(): array
    {
        return [
            "type" => $this->getType(),
            "name" => $this->getName(),
            "value" => $this->getValue(),
        ];
    }
}
