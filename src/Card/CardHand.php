<?php

namespace App\Card;

use App\Card\DeckOfCards;

// Detta är player klassen. I denna instansierar jag en kortlek åt spelaren och en giv. CardHand blir då ägaren av DeckOfCards som i sin tur äger Card.

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
}
