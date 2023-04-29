<?php

namespace App\Card;

/** @author Jesper Yli-Hukka Högback */

/**
 * The CardGraphic class is an extension of the Card class.
 */

class CardGraphic extends Card
{
    public function __construct(string $type, string $name, int $value)
    {
        parent::__construct($type, $name, $value);
    }

    /**
     * get a string containing a CSS class "card-container" and $name-of-$type. Example: "card-container ace-of-spades"
     * @return string
     */
    public function getAsString(): string
    {
        return "card-container {$this->name}-of-{$this->type}";
    }

    /**
     * returns an empty string.
     * @return string
     */
    public function getAsLowRes(): string
    {
        return "";
    }
}
