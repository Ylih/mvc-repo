<?php

namespace App\Card;

class Card
{
    protected int $value;
    protected string $name;
    protected string $type;

    public function __construct(string $type, string $name, int $value)
    {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
    }

    public function getType(): string
    {
        return (string) $this->type;
    }

    public function getName(): string
    {
        return (string) $this->name;
    }

    public function getValue(): int
    {
        return (int) $this->value;
    }

    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    public function getAsString(): string
    {
        return "alt-container {$this->name}-{$this->type}";
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
            14 => "A",
        ];
        return "{$letter[$this->getValue()]}{$types[$this->getType()]}";
    }

    /**
     * @return array{ "type": string, "name": string, "value": int }
     */
    public function getAsArray(): array
    {
        return [
            "type" => $this->getType(),
            "name" => $this->getName(),
            "value" => $this->getValue(),
        ];
    }
}
