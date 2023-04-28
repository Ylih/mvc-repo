<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class TwentyOneGame.
 */
class TwentyOneGameTest extends TestCase
{
    /**
     * Construct object.
     */
    public function testCreateObject()
    {
        $game = new TwentyOneGame();
        $bank = $game->getBank();
        $player = $game->getPlayer();
        $deck = $game->getDeck();
        $activeGame = $game->getGame();

        $this->assertInstanceOf("\App\Card\TwentyOneGame", $game);
        $this->assertInstanceOf("\App\Card\CardHand", $bank);
        $this->assertInstanceOf("\App\Card\CardHand", $player);
        $this->assertInstanceOf("\App\Card\DeckOfCards", $deck);

        $bank = $bank->getHand();
        $player = $player->getHand();
        $deck = $deck->getDeck();

        $this->assertEmpty($bank);
        $this->assertEmpty($player);
        $this->assertCount(52, $deck);
        $this->assertTrue($activeGame);
    }

    /**
     * Verify setBank works.
     */
    public function testSetBank()
    {
        $game = new TwentyOneGame();

        $bank = $game->getBank();
        $this->assertEmpty($bank->getHand());

        $newBank = new CardHand();

        $cards = [
            new Card("spades", "ace", 14),
            new Card("hearts", "king", 13),
            new Card("diamonds", "queen", 12)
        ];

        foreach ($cards as $card) {
            $newBank->add($card);
        }

        $game->setBank($newBank);

        $bank = $game->getBank();
        $this->assertCount(3, $bank->getHand());
    }

    /**
     * Verify setPlayer works.
     */
    public function testSetPlayer()
    {
        $game = new TwentyOneGame();

        $player = $game->getPlayer();
        $this->assertEmpty($player->getHand());

        $newPlayer = new CardHand();

        $cards = [
            new Card("spades", "ace", 14),
            new Card("hearts", "king", 13),
            new Card("diamonds", "queen", 12)
        ];

        foreach ($cards as $card) {
            $newPlayer->add($card);
        }

        $game->setPlayer($newPlayer);

        $player = $game->getPlayer();
        $this->assertCount(3, $player->getHand());
    }

    /**
     * Verify setDeck works.
     */
    public function testSetDeck()
    {
        $game = new TwentyOneGame();

        $deck = $game->getDeck();
        $this->assertCount(52, $deck->getDeck());

        while ($deck->getNumberCards() > 42) {
            $deck->draw();
        }

        $this->assertCount(42, $deck->getDeck());

        $newDeck = new DeckOfCards();

        $game->setDeck($newDeck);

        $deck = $game->getDeck();

        $this->assertCount(52, $deck->getDeck());
    }

    /**
     * Verify setGameOver works.
     */
    public function testSetGameOver()
    {
        $game = new TwentyOneGame();
        $activeGame = $game->getGame();
        $this->assertTrue($activeGame);

        $game->setGameOver();

        $activeGame = $game->getGame();
        $this->assertFalse($activeGame);
    }

    /**
     * Verify CheckLimit works.
     */
    public function testCheckLimit()
    {
        $game = new TwentyOneGame();
        $player = $game->getPlayer();

        $hand = new CardHand();

        $cards = [
            new Card("spades", "ace", 14),
            new Card("hearts", "king", 13),
            new Card("diamonds", "queen", 12)
        ];

        foreach ($cards as $card) {
            $hand->add($card);
        }

        $this->assertTrue($game->checkLimit($hand));
        $this->assertFalse($game->checkLimit($player));
    }

    /**
     * Verify containsAce works.
     */
    public function testContainsAce()
    {
        $game = new TwentyOneGame();
        $hand = new CardHand();
        $noAceHand = new CardHand();

        $cards = [
            new Card("spades", "ace", 14),
            new Card("hearts", "king", 13),
            new Card("diamonds", "queen", 12)
        ];

        $noAce = [
            new Card("spades", "two", 2),
            new Card("hearts", "king", 13),
            new Card("diamonds", "queen", 12)
        ];

        foreach ($cards as $card) {
            $hand->add($card);
        }

        foreach ($noAce as $card) {
            $noAceHand->add($card);
        }

        $this->assertTrue($game->containsAce($hand));
        $this->assertFalse($game->containsAce($noAceHand));
    }

    /**
     * Verify handleAce works.
     */
    public function testHandleAce()
    {
        $game = new TwentyOneGame();
        $hand = new CardHand();

        $cards = [
            new Card("spades", "ace", 14),
            new Card("hearts", "king", 13),
            new Card("diamonds", "queen", 12)
        ];

        foreach ($cards as $card) {
            $hand->add($card);
        }

        $sum = $hand->getSum();
        $this->assertEquals(39, $sum);

        $game->handleAce($hand);

        $sum = $hand->getSum();
        $this->assertEquals(26, $sum);
    }

    /**
     * Verify autoDraw works.
     */
    public function testAutoDraw()
    {
        $game = new TwentyOneGame();
        $player = $game->getPlayer();
        $deck = $game->getDeck();

        $this->assertCount(52, $deck->getDeck());

        $game->autoDraw($player, $deck);

        $this->assertNotCount(52, $deck->getDeck());
        $this->assertGreaterThan(17, $player->getSum());
    }

    /**
     * Verify compareHands works.
     */
    public function testCompareHands()
    {
        $game = new TwentyOneGame();
        $player = $game->getPlayer();
        $bank = $game->getBank();

        $res = $game->compareHands();
        $this->assertEquals("Bank", $res);

        $player->add(new Card("spades", "ace", 14));

        $res = $game->compareHands();
        $this->assertNotEquals("Bank", $res);
        $this->assertEquals("Player", $res);
    }
}
