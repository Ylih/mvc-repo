<?php

namespace App\Blackjack;

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
     * get a string with the name of the card. Example: "ace-of-spades"
     * @return string
     */
    public function getAsString(): string
    {
        return "{$this->name}-of-{$this->type}";
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
