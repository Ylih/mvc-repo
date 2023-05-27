<?php

namespace App\Blackjack;

/** @author Jesper Yli-Hukka Högback */

/**
 * The Hand class is meant to act as a hand in a card game.
 * The function of this class is to hold multiple Card objects.
 */
class Hand
{
    /**
     * $hand is an array that holds Card objects.
     * @var Card[]
     */
    private array $hand = [];
    private int $stake = 0;
    private string $status = "";
    private bool $played = false;

    /**
     * Adds a card to the $hand array.
     * @param Card $card Card object to add.
     * @return void
     */
    public function add(Card $card): void
    {
        $this->hand[] = $card;
    }

    /**
     * Returns the $hand array.
     * @return Card[]
     */
    public function getHand(): array
    {
        return $this->hand;
    }

    /**
     * Returns the $played bool.
     * @return boolean
     */
    public function isPlayed(): bool
    {
        return $this->played;
    }

    /**
     * Set state of Hand to played.
     * @return void
     */
    public function setPlayed(): void
    {
        $this->played = true;
    }

    /**
     * Returns the $stake int.
     * @return int
     */
    public function getStake(): int
    {
        return $this->stake;
    }

    /**
     * Set stake of hand.
     * @param int $stake the stake of hand
     * @return void
     */
    public function setStake(int $stake): void
    {
        $this->stake = $stake;
    }

    /**
     * Get status of hand.
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set status of hand.
     * @param string $status the status to set
     * @return void
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
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
     * get string names of each card in hand in an array, the stake of the hand and the status of the hand.
     * @return array{values: array<int, string>, stake: int, sum: int, status: string}>
     */
    public function getAssociative(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getAsString();
        }
        $stake = $this->getStake();
        $sum = $this->getSum();
        $status = $this->getStatus();
        return ["values" => $values, "stake" => $stake, "sum" => $sum, "status" => $status];
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
     * see if hand contains an ace and if it has not yet been handled (set to 1)
     * @return bool
     */
    public function containsAce(): bool
    {
        foreach ($this->hand as $card) {
            if($card->getName() === "ace" && $card->getValue() === 11) {
                return true;
            }
        }
        return false;
    }

    /**
     * set value of ace to 1.
     * @return void
     */
    public function handleAce(): void
    {
        foreach ($this->hand as $card) {
            if($card->getName() === "ace" && $card->getValue() === 11) {
                $card->setValue(1);
                break;
            }
        }
    }
}
