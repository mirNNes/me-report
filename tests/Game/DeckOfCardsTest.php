<?php

namespace App\Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\DeckOfCards;
use App\Game\Card;

class DeckOfCardsTest extends TestCase
{
    public function testDeckHas52CardsAfterInitialization(): void
    {
        $deck = new DeckOfCards();
        $this->assertCount(52, $deck->getDeck());
        $this->assertSame(52, $deck->count());
    }

    public function testDrawReturnsCorrectNumberOfCards(): void
    {
        $deck = new DeckOfCards();
        $drawn = $deck->draw(5);
        $this->assertCount(5, $drawn);
        $this->assertCount(47, $deck->getDeck());
        $this->assertSame(47, $deck->count());

        foreach ($drawn as $card) {
            $this->assertInstanceOf(Card::class, $card);
        }
    }

    public function testDrawMoreThanDeckReturnsAllRemaining(): void
    {
        $deck = new DeckOfCards();
        $drawn = $deck->draw(60);
        $this->assertCount(52, $drawn);
        $this->assertEmpty($deck->getDeck());
        $this->assertSame(0, $deck->count());
    }

    public function testShuffleChangesCardOrder(): void
    {
        $deck1 = new DeckOfCards();
        $deck2 = new DeckOfCards();

        $deck1->shuffle();

        $this->assertNotEquals(
            array_map(fn ($c) => $c->getAsString(), $deck1->getDeck()),
            array_map(fn ($c) => $c->getAsString(), $deck2->getDeck()),
        );
    }
}
