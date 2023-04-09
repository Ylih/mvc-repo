<?php

namespace App\Card;

class CardGraphic extends Card
{
    public function __construct($type, $name, $value)
    {
        parent::__construct($type, $name, $value);
    }

    public function getAsString(): string
    {
        return "card-container {$this->name}-of-{$this->type}";
    }

    public function getAsLowRes(): string
    {
        return "";
    }
}
