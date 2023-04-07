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

    public function getName(): int
    {
        return $this->name;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getAsString(): string
    {
        return "{$this->name}-of-{$this->type}";
    }
}
