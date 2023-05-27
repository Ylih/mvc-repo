<?php

namespace App\Blackjack;

use Exception;

/** @author Jesper Yli-Hukka Högback */

/**
 * The Player class is meant to act as a player in a card game.
 * The function of this class is to hold multiple Hand objects and attributes associated with a player (name, money etc.).
 */

class Player
{
    protected string $name;
    protected float $money;
    /** @var Hand[] $hands */
    protected array $hands;
    private int $currentHand;

    /**
     * The constructor constructs a Player object with a name and a balance.
     * @param string $name the name of the player.
     * @param float $money the balance that the player is supposed to have.
     */
    public function __construct(string $name = "", float $money = 0)
    {
        $this->name = $name;
        $this->money = $money;
        $this->hands = [];
        $this->currentHand = -1;
    }

    /**
     * instantiate and add Hand objects to the player $hands array based on the $amount param.
     * @param int $amount the amount of Hand objects to add to the $hands array.
     * @return void
     */
    public function createHands(int $amount = 1): void
    {
        for ($i = 0; $i < $amount; $i++) {
            $this->hands[] = new Hand();
        }

        $this->currentHand = $amount;
    }

    /**
     * get the string of $name
     * @return string $name
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * set the value of $name
     * @param string $name the name to set
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * get the value of $money
     * @return float $money
     */
    public function getMoney(): float
    {
        return (float) $this->money;
    }

    /**
     * set the value of $money
     * @param float $value set the value of player money.
     * @return void
     */
    public function setMoney(float $value): void
    {
        $this->money = $value;
    }

    /**
     * Add numeric value to $this->money
     * @param float $value the amount to add to the current value of $money.
     * @return void
     */
    public function addMoney(float $value): void
    {
        $this->money += $value;
    }

    /**
     * get the value of currentHand
     * @return int $currentHand
     */
    public function getCurrent(): int
    {
        return (int) $this->currentHand;
    }

    /**
     * reduce the value of $currentHand by -1
     * @return void
     */
    public function reduceCurrent(): void
    {
        $this->currentHand = $this->currentHand - 1;
    }

    /**
     * get array of player hands
     * @return Hand[] $hands
     */
    public function getHands(): array
    {
        return (array) $this->hands;
    }

    /**
     * set the value of $hands
     * @param Hand[] $hands
     */
    public function setHands(array $hands): void
    {
        $this->hands = $hands;
    }

    /**
     * Get hand on given index or null if index doesn't exist.
     * @param int $index get hand on given index from the $hands array
     * @return Hand|null returns hand on given index or returns null if index doesn't exist.
     */
    public function getHandOnIndex(int $index): Hand|null
    {
        if ($index >= 0 && $index < count($this->hands)) {
            return $this->hands[$index];
        }
        return null;
    }

    /**
     * get hand on the index of $currentHand or first hand in array if $currentHand is less than 0.
     * @return Hand $hand
     */
    public function getActiveHand(): Hand
    {
        if ($this->currentHand <= 0) {
            return $this->hands[0];
        }
        return $this->hands[$this->currentHand - 1];
    }

    /**
     * Gets current active hand then while hand is flagged as played reduces $currentHand and gets new active hand.
     * @return Hand hand that is not flagged as played or first hand in array.
     */
    public function nextPlayableHand(): Hand
    {
        $activeHand = $this->getActiveHand();
        while ($activeHand->isPlayed() === true) {
            $this->reduceCurrent();
            if ($this->currentHand < 0) {
                break;
            }
            $activeHand = $this->getActiveHand();
        }
        return $activeHand;
    }

    /**
     * Get an array of hands as an associative array where "values" is an array of strings with the card names,
     * "stake" is the current stake of hand, "sum" is the current sum of all cards in hand and "status" is the status text of the hand.
     * @return array<int,array{values: array<int, string>, stake: int, sum: int, status: string}>
     */
    public function getHandsAssociative(): array
    {
        $values = [];
        foreach ($this->hands as $hand) {
            $values[] = $hand->getAssociative();
        }
        return $values;
    }

    /**
     * iterate through hands and add their stakes together then compare $money to the sum to see if $money is greater than or equal to the sum of the stakes.
     * @return bool
     */
    private function canPlaceSameBet(): bool
    {
        $sum = 0;
        foreach ($this->hands as $hand) {
            $sum += $hand->getStake();
        }

        return $this->money >= $sum;
    }

    /**
     * compare $money to the sum of hands * 10
     * @return bool
     */
    private function canPlaceMinBet(): bool
    {
        $sum = count($this->hands) * 10;

        return $this->money >= $sum;
    }

    /**
     * get associative array of booleans that controls whether the player can place same bet again or the min bet.
     * @return array<string, bool>
     */
    public function getMoneyStatus(): array
    {
        return ["sameBet" => $this->canPlaceSameBet(), "minBet" => $this->canPlaceMinBet()];
    }

    /**
     * get an array containing the stakes of the hands in $hands array.
     * @return array<int>
     */
    public function getStakes(): array
    {
        $bets = [];
        foreach ($this->hands as $hand) {
            $bets[] = $hand->getStake();
        }

        return $bets;
    }

    /**
     * iterate through the $hands array and control if all hands are played.
     * @return bool
     */
    public function isAllPlayed(): bool
    {
        foreach ($this->hands as $hand) {
            if ($hand->isPlayed() === false) {
                return false;
            }
        }
        return true;
    }
}
