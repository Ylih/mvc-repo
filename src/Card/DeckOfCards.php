<?php

namespace App\Card;

use App\Card\Card;

class DeckOfCards
{
    private $deck = [];

    public function __construct()
    {
        $cardType = ["hearts", "spades", "diamonds", "clubs"];
        $cardName = [
            'ace',
            'two',
            'three',
            'four',
            'five',
            'six',
            'seven',
            'eight',
            'nine',
            'ten',
            'jack',
            'queen',
            'king',
        ];

        foreach ($cardType as $type) {
            for ($i = 1; $i <= 13; $i++) {
                $this->deck[] = new Card($type, $cardName[$i-1], $i);
            }
        }
    }

    public function shuffle() {
        shuffle($this->deck);
    }

    public function getNumberCards(): int
    {
        return count($this->deck);
    }

    public function getString(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getAsString();
        }
        return $values;
    }
}