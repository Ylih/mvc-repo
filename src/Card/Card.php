<?php

namespace App\Card;

/** @author Jesper Yli-Hukka Högback */

/**
 * The Card class is meant to act as a card in a card game.
 * The function of this class is to hold attributes associated with a card in a card game.
 */
class Card
{
    protected int $value;
    protected string $name;
    protected string $type;

    /**
     * The constructor constructs a Card object with the specified type, name and value.
     * @param string $type the type that the card is supposed to have. Example: "spades"
     * @param string $name the name that the card is supposed to have. Example: "ace"
     * @param int $value the value that the card is supposed to have.
     */
    public function __construct(string $type, string $name, int $value)
    {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * get the string of $type
     * @return string $type
     */
    public function getType(): string
    {
        return (string) $this->type;
    }

    /**
     * get the string of $name
     * @return string $name
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * get the int of $value
     * @return int $value
     */
    public function getValue(): int
    {
        return (int) $this->value;
    }

    /**
     * set the int of $value
     * @return void
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    /**
     * get a string containing a CSS class "alt-container" and $name-$type. Example: "alt-container ace-spades"
     * @return string
     */
    public function getAsString(): string
    {
        return "alt-container {$this->name}-{$this->type}";
    }

    /**
     * get a string with card letter and symbol.
     * The method contains two associative arrays, $types and $letter. Types contain the symbol associated with the type "hearts" => "♥".
     * Letter pointers are of type int so the value of the card returns its value as a string. Example: 13 => "K".
     * @return string
     */
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
     * get an associative array with the attributes of the card.
     *
     * @return array{type: string, name: string, value: int}
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
