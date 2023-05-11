<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for entity Product.
 */
class ProductTest extends TestCase
{
    /**
     * Construct object.
     */
    public function testCreateObject(): void
    {
        $prod = new Product();

        $this->assertInstanceOf("\App\Entity\Product", $prod);
        $this->assertNull($prod->getId());
    }

    /**
     * Verify set and get Name works.
     */
    public function testSetGetName(): void
    {
        $prod = new Product();

        $this->assertInstanceOf("\App\Entity\Product", $prod);

        $this->assertNull($prod->getName());
        $prod->setName("Pippi långstrump");
        $this->assertNotNull($prod->getName());
        $this->assertEquals("Pippi långstrump", $prod->getName());
    }

    /**
     * Verify set and get Value works.
     */
    public function testSetGetValue(): void
    {
        $prod = new Product();

        $this->assertInstanceOf("\App\Entity\Product", $prod);

        $this->assertNull($prod->getValue());
        $prod->setValue(1337);
        $this->assertNotNull($prod->getValue());
        $this->assertEquals(1337, $prod->getValue());
    }
}