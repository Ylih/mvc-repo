<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceGraphic.
 */
class DiceGraphicTest extends TestCase
{
    /**
     * Construct object.
     */
    public function testCreateObject(): void
    {
        $dice = new DiceGraphic();
        $this->assertInstanceOf("\App\Dice\Dice", $dice);
    }

    /**
     * Verify that getValue works.
     */
    public function testGetValue(): void
    {
        $dice = new DiceGraphic();

        $value = $dice->getValue();

        $this->assertInstanceOf("\App\Dice\Dice", $dice);
        $this->assertEquals(0, $value);
    }

    /**
     * Verify that roll works.
     */
    public function testRoll(): void
    {
        $dice = new DiceGraphic();

        $value = $dice->getValue();

        $this->assertInstanceOf("\App\Dice\Dice", $dice);
        $this->assertEquals(0, $value);

        $dice->roll();
        $value = $dice->getValue();
        $this->assertNotEquals(0, $value);
        $this->assertLessThan(7, $value);
        $this->assertGreaterThan(0, $value);
    }

    /**
     * Verify that getAsString works.
     */
    public function testGetAsString(): void
    {
        $dice = new DiceGraphic();

        $dice->roll();
        $visual = $dice->getAsString();
        $outcomes = ['⚀', '⚁', '⚂', '⚃', '⚄', '⚅'];
        $this->assertContains($visual, $outcomes);
    }

}