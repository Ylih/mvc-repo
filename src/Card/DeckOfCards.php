<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardGraphic;
use Exception;

class DeckOfCards
{
    /** @var Card[] */
    protected array $deck = [];

    public function __construct(string $cardClass = "graphic")
    {
        $cardType = ["hearts", "spades", "diamonds", "clubs"];
        $cardName = [
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
            'ace',
        ];

        switch (strtolower($cardClass)) {
            case "graphic":
                foreach ($cardType as $type) {
                    for ($i = 1; $i <= 13; $i++) {
                        $this->deck[] = new CardGraphic($type, $cardName[$i - 1], $i + 1);
                    }
                }
                break;
            case "basic":
                foreach ($cardType as $type) {
                    for ($i = 1; $i <= 13; $i++) {
                        $this->deck[] = new Card($type, $cardName[$i - 1], $i + 1);
                    }
                }
                break;
            default:
                throw new Exception("That type of card does not exist.");
        }
    }

    /**
     * @return Card[]
     */
    public function getDeck(): array
    {
        return $this->deck;
    }

    public function shuffle(): void
    {
        shuffle($this->deck);
    }

    public function draw(): Card
    {
        if (empty($this->deck)) {
            throw new Exception("The deck is empty.");
        }

        $card = array_pop($this->deck);
        return $card;
    }

    public function getNumberCards(): int
    {
        return count($this->deck);
    }

    /**
     * @return string[]
     */
    public function getString(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getAsString();
        }
        return $values;
    }

    /**
     * @return array<int,array{type: string, name: string, value: int}>
     */
    public function getArray(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getAsArray();
        }
        return $values;
    }

    /**
     * @return string[]
     */
    public function getLowRes(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getAsLowRes();
        }
        return $values;
    }
}
