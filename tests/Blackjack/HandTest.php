<?php

namespace App\Blackjack;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Hand.
 */
class HandTest extends TestCase
{
    /**
     * Construct object.
     */
    public function testCreateObject(): void
    {
        $hand = new Hand();
        $stake = $hand->getStake();
        $status = $hand->getStatus();
        $handArr = $hand->getHand();

        $this->assertInstanceOf("\App\Blackjack\Hand", $hand);
        $this->assertIsArray($handArr);
        $this->assertEmpty($handArr);
        $this->assertEquals($stake, 0);
        $this->assertEquals($status, "");
        $this->assertFalse($hand->isPlayed());
    }

    /**
     * Verify add card objects to hand works.
     */
    public function testAdd(): void
    {
        $hand = new Hand();
        $card = new Card("spades", "ace", 11);
        $handArr = $hand->getHand();

        $this->assertIsArray($handArr);
        $this->assertEmpty($handArr);
        $this->assertEquals(0, $hand->countCards());

        $hand->add($card);
        $handArr = $hand->getHand();

        $this->assertNotEmpty($handArr);
        $this->assertContains($card, $handArr);
        $this->assertEquals(1, $hand->countCards());

        $this->assertInstanceOf("\App\Blackjack\Hand", $hand);
    }

    /**
     * Verify set methods for Hand attribute works.
     */
    public function testSet(): void
    {
        $hand = new Hand();

        $this->assertInstanceOf("\App\Blackjack\Hand", $hand);
        $this->assertFalse($hand->isPlayed());
        $this->assertEquals($hand->getStake(), 0);
        $this->assertEquals($hand->getStatus(), "");

        $hand->setPlayed();
        $hand->setStake(123);
        $hand->setStatus("Test");

        $this->assertTrue($hand->isPlayed());
        $this->assertEquals($hand->getStake(), 123);
        $this->assertEquals($hand->getStatus(), "Test");
    }

    /**
     * Verify getString works.
     */
    public function testGetString(): void
    {
        $hand = new Hand();
        $cards = [
            new Card("spades", "ace", 11),
            new Card("hearts", "king", 10),
            new Card("diamonds", "queen", 10)
        ];

        foreach ($cards as $card) {
            $hand->add($card);
        }

        $stringArr = $hand->getString();
        $exp = [
            "ace-of-spades",
            "king-of-hearts",
            "queen-of-diamonds"
        ];

        $this->assertEquals($exp, $stringArr);
    }

    /**
     * Verify getSum works.
     */
    public function testGetSum(): void
    {
        $hand = new Hand();
        $cards = [
            new Card("spades", "ace", 11),
            new Card("hearts", "king", 10),
            new Card("diamonds", "queen", 10)
        ];

        foreach ($cards as $card) {
            $hand->add($card);
        }

        $sumArr = $hand->getSum();
        $exp = 31;

        $this->assertEquals($exp, $sumArr);
    }

    /**
     * Verify getArray works.
     */
    public function testGetArray(): void
    {
        $hand = new Hand();
        $cards = [
            new Card("spades", "ace", 11),
            new Card("hearts", "king", 10),
            new Card("diamonds", "queen", 10)
        ];

        foreach ($cards as $card) {
            $hand->add($card);
        }

        $res = $hand->getArray();
        $exp = [
            [
            "type" => "spades",
            "name" => "ace",
            "value" => 11,
            ],
            [
                "type" => "hearts",
                "name" => "king",
                "value" => 10,
            ],
            [
                "type" => "diamonds",
                "name" => "queen",
                "value" => 10,
            ],
        ];

        $this->assertEquals($exp, $res);
    }

    /**
     * Verify getNames works.
     */
    public function testGetNames(): void
    {
        $hand = new Hand();
        $cards = [
            new Card("spades", "ace", 11),
            new Card("hearts", "king", 10),
            new Card("diamonds", "queen", 10)
        ];

        foreach ($cards as $card) {
            $hand->add($card);
        }

        $res = $hand->getNames();
        $exp = ["ace", "king", "queen"];

        $this->assertEquals($exp, $res);
    }

    /**
     * Verify getAssociative works.
     */
    public function testGetAssociative(): void
    {
        $hand = new Hand();
        $cards = [
            new Card("spades", "ace", 11),
            new Card("hearts", "king", 10),
            new Card("diamonds", "queen", 10)
        ];

        foreach ($cards as $card) {
            $hand->add($card);
        }

        $res = $hand->getAssociative();
        $exp = [
            "values" => [
                "ace-of-spades",
                "king-of-hearts",
                "queen-of-diamonds",
            ],
            "stake" => 0,
            "sum" => 31,
            "status" => "",
        ];

        $this->assertEquals($exp, $res);
    }

    /**
     * Verify containsAce works.
     */
    public function testContainsAce(): void
    {
        $hand = new Hand();
        $cards = [
            new Card("spades", "ten", 10),
            new Card("hearts", "king", 10),
            new Card("diamonds", "queen", 10)
        ];

        foreach ($cards as $card) {
            $hand->add($card);
        }

        $res = $hand->containsAce();
        $exp = false;

        $this->assertEquals($exp, $res);

        $ace = new Card("spades", "ace", 11);
        $hand->add($ace);

        $res = $hand->containsAce();
        $exp = true;

        $this->assertEquals($exp, $res);
    }

    /**
     * Verify handleAce works.
     */
    public function testHandleAce(): void
    {
        $hand = new Hand();
        $cards = [
            new Card("spades", "ace", 11),
            new Card("hearts", "king", 10),
            new Card("diamonds", "queen", 10)
        ];

        foreach ($cards as $card) {
            $hand->add($card);
        }

        $sum = $hand->getSum();

        $this->assertEquals($sum, 31);

        $hand->handleAce();
        $sum = $hand->getSum();

        $this->assertEquals($sum, 21);
    }

}
