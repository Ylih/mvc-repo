<?php

namespace App\Card;

class TwentyOneGame
{
    protected CardHand $bank;
    protected CardHand $player;
    protected DeckOfCards $deck;
    protected bool $game = true;

    public function __construct()
    {
        $this->bank = new CardHand();
        $this->player = new CardHand();
        $this->deck = new DeckOfCards();
    }

    public function getBank(): CardHand
    {
        return $this->bank;
    }

    public function getPlayer(): CardHand
    {
        return $this->player;
    }

    public function getDeck(): DeckOfCards
    {
        return $this->deck;
    }

    public function getGame(): bool
    {
        return $this->game;
    }

    public function setBank(CardHand $hand): void
    {
        $this->bank = $hand;
    }

    public function setPlayer(CardHand $hand): void
    {
        $this->player = $hand;
    }

    public function setDeck(DeckOfCards $deck): void
    {
        $this->deck = $deck;
    }

    public function setGameOver(): void
    {
        $this->game = false;
    }

    public function checkLimit(CardHand $hand): bool
    {
        return $hand->getSum() > 21;
    }

    public function containsAce(CardHand $hand): bool
    {
        $hand = $hand->getHand();
        foreach ($hand as $card) {
            if($card->getName() === "ace" && $card->getValue() === 14) {
                return true;
            }
        }
        return false;
    }

    public function handleAce(CardHand $hand): void
    {
        $hand = $hand->getHand();
        foreach ($hand as $card) {
            if($card->getName() === "ace" && $card->getValue() === 14) {
                $card->setValue(1);
                break;
            }
        }
    }

    public function autoDraw(CardHand $hand, DeckOfCards $deck): void
    {
        while ($hand->getSum() < 17) {
            $card = $deck->draw();
            $hand->add($card);

            if ($this->checkLimit($hand)) {
                if ($this->containsAce($hand)) {
                    $this->handleAce($hand);
                }
            }
        }
    }

    public function compareHands(): string
    {
        if ($this->getBank()->getSum() >= $this->getPlayer()->getSum()) {
            return "Bank";
        }
        return "Player";
    }
}
