<?php

namespace App\Blackjack;

use Exception;

class Deck
{
    /** @var Card[] */
    protected array $deck = [];

    public function createNormalDeck(): void
    {
        $cardType = ["hearts", "spades", "diamonds", "clubs"];
        $card = [
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5,
            'six' => 6,
            'seven' => 7,
            'eight' => 8,
            'nine' => 9,
            'ten' => 10,
            'jack' => 10,
            'queen' => 10,
            'king' => 10,
            'ace' => 11,
        ];

        foreach ($cardType as $type) {
            foreach ($card as $cardName => $cardVal) {
                $this->deck[] = new Card($type, $cardName, $cardVal);
            }
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

    /**
     * @return array<int,Card> $cards
     */
    public function drawMultiple(int $times): array
    {
        $cards = [];
        for ($i = 0; $i < $times; $i++) {
            $card = array_pop($this->deck);

            if ($card === null) {
                throw new Exception("The deck is empty.");
            }

            $cards[] = $card;
        }

        return $cards;
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
}
