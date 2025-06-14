<?php

namespace App\Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\Card;

class CardTest extends TestCase
{
    public function testCardInitialization(): void
    {
        $card = new Card('Hearts', 1);
        $this->assertEquals('Hearts', $card->getSuit());
        $this->assertEquals('A', $card->getValue());
    }

    public function testNumericCard(): void
    {
        $card = new Card('Spades', 7);
        $this->assertEquals('7', $card->getValue());
    }

    public function testFaceCards(): void
    {
        $this->assertEquals('J', (new Card('Clubs', 11))->getValue());
        $this->assertEquals('Q', (new Card('Diamonds', 12))->getValue());
        $this->assertEquals('K', (new Card('Hearts', 13))->getValue());
    }

    public function testGetAsString(): void
    {
        $card = new Card('Hearts', 1);
        $this->assertEquals('[A♥]', $card->getAsString());
    }

    public function testSuitSymbol(): void
    {
        $symbols = [
            'hearts' => '♥',
            'diamonds' => '♦',
            'clubs' => '♣',
            'spades' => '♠',
            'unknown' => '?'
        ];

        foreach ($symbols as $suit => $expected) {
            $card = new Card($suit, 5);
            $this->assertEquals($expected, $card->getSuitSymbol());
        }
    }
}
