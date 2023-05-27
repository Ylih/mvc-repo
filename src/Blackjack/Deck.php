<?php

namespace App\Blackjack;

use Exception;

/** @author Jesper Yli-Hukka Högback */

/**
 * The Deck class is meant to act as a deck of cards in a card game.
 * The function of this class is to hold an array of card objects.
 */
class Deck
{
    /** @var Card[] */
    protected array $deck = [];

    /**
     * instantiate and add 52 Card objects to the $deck array.
     * @return void
     */
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
     * get the $deck array holding the cards.
     * @return Card[]
     */
    public function getDeck(): array
    {
        return $this->deck;
    }

    /**
     * shuffle the array of cards.
     * @return void
     */
    public function shuffle(): void
    {
        shuffle($this->deck);
    }

    /**
     * uses array_pop to "draw" the last card in the $deck array.
     * @return Card
     */
    public function draw(): Card
    {
        if (empty($this->deck)) {
            throw new Exception("The deck is empty.");
        }

        $card = array_pop($this->deck);
        return $card;
    }

    /**
     * draw mutliple cards from $deck array.
     * @param int $times number of times to draw
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

    /**
     * get the number of cards in the $deck array.
     * @return int
     */
    public function getNumberCards(): int
    {
        return count($this->deck);
    }

    /**
     * get the result of each Card object getAsString in an array.
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
     * get the result of each Card object getAsArray in an array.
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
