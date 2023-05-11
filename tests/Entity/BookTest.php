<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for entity Book.
 */
class BookTest extends TestCase
{
    /**
     * Construct object.
     */
    public function testCreateObject(): void
    {
        $book = new Book();

        $this->assertInstanceOf("\App\Entity\Book", $book);
        $this->assertNull($book->getId());
    }

    /**
     * Verify set and get Title works.
     */
    public function testSetGetTitle(): void
    {
        $book = new Book();

        $this->assertInstanceOf("\App\Entity\Book", $book);

        $this->assertNull($book->getTitle());
        $book->setTitle("Pippi långstrump");
        $this->assertNotNull($book->getTitle());
        $this->assertEquals("Pippi långstrump", $book->getTitle());
    }

    /**
     * Verify set and get Isbn works.
     */
    public function testSetGetIsbn(): void
    {
        $book = new Book();

        $this->assertInstanceOf("\App\Entity\Book", $book);

        $this->assertNull($book->getIsbn());
        $book->setIsbn(1337);
        $this->assertNotNull($book->getIsbn());
        $this->assertEquals(1337, $book->getIsbn());
    }

    /**
     * Verify set and get Author works.
     */
    public function testSetGetAuthor(): void
    {
        $book = new Book();

        $this->assertInstanceOf("\App\Entity\Book", $book);

        $this->assertNull($book->getAuthor());
        $book->setAuthor("Pippi långstrump");
        $this->assertNotNull($book->getAuthor());
        $this->assertEquals("Pippi långstrump", $book->getAuthor());
    }

    /**
     * Verify set and get Image works.
     */
    public function testSetGetImage(): void
    {
        $book = new Book();

        $this->assertInstanceOf("\App\Entity\Book", $book);

        $this->assertNull($book->getImage());
        $book->setImage("public/img/thisisatest.png");
        $this->assertNotNull($book->getImage());
        $this->assertEquals("public/img/thisisatest.png", $book->getImage());
    }

}