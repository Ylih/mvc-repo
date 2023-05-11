<?php

namespace App\Card;

/** @author Jesper Yli-Hukka Högback */

/**
 * The CardHand class is meant to act as a hand in a card game.
 * The function of this class is to hold multiple Card objects.
 */
class CardHand
{
    /**
     * $hand is an array that holds Card objects.
     * @var Card[]
     */
    private array $hand = [];

    /**
     * Adds a card to the $hand array.
     * @param Card $card Card object to add.
     * @return void
     */
    public function add(Card $card): void
    {
        $this->hand[] = $card;
    }

    //discard function?

    /**
     * Returns the $hand array.
     * @return Card[]
     */
    public function getHand(): array
    {
        return $this->hand;
    }

    /**
     * Counts the amount of items in the $hand array.
     * @return int the amount of items as a numeric value.
     */
    public function countCards(): int
    {
        return count($this->hand);
    }

    /**
     * Loops through the Card objects in the $hand. For each iteration call the Card method "getAsString" and push result to an array.
     * @return string[] Returns array with the result.
     */
    public function getString(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getAsString();
        }
        return $values;
    }

    /**
     * Sums all the Card values in $hand.
     * Loops through the Card objects in the $hand. For each iteration call the Card method "getValue" and push result to an array.
     * Then uses array_sum on that array to get sum of $hand.
     * @return int Returns the sum of $hand.
     */
    public function getSum(): int
    {
        /** @var int[] $values */
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getValue();
        }

        /** @var int $sum */
        $sum = array_sum($values);

        return $sum;
    }

    /**
     * Get name of all Cards in $hand.
     * Loops through the Card objects in the $hand. For each iteration call the Card method "getName" and push result to an array.
     * @return string[] Returns array containing strings like "ace", "two", "queen".
     */
    public function getNames(): array
    {
        $names = [];
        foreach ($this->hand as $card) {
            $names[] = $card->getName();
        }
        return $names;
    }

    /**
     * Get array containing associative arrays of each Card objects attributes.
     * Loops through the Card objects in the $hand. For each iteration call the Card method "getAsArray" and push result to an array.
     * @return array<int,array{type: string, name: string, value: int}>
     */
    public function getArray(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getAsArray();
        }
        return $values;
    }

    /**
     * Loops through the Card objects in the $hand. For each iteration call the Card method "getAsLowRes" and push result to an array.
     * @return string[] Returns array with the result.
     */
    public function getLowRes(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getAsLowRes();
        }
        return $values;
    }
}
