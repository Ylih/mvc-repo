<?php

namespace App\Card;

class CardHand
{
    private $hand = [];

    public function add($card): void
    {
        $this->hand[] = $card;
    }

    //discard function?

    public function getHand(): array
    {
        return $this->hand;
    }

    public function countCards(): array
    {
        return count($this->hand);
    }

    public function getString(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getAsString();
        }
        return $values;
    }

    public function getArray(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getAsArray();
        }
        return $values;
    }
}
