<?php

namespace App\Blackjack;

use Exception;

/** @author Jesper Yli-Hukka Högback */

/**
 * The BlackjackGame class is meant to serve as a blackjack table.
 * The function of this class is to hold the games logic and data necessary to play the game.
 */

class BlackjackGame
{
    protected Hand $bank;
    protected Player $player;
    protected Deck $deck;
    protected bool $gameOver = false;

    /**
     * The constructor constructs a BlackjackGame object with a Hand, Player and Deck object.
     */
    public function __construct()
    {
        $this->bank = new Hand();
        $this->player = new Player();
        $this->deck = new Deck();
    }

    /**
     * get the Hand object
     * @return Hand $bank
     */
    public function getBank(): Hand
    {
        return $this->bank;
    }

    /**
     * get the Player object
     * @return Player $player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * get the Deck object
     * @return Deck $deck
     */
    public function getDeck(): Deck
    {
        return $this->deck;
    }

    /**
     * get the boolean of $gameOver
     * @return bool $gameOver
     */
    public function isGameOver(): bool
    {
        return $this->gameOver;
    }

    /**
     * set the Hand object of $bank
     * @param Hand $hand the hand to be set as bank
     * @return void
     */
    public function setBank(Hand $hand): void
    {
        $this->bank = $hand;
    }

    /**
     * set the Player object of $player
     * @param Player $player the player object to be set as player
     * @return void
     */
    public function setPlayer(Player $player): void
    {
        $this->player = $player;
    }

    /**
     * set the Deck object of $deck
     * @param Deck $deck the deck object to be set as deck
     * @return void
     */
    public function setDeck(Deck $deck): void
    {
        $this->deck = $deck;
    }

    /**
     * set the boolean of $gameOver to true.
     * @return void
     */
    public function setGameOver(): void
    {
        $this->gameOver = true;
    }

    /**
     * control if the sum of a hand is over 21
     * @param Hand $hand the hand object to control
     * @return bool
     */
    public function isBust(Hand $hand): bool
    {
        return $hand->getSum() > 21;
    }

    /**
     * deal two cards to each hand in the Players $hands array. Then deal a single card to $bank.
     * @return void
     */
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

    /**
     * iterate through the hands of the Player $hands array and control if the sum is equal to 21 and hand only contains 2 cards.
     * If they both are true then set hand as played and set the status of hand to "Blackjack".
     * @return void
     */
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

    /**
     * control a hand object to see if the hand is a bust but contains an ace that is not yet set to the value of 1 if this is true then handleAce and return from function.
     * If the conditions are false we control if the hand is bust if true flag as played and set status to "Bust" and reduce amount of hands left.
     * @return void
     */
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
     * add bets to each of the hands in the player $hands array.
     * @param array<int, int> $bets
     * @return void
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

    /**
     * draw cards from deck and add them to the hand as long as sum of hand is less than 17
     * @param Hand $hand the hand to add cards to
     * @param Deck $deck the deck to draw cards from
     * @return void
     */
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

    /**
     * controls payout to the player of the round played.
     * @return void
     */
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

    /**
     * get stake of hand and get that times 2 add that sum to player money and then set status to "Win".
     * @param Hand $hand the hand to handle
     * @return void
     */
    private function handleWin(Hand $hand): void
    {
        $sum = $hand->getStake() * 2;
        $this->player->addMoney($sum);
        $hand->setStatus("Win");
    }

    /**
     * get stake of hand and return that sum to player money and then set status to "Tie".
     * @param Hand $hand the hand to handle
     * @return void
     */
    private function handleTie(Hand $hand): void
    {
        $sum = $hand->getStake();
        $this->player->addMoney($sum);
        $hand->setStatus("Tie");
    }
}
