<?php

namespace App\Blackjack;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Deck.
 */
class DeckTest extends TestCase
{
    /**
     * Construct object.
     */
    public function testCreateDeck(): void
    {
        $deck = new Deck();
        $deckArr = $deck->getDeck();

        $this->assertInstanceOf("\App\Blackjack\Deck", $deck);
        $this->assertEmpty($deckArr);
    }

    /**
     * Verify createNormalDeck method works.
     */
    public function testCreateNormalDeck(): void
    {
        $deck = new Deck();
        $this->assertInstanceOf("\App\Blackjack\Deck", $deck);
        $this->assertEmpty($deck->getDeck());

        $deck->createNormalDeck();

        $this->assertContainsOnlyInstancesOf("\App\Blackjack\Card", $deck->getDeck());
        $this->assertEquals(52, $deck->getNumberCards());
    }

    /**
     * Verify shuffle works.
     */
    public function testShuffle(): void
    {
        $deck = new Deck();
        $deck->createNormalDeck();

        $exp = new Deck();
        $exp->createNormalDeck();

        $deck->shuffle();

        $this->assertNotEquals($exp->getDeck(), $deck->getDeck());
    }

    /**
     * Verify draw works.
     */
    public function testDraw(): void
    {
        $deck = new Deck();
        $deck->createNormalDeck();

        $this->assertCount(52, $deck->getDeck());

        $card = $deck->draw();

        $this->assertCount(51, $deck->getDeck());
        $this->assertInstanceOf("\App\Blackjack\Card", $card);
    }

    /**
     * Verify draw works.
     */
    public function testDrawMultiple(): void
    {
        $deck = new Deck();
        $deck->createNormalDeck();

        $this->assertCount(52, $deck->getDeck());

        $cards = $deck->drawMultiple(7);

        $this->assertCount(45, $deck->getDeck());
        $this->assertContainsOnlyInstancesOf("\App\Blackjack\Card", $cards);
        $this->assertCount(7, $cards);
    }

    /**
     * Verify draw exception raised on empty deck.
     */
    public function testDrawException(): void
    {
        $deck = new Deck();
        $deck->createNormalDeck();

        while (1 <= $deck->getNumberCards()) {
            $deck->draw();
        }

        $this->assertCount(0, $deck->getDeck());

        $this->expectException(\Exception::class);
        $deck->draw();
    }

    /**
     * Verify drawMultiple exception raised on empty deck.
     */
    public function testDrawMultipleException(): void
    {
        $deck = new Deck();
        $deck->createNormalDeck();

        $this->expectException(\Exception::class);
        $deck->drawMultiple(53);
    }

    /**
     * Verify getString works.
     */
    public function testGetString(): void
    {
        $deck = new Deck();
        $deck->createNormalDeck();

        $stringArr = $deck->getString();
        $fiveFirst = array_slice($stringArr, 0, 5);
        $exp = [
            'two-of-hearts',
            'three-of-hearts',
            'four-of-hearts',
            'five-of-hearts',
            'six-of-hearts'
        ];

        $this->assertEquals($exp, $fiveFirst);
    }

    /**
     * Verify getArray works.
     */
    public function testGetArray(): void
    {
        $deck = new Deck();
        $deck->createNormalDeck();

        $res = $deck->getArray();
        $fiveFirst = array_slice($res, 0, 5);
        $exp = [
            [
            "type" => "hearts",
            "name" => "two",
            "value" => 2,
            ],
            [
                "type" => "hearts",
                "name" => "three",
                "value" => 3,
            ],
            [
                "type" => "hearts",
                "name" => "four",
                "value" => 4,
            ],
            [
                "type" => "hearts",
                "name" => "five",
                "value" => 5,
            ],
            [
                "type" => "hearts",
                "name" => "six",
                "value" => 6,
            ],
        ];

        $this->assertEquals($exp, $fiveFirst);
    }
}