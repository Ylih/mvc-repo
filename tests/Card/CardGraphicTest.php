<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGraphic.
 */
class CardGraphicTest extends TestCase
{
    /**
     * Construct object.
     */
    public function testCreateObject(): void
    {
        $card = new CardGraphic("spades", "ace", 14);

        $resType = $card->getType();
        $expType = "spades";

        $resName = $card->getName();
        $expName = "ace";

        $resVal = $card->getValue();
        $expVal = 14;

        $this->assertInstanceOf("\App\Card\Card", $card);
        $this->assertEquals($expType, $resType);
        $this->assertEquals($expName, $resName);
        $this->assertEquals($expVal, $resVal);
    }

    /**
     * Verify that getAsString works.
     */
    public function testGetAsString(): void
    {
        $card = new CardGraphic("spades", "ace", 14);
        $res = $card->getAsString();
        $exp = "card-container ace-of-spades";
        $this->assertEquals($exp, $res);
    }

    /**
     * Verify that getAsLowRes works.
     */
    public function testGetAsLowRes(): void
    {
        $card = new CardGraphic("spades", "ace", 14);
        $res = $card->getAsLowRes();
        $exp = "";
        $this->assertIsString($res);
        $this->assertEquals($exp, $res);
    }
}