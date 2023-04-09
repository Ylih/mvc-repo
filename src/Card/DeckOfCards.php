<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardGraphic;

class DeckOfCards
{
    protected $deck = [];

    public function __construct($cardClass = "graphic")
    {
        $cardClass = strtolower($cardClass);
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

        if ($cardClass === "graphic") {
            foreach ($cardType as $type) {
                for ($i = 1; $i <= 13; $i++) {
                    $this->deck[] = new CardGraphic($type, $cardName[$i-1], $i);
                }
            }
        } elseif ($cardClass === "basic") {
            foreach ($cardType as $type) {
                for ($i = 1; $i <= 13; $i++) {
                    $this->deck[] = new Card($type, $cardName[$i-1], $i);
                }
            }
        } else {
            throw new \Exception("That type of card does not exist.");
        }


    }

    public function shuffle()
    {
        shuffle($this->deck);
    }

    public function draw(): Card
    {
        $card = array_pop($this->deck);
        return $card;
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

    public function getArray(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getAsArray();
        }
        return $values;
    }

    public function getLowRes(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getAsLowRes();
        }
        return $values;
    }

}
