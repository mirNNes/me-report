<?php

namespace App\Tests\Game;

use App\Game\Card;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{
    public function testGetSuitAndValue(): void
    {
        $card = new Card("hearts", 1);
        $this->assertEquals("hearts", $card->getSuit());
        $this->assertEquals(1, $card->getNumericValue());
        $this->assertEquals("A", $card->getValue());
    }

    public function testGetSuitSymbol(): void
    {
        $card = new Card("spades", 13);
        $this->assertEquals("♠", $card->getSuitSymbol());

        $card = new Card("clubs", 13);
        $this->assertEquals("♣", $card->getSuitSymbol());

        $card = new Card("unknown", 13);
        $this->assertEquals("?", $card->getSuitSymbol());
    }

    public function testGetAsString(): void
    {
        $card = new Card("diamonds", 12);
        $expected = "[" . $card->getValue() . $card->getSuitSymbol() . "]";
        $this->assertEquals($expected, "[" . $card->getValue() . $card->getSuitSymbol() . "]");
    }
}
