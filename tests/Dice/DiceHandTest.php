<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceHand.
 */
class DiceHandTest extends TestCase
{
    /**
     * Construct object.
     */
    public function testCreateObject(): void
    {
        $hand = new DiceHand();

        $this->assertInstanceOf("\App\Dice\DiceHand", $hand);
    }

    /**
     * Verify add dice objects to hand works.
     */
    public function testAdd(): void
    {
        $hand = new DiceHand();
        $dice = new Dice();

        $this->assertEquals(0, $hand->getNumberDices());

        $hand->add($dice);

        $this->assertEquals(1, $hand->getNumberDices());
        $this->assertInstanceOf("\App\Dice\DiceHand", $hand);
    }

    /**
     * Verify hand roll works.
     */
    public function testRoll(): void
    {
        $hand = new DiceHand();
        $dice = new Dice();

        $this->assertEquals(0, $hand->getNumberDices());

        $hand->add($dice);

        $this->assertEquals(1, $hand->getNumberDices());
        $this->assertInstanceOf("\App\Dice\DiceHand", $hand);

        $hand->roll();

        $values = $hand->getValues();

        foreach ($values as $value) {
            $this->assertNotEquals(0, $value);
            $this->assertLessThan(7, $value);
            $this->assertGreaterThan(0, $value);
        }
    }

    /**
     * Verify getString works.
     */
    public function testGetString(): void
    {
        $hand = new DiceHand();
        $dice = new DiceGraphic();

        $this->assertEquals(0, $hand->getNumberDices());

        $hand->add($dice);

        $this->assertEquals(1, $hand->getNumberDices());
        $this->assertInstanceOf("\App\Dice\DiceHand", $hand);

        $hand->roll();

        $strings = $hand->getString();

        foreach ($strings as $str) {
            $outcomes = ['⚀', '⚁', '⚂', '⚃', '⚄', '⚅'];
            $this->assertContains($str, $outcomes);
        }
    }
}
