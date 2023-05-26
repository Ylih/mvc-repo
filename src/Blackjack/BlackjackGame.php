<?php

namespace App\Blackjack;

use Exception;

class BlackjackGame
{
    protected Hand $bank;
    protected Player $player;
    protected Deck $deck;
    protected bool $gameOver = false;

    public function __construct()
    {
        $this->bank = new Hand();
        $this->player = new Player();
        $this->deck = new Deck();
    }

    public function getBank(): Hand
    {
        return $this->bank;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getDeck(): Deck
    {
        return $this->deck;
    }

    public function getGameOver(): bool
    {
        return $this->gameOver;
    }

    public function setBank(Hand $hand): void
    {
        $this->bank = $hand;
    }

    public function setPlayer(Player $player): void
    {
        $this->player = $player;
    }

    public function setDeck(Deck $deck): void
    {
        $this->deck = $deck;
    }

    public function setGameOver(): void
    {
        $this->gameOver = true;
    }

    public function isBust(Hand $hand): bool
    {
        return $hand->getSum() > 21;
    }

    public function deal(): void
    {
        if (empty($this->deck->getDeck())) {
            throw new Exception("The deck is empty.");
        }

        $pHands = $this->player->getHands();
        foreach ($pHands as $hand) {
            $cards = $this->deck->drawMultiple(2);
            foreach ($cards as $card) {
                $hand->add($card);
            }
            $this->controlHand($hand);
        }

        $card = $this->deck->draw();
        $this->bank->add($card);
    }

    public function controlBlackjack(): void
    {
        $hands = $this->player->getHands();
        foreach ($hands as $hand) {
            if ($hand->getSum() === 21 && $hand->countCards() === 2) {
                $hand->setPlayed();
                $hand->setStatus("Blackjack");
            }
        }
    }

    public function controlHand(Hand $hand): void
    {
        if ($this->isBust($hand) && $hand->containsAce()) {
            $hand->handleAce();
            return;
        }

        if ($this->isBust($hand)) {
            $hand->setPlayed();
            $hand->setStatus("Bust");
            $this->player->reduceCurrent();
        }
    }

    /**
     * @param array<int, int> $bets
     */
    public function takeBets(array $bets): void
    {
        $player = $this->getPlayer();
        $money = $player->getMoney();
        $hands = $player->getHands();
        $count = 0;
        foreach ($bets as $bet) {
            $bet = intval($bet);
            $currHand = $hands[$count];
            $currHand->setStake($bet);

            $money = $money - $bet;
            $count++;
        }

        $player->setMoney($money);
        $player->setHands($hands);
    }

    public function autoDraw(Hand $hand, Deck $deck): void
    {
        while ($hand->getSum() < 17) {
            $card = $deck->draw();
            $hand->add($card);

            if ($this->isBust($hand)) {
                if ($hand->containsAce()) {
                    $hand->handleAce();
                }
            }
        }
    }

    public function payOut(): void
    {
        $bankScore = $this->bank->getSum();
        foreach ($this->player->getHands() as $hand) {

            if ($this->isBust($hand)) {
                continue;
            }

            $handScore = $hand->getSum();

            if ($hand->getStatus() === "Blackjack" && $bankScore != 21) {
                $sum = $hand->getStake() * 2.5;
                $this->player->addMoney($sum);
                continue;
            }

            if ($handScore > $bankScore || $bankScore > 21) {
                $this->handleWin($hand);
            } elseif ($handScore < $bankScore) {
                $hand->setStatus("Lose");
            } elseif ($handScore === $bankScore) {
                $this->handleTie($hand);
            }
        }
    }

    private function handleWin(Hand $hand): void
    {
        $sum = $hand->getStake() * 2;
        $this->player->addMoney($sum);
        $hand->setStatus("Win");
    }

    private function handleTie(Hand $hand): void
    {
        $sum = $hand->getStake();
        $this->player->addMoney($sum);
        $hand->setStatus("Tie");
    }
}
