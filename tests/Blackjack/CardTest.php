<?php

namespace App\Blackjack;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Construct object.
     */
    public function testCreateObject(): void
    {
        $card = new Card("spades", "ace", 14);

        $resType = $card->getType();
        $expType = "spades";

        $resName = $card->getName();
        $expName = "ace";

        $resVal = $card->getValue();
        $expVal = 14;

        $this->assertInstanceOf("\App\Blackjack\Card", $card);
        $this->assertEquals($expType, $resType);
        $this->assertEquals($expName, $resName);
        $this->assertEquals($expVal, $resVal);
    }

    /**
     * Verify that setValue works.
     */
    public function testSetValue(): void
    {
        $card = new Card("spades", "ace", 14);

        $value = $card->getValue();

        $this->assertEquals(14, $value);

        $card->setValue(1);

        $newValue = $card->getValue();

        $this->assertEquals(1, $newValue);
    }

    /**
     * Verify that getAsString works.
     */
    public function testGetAsString(): void
    {
        $card = new Card("spades", "ace", 14);
        $res = $card->getAsString();
        $exp = "ace-of-spades";
        $this->assertEquals($exp, $res);
    }

    /**
     * Verify that getAsArray works.
     */
    public function testGetAsArray(): void
    {
        $card = new Card("spades", "ace", 14);
        $res = $card->getAsArray();
        $exp = ["type" => "spades", "name" => "ace", "value" => 14];
        $this->assertIsArray($res);
        $this->assertEquals($exp, $res);
    }
}