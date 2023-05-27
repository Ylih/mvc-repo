<?php

namespace App\Blackjack;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackjackGame.
 */
class BlackjackGameTest extends TestCase
{
    /**
     * Construct object.
     */
    public function testCreateObject(): void
    {
        $game = new BlackjackGame();
        $bank = $game->getBank();
        $player = $game->getPlayer();
        $deck = $game->getDeck();
        $gameOver = $game->isGameOver();

        $this->assertInstanceOf("\App\Blackjack\BlackjackGame", $game);
        $this->assertInstanceOf("\App\Blackjack\Hand", $bank);
        $this->assertInstanceOf("\App\Blackjack\Player", $player);
        $this->assertInstanceOf("\App\Blackjack\Deck", $deck);

        $bank = $bank->getHand();
        $player = $player->getHands();
        $deck = $deck->getDeck();

        $this->assertEmpty($bank);
        $this->assertEmpty($player);
        $this->assertEmpty($deck);
        $this->assertFalse($gameOver);
    }

    /**
     * Verify setBank works.
     */
    public function testSet(): void
    {
        $game = new BlackjackGame();

        $bank = $game->getBank();
        $player = $game->getPlayer();
        $deck = $game->getDeck();
        $gameOver = $game->isGameOver();

        $this->assertEmpty($bank->getHand());
        $this->assertEmpty($player->getHands());
        $this->assertEmpty($deck->getDeck());
        $this->assertFalse($gameOver);

        $cards = [
            new Card("spades", "ace", 11),
            new Card("hearts", "king", 10),
            new Card("diamonds", "queen", 10)
        ];

        $newBank = new Hand();

        foreach ($cards as $card) {
            $newBank->add($card);
        }

        $newPlayer = new Player();
        $newPlayer->createHands(3);

        $newDeck = new Deck();
        $newDeck->createNormalDeck();

        $game->setGameOver();

        $game->setBank($newBank);
        $game->setPlayer($newPlayer);
        $game->setDeck($newDeck);

        $bank = $game->getBank();
        $player = $game->getPlayer();
        $deck = $game->getDeck();
        $gameOver = $game->isGameOver();

        $this->assertCount(3, $bank->getHand());
        $this->assertContainsOnlyInstancesOf("\App\Blackjack\Card", $bank->getHand());

        $this->assertCount(3, $player->getHands());
        $this->assertContainsOnlyInstancesOf("\App\Blackjack\Hand", $player->getHands());

        $this->assertCount(52, $deck->getDeck());
        $this->assertContainsOnlyInstancesOf("\App\Blackjack\Card", $deck->getDeck());

        $this->assertTrue($gameOver);
    }

    /**
     * Verify isBust works.
     */
    public function testIsBust(): void
    {
        $game = new BlackjackGame();
        $hand = $game->getBank();

        $emptyHand = new Hand();

        $cards = [
            new Card("spades", "ace", 11),
            new Card("hearts", "king", 10),
            new Card("diamonds", "queen", 10)
        ];

        foreach ($cards as $card) {
            $hand->add($card);
        }

        $this->assertTrue($game->isBust($hand));
        $this->assertFalse($game->isBust($emptyHand));
    }

    /**
     * Verify autoDraw works.
     */
    public function testAutoDraw(): void
    {
        $game = new BlackjackGame();
        $bank = $game->getBank();
        $deck = $game->getDeck();
        $deck->createNormalDeck();

        $this->assertCount(52, $deck->getDeck());

        $deck->drawMultiple(9);

        $this->assertCount(43, $deck->getDeck());

        $game->autoDraw($bank, $deck);

        $this->assertNotCount(43, $deck->getDeck());
        $this->assertGreaterThanOrEqual(17, $bank->getSum());
        $this->assertCount(6, $bank->getHand());
        $this->assertEquals(25, $bank->getSum());
    }

    /**
     * Verify deal works.
     */
    public function testDeal(): void
    {
        $game = new BlackjackGame();

        $game->getPlayer()->createHands(3);

        $game->getDeck()->createNormalDeck();
        $game->deal();

        foreach ($game->getPlayer()->getHands() as $hand) {
            $this->assertCount(2, $hand->getHand());
        }

        $this->assertCount(1, $game->getBank()->getHand());
    }

    /**
     * Verify deal exception thrown.
     */
    public function testDealException(): void
    {
        $game = new BlackjackGame();

        $game->getPlayer()->createHands(3);

        //No cards in deck.
        $this->expectException(\Exception::class);
        $game->deal();
    }

    /**
     * Verify controlBlackjack works.
     */
    public function testControlBlackjack(): void
    {
        $game = new BlackjackGame();

        $game->getPlayer()->createHands(3);
        $game->getDeck()->createNormalDeck();
        $game->deal();

        #21 ace, king
        #20 queen, jack
        #19 ten, nine

        $game->controlBlackjack();
        $hands = $game->getPlayer()->getHands();

        $this->assertTrue($hands[0]->isPlayed());
        $this->assertEquals("Blackjack", $hands[0]->getStatus());

        $this->assertFalse($hands[1]->isPlayed());
        $this->assertEquals("", $hands[1]->getStatus());

        $this->assertFalse($hands[2]->isPlayed());
        $this->assertEquals("", $hands[2]->getStatus());
    }

    /**
     * Verify controlHand works.
     */
    public function testControlHand(): void
    {
        $game = new BlackjackGame();

        $cards = [
            new Card("spades", "ace", 11),
            new Card("hearts", "king", 10),
            new Card("diamonds", "two", 2)
        ];

        $hand = $game->getBank();

        foreach ($cards as $card) {
            $hand->add($card);
        }

        $this->assertEquals(23, $hand->getSum());

        $game->controlHand($hand);

        $this->assertEquals(13, $hand->getSum());

        $hand->add(new Card("diamonds", "queen", 10));

        $game->controlHand($hand);

        $this->assertTrue($hand->isPlayed());
        $this->assertEquals("Bust", $hand->getStatus());
        $this->assertEquals(-2, $game->getPlayer()->getCurrent());
    }

    /**
     * Verify takeBets works.
     */
    public function testTakeBets(): void
    {
        $game = new BlackjackGame();

        $game->getPlayer()->createHands(3);
        $game->getPlayer()->setMoney(120);

        $bets = [10, 20, 30];

        $game->takeBets($bets);
        $hands = $game->getPlayer()->getHands();
        $money = $game->getPlayer()->getMoney();

        $this->assertEquals($hands[0]->getStake(), 10);
        $this->assertEquals($hands[1]->getStake(), 20);
        $this->assertEquals($hands[2]->getStake(), 30);
        $this->assertEquals($money, 60);
    }

    /**
     * Verify payOut works.
     */
    public function testPayOut(): void
    {
        $game = new BlackjackGame();

        $game->getDeck()->createNormalDeck();

        $game->getPlayer()->createHands(5);
        $game->getPlayer()->setMoney(250);

        $bankCards = [
            new Card("diamonds", "ace", 11),
            new Card("hearts", "six", 6),
        ];

        $betterCards = [
            new Card("clubs", "king", 10),
            new Card("hearts", "ten", 10),
        ];

        $equalCards = [
            new Card("hearts", "ace", 11),
            new Card("clubs", "six", 6),
        ];

        $worseCards = [
            new Card("clubs", "three", 3),
            new Card("hearts", "four", 4),
        ];

        $blackjackCards = [
            new Card("spades", "ace", 11),
            new Card("hearts", "king", 10),
        ];

        $bustCards = [
            new Card("spades", "queen", 10),
            new Card("hearts", "queen", 10),
            new Card("spades", "king", 10),
        ];

        foreach ($bankCards as $card) {
            $game->getBank()->add($card);
        }

        $hands = $game->getPlayer()->getHands();

        foreach ($betterCards as $card) {
            $hands[0]->add($card);
        }

        foreach ($blackjackCards as $card) {
            $hands[1]->add($card);
        }
        $hands[1]->setStatus("Blackjack");

        foreach ($bustCards as $card) {
            $hands[2]->add($card);
        }

        foreach ($worseCards as $card) {
            $hands[3]->add($card);
        }

        foreach ($equalCards as $card) {
            $hands[4]->add($card);
        }

        $game->getPlayer()->setHands($hands);

        $bets = [50, 50, 50, 50, 50];

        $game->takeBets($bets);
        $game->payOut();
        $this->assertEquals($game->getPlayer()->getMoney(), 275);
        $this->assertEquals("Win", $game->getPlayer()->getHands()[0]->getStatus());
        $this->assertEquals("Lose", $game->getPlayer()->getHands()[3]->getStatus());
        $this->assertEquals("Tie", $game->getPlayer()->getHands()[4]->getStatus());
    }
}
