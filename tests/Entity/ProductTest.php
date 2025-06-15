<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $product = new Product();


        $this->assertNull($product->getName());
        $product->setName('Test Product');
        $this->assertSame('Test Product', $product->getName());

        $this->assertNull($product->getValue());
        $product->setValue(123);
        $this->assertSame(123, $product->getValue());

        $this->assertNull($product->getId());
    }
}
