<?php

namespace App\Tests\Form;

use App\Entity\Book;
use App\Form\BookForm;
use Symfony\Component\Form\Test\TypeTestCase;

class BookFormTest extends TypeTestCase
{
    public function testBuildForm(): void
    {
        $formData = [
            'title' => 'Test Title',
            'isbn' => '1234567890',
            'author' => 'Test Author',
            'image' => 'test.jpg',
        ];

        $model = new Book();

        $form = $this->factory->create(BookForm::class, $model);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals('Test Title', $model->getTitle());
        $this->assertEquals('1234567890', $model->getIsbn());
        $this->assertEquals('Test Author', $model->getAuthor());
        $this->assertEquals('test.jpg', $model->getImage());

        $this->assertArrayHasKey('title', $form->createView()->children);
        $this->assertArrayHasKey('isbn', $form->createView()->children);
        $this->assertArrayHasKey('author', $form->createView()->children);
        $this->assertArrayHasKey('image', $form->createView()->children);
    }
}
