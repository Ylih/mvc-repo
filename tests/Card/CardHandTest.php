<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
{
    /**
     * Construct object.
     */
    public function testCreateObject(): void
    {
        $hand = new CardHand();
        $handArr = $hand->getHand();

        $this->assertInstanceOf("\App\Card\CardHand", $hand);
        $this->assertIsArray($handArr);
        $this->assertEmpty($handArr);
    }

    /**
     * Verify add card objects to hand works.
     */
    public function testAdd(): void
    {
        $hand = new CardHand();
        $card = new Card("spades", "ace", 14);
        $handArr = $hand->getHand();

        $this->assertIsArray($handArr);
        $this->assertEmpty($handArr);
        $this->assertEquals(0, $hand->countCards());

        $hand->add($card);
        $handArr = $hand->getHand();

        $this->assertNotEmpty($handArr);
        $this->assertEquals(1, $hand->countCards());

        $this->assertInstanceOf("\App\Card\CardHand", $hand);
    }

    /**
     * Verify getString works.
     */
    public function testGetString(): void
    {
        $hand = new CardHand();
        $cards = [
            new Card("spades", "ace", 14),
            new Card("hearts", "king", 13),
            new Card("diamonds", "queen", 12)
        ];

        foreach ($cards as $card) {
            $hand->add($card);
        }

        $stringArr = $hand->getString();
        $exp = [
            "alt-container ace-spades",
            "alt-container king-hearts",
            "alt-container queen-diamonds"
        ];

        $this->assertEquals($exp, $stringArr);
    }

    /**
     * Verify getSum works.
     */
    public function testGetSum(): void
    {
        $hand = new CardHand();
        $cards = [
            new Card("spades", "ace", 14),
            new Card("hearts", "king", 13),
            new Card("diamonds", "queen", 12)
        ];

        foreach ($cards as $card) {
            $hand->add($card);
        }

        $sumArr = $hand->getSum();
        $exp = 39;

        $this->assertEquals($exp, $sumArr);
    }

    /**
     * Verify getArray works.
     */
    public function testGetArray(): void
    {
        $hand = new CardHand();
        $cards = [
            new Card("spades", "ace", 14),
            new Card("hearts", "king", 13),
            new Card("diamonds", "queen", 12)
        ];

        foreach ($cards as $card) {
            $hand->add($card);
        }

        $res = $hand->getArray();
        $exp = [
            [
            "type" => "spades",
            "name" => "ace",
            "value" => 14,
            ],
            [
                "type" => "hearts",
                "name" => "king",
                "value" => 13,
            ],
            [
                "type" => "diamonds",
                "name" => "queen",
                "value" => 12,
            ],
        ];

        $this->assertEquals($exp, $res);
    }

    /**
     * Verify getNames works.
     */
    public function testGetNames(): void
    {
        $hand = new CardHand();
        $cards = [
            new Card("spades", "ace", 14),
            new Card("hearts", "king", 13),
            new Card("diamonds", "queen", 12)
        ];

        foreach ($cards as $card) {
            $hand->add($card);
        }

        $res = $hand->getNames();
        $exp = ["ace", "king", "queen"];

        $this->assertEquals($exp, $res);
    }

    /**
     * Verify getLowRes works.
     */
    public function testGetLowRes(): void
    {
        $hand = new CardHand();
        $cards = [
            new Card("spades", "ace", 14),
            new Card("hearts", "king", 13),
            new Card("diamonds", "queen", 12)
        ];

        foreach ($cards as $card) {
            $hand->add($card);
        }

        $res = $hand->getLowRes();
        $exp = ["A♠", "K♥", "Q♦"];

        $this->assertEquals($exp, $res);
    }
}
