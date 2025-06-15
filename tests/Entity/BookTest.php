<?php

namespace App\Tests\Entity;

use App\Entity\Book;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $book = new Book();

        $this->assertNull($book->getTitle());
        $this->assertNull($book->getIsbn());
        $this->assertNull($book->getAuthor());
        $this->assertNull($book->getImage());
        $this->assertNull($book->getId());

        $book->setTitle('Bortom horisonten');
        $this->assertSame('Bortom horisonten', $book->getTitle());

        $book->setIsbn('9781234567890');
        $this->assertSame('9781234567890', $book->getIsbn());

        $book->setAuthor('Mirnes Mrso');
        $this->assertSame('Mirnes Mrso', $book->getAuthor());

        $book->setImage('book-cover.jpg');
        $this->assertSame('book-cover.jpg', $book->getImage());
    }
}
