<?php

namespace App\Tests\Entity;

use App\Entity\Library;
use PHPUnit\Framework\TestCase;

class LibraryTest extends TestCase
{
    public function testSetterAndGetter(): void
    {
        $library = new Library();

        $library->setTitle('Test Book');
        $library->setWriter('John Test');
        $library->setIsbn('Test-111');
        $library->setImage('test.jpg');

        $this->assertEquals('Test Book', $library->getTitle());
        $this->assertEquals('John Test', $library->getWriter());
        $this->assertEquals('Test-111', $library->getIsbn());
        $this->assertEquals('test.jpg', $library->getImage());
    }

    public function testBookSetter(): void
    {
        $library = new Library();
        $library->bookSetter(
            title: 'Test Book',
            writer: 'Daud Test',
            isbn: 'Test-222',
            image: 'test2.jpg'
        );

        $this->assertEquals('Test Book', $library->getTitle());
        $this->assertEquals('Daud Test', $library->getWriter());
        $this->assertEquals('Test-222', $library->getIsbn());
        $this->assertEquals('test2.jpg', $library->getImage());
    }

    public function testBookSetterWithNull(): void
    {
        $library = new Library();
        $library->setTitle('Test Title');
        $library->setWriter('Test Writer');

        $library->bookSetter(writer: 'New Writer', image: 'new.jpg');

        $this->assertEquals('Test Title', $library->getTitle());
        $this->assertEquals('New Writer', $library->getWriter());
        $this->assertEquals(null, $library->getIsbn());
        $this->assertEquals('new.jpg', $library->getImage());
    }
}
