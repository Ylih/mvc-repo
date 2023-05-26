<?php

namespace App\Blackjack;

use Exception;

class Player
{
    protected string $name;
    protected float $money;
    /** @var Hand[] $hands */
    protected array $hands;
    private int $currentHand;

    public function __construct(string $name = "", float $money = 0)
    {
        $this->name = $name;
        $this->money = $money;
        $this->hands = [];
        $this->currentHand = -1;
    }

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
     */
    public function setMoney(float $value): void
    {
        $this->money = $value;
    }

    /**
     * Add numeric value to $this->money
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
     * get array of player hands
     * @return Hand $hand
     */
    public function getHandOnIndex(int $index): Hand|null
    {
        if ($index >= 0 && $index < count($this->hands)) {
            return $this->hands[$index];
        }
        return null;
    }

    /**
     * get array of player current hand
     * @return Hand $hand
     */
    public function getActiveHand(): Hand
    {
        if ($this->currentHand <= 0) {
            return $this->hands[0];
        }
        return $this->hands[$this->currentHand - 1];
    }

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
     * @return bool
     */
    private function canPlaceMinBet(): bool
    {
        $sum = count($this->hands) * 10;

        return $this->money >= $sum;
    }

    /**
     * @return array<string, bool>
     */
    public function getMoneyStatus(): array
    {
        return ["sameBet" => $this->canPlaceSameBet(), "minBet" => $this->canPlaceMinBet()];
    }

    /**
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
