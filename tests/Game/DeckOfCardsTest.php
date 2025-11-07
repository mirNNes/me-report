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
        $this->assertSame(52, $deck->count());
    }


    public function testDrawMultipleReturnsCorrectNumberOfCards(): void
    {
        $deck = new DeckOfCards();

        $drawnCards = $deck->draw(5);

        $this->assertCount(5, $drawnCards);
        $this->assertSame(47, $deck->count());

        foreach ($drawnCards as $card) {
            $this->assertInstanceOf(Card::class, $card);
        }
    }

    public function testDrawMoreThanDeckReturnsAllRemaining(): void
    {
        $deck = new DeckOfCards();
        $drawnCards = $deck->draw(60);

        $this->assertCount(52, $drawnCards);
        $this->assertSame(0, $deck->count());
    }

    public function testShuffleChangesCardOrder(): void
    {
        $deck1 = new DeckOfCards();
        $deck2 = new DeckOfCards();

        $deck1->shuffle();

        $deck1Strings = array_map(fn(Card $card) => $card->getValue() . $card->getSuit(), $deck1->draw(52));
        $deck2Strings = array_map(fn(Card $card) => $card->getValue() . $card->getSuit(), $deck2->draw(52));

        $this->assertNotEquals($deck2Strings, $deck1Strings, "Shuffle should change card order");
    }
}
