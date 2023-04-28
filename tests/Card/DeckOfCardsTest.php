<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * Construct object.
     */
    public function testCreateObjectNoArg()
    {
        $deck = new DeckOfCards();
        $typeOfCard = $deck->getDeck();
        $amount = $deck->getNumberCards();

        $this->assertInstanceOf("\App\Card\DeckOfCards", $deck);
        $this->assertContainsOnlyInstancesOf("\App\Card\CardGraphic", $typeOfCard);
        $this->assertEquals(52, $amount);
    }

    /**
     * Construct object with viable arg.
     */
    public function testCreateObjectWithArg()
    {
        $deck = new DeckOfCards("basic");
        $typeOfCard = $deck->getDeck();
        $amount = $deck->getNumberCards();

        $this->assertInstanceOf("\App\Card\DeckOfCards", $deck);
        $this->assertContainsOnlyInstancesOf("\App\Card\Card", $typeOfCard);
        $this->assertEquals(52, $amount);
    }

    /**
     * Verify exception thrown with a non-viable arg.
     */
    public function testCreateObjectException()
    {
        $this->expectException(\Exception::class);
        $deck = new DeckOfCards("testing");
        $this->assertNull($deck);
    }

    /**
     * Verify shuffle works.
     */
    public function testShuffle()
    {
        $deck = new DeckOfCards();
        $exp = new DeckOfCards();

        $deck->shuffle();

        $this->assertNotEquals($exp, $deck);
    }

    /**
     * Verify draw works.
     */
    public function testDraw()
    {
        $deck = new DeckOfCards();

        $this->assertCount(52, $deck->getDeck());

        $card = $deck->draw();

        $this->assertCount(51, $deck->getDeck());
        $this->assertInstanceOf("\App\Card\CardGraphic", $card);
    }

    /**
     * Verify draw exception raised on empty deck.
     */
    public function testDrawException()
    {
        $deck = new DeckOfCards();

        while (1 <= $deck->getNumberCards()) {
            $deck->draw();
        }

        $this->assertCount(0, $deck->getDeck());

        $this->expectException(\Exception::class);
        $deck->draw();
    }

    /**
     * Verify getString works.
     */
    public function testGetString()
    {
        $deck = new DeckOfCards();

        $stringArr = $deck->getString();
        $fiveFirst = array_slice($stringArr, 0, 5);
        $exp = [
            'card-container two-of-hearts',
            'card-container three-of-hearts',
            'card-container four-of-hearts',
            'card-container five-of-hearts',
            'card-container six-of-hearts'
        ];

        $this->assertEquals($exp, $fiveFirst);
    }

    /**
     * Verify getArray works.
     */
    public function testGetArray()
    {
        $deck = new DeckOfCards();

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

    /**
     * Verify getLowRes works. If card class is CardGraphic it will return array of empty strings.
     */
    public function testGetLowRes()
    {
        $deck = new DeckOfCards();

        $res = $deck->getLowRes();
        $fiveFirst = $fiveFirst = array_slice($res, 0, 5);
        $exp = ["", "", "", "", ""];

        $this->assertEquals($exp, $fiveFirst);
    }
}