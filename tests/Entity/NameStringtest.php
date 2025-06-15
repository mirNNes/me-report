<?php

namespace App\Tests\Entity;

use App\Entity\NameString;
use PHPUnit\Framework\TestCase;

class NameStringTest extends TestCase
{
    public function testGetSetValue()
    {
        $nameString = new NameString();

        $this->assertNull($nameString->getValue());

        $nameString->setValue(42);
        $this->assertSame(42, $nameString->getValue());

        $this->assertInstanceOf(NameString::class, $nameString->setValue(100));
    }

    public function testGetIdInitiallyNull()
    {
        $nameString = new NameString();

        $this->assertNull($nameString->getId());
    }
}
