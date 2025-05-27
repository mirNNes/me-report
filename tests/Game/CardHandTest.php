<?php

namespace App\Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\Card;
use App\Game\CardHand;

class CardHandTest extends TestCase
{
    public function testAddAndGetCards(): void
    {
        $hand = new CardHand();
        $card1 = new Card('Hearts', 2);
        $card2 = new Card('Spades', 10);

        $hand->addCard($card1);
        $hand->addCard($card2);

        $this->assertCount(2, $hand->getCards());
        $this->assertSame($card1, $hand->getCards()[0]);
    }

    public function testGetScoreWithoutAces(): void
    {
        $hand = new CardHand();
        $hand->addCard(new Card('Hearts', 10));
        $hand->addCard(new Card('Spades', 9));

        $this->assertEquals(19, $hand->getScore());
    }

    public function testGetScoreWithAceUnder21(): void
    {
        $hand = new CardHand();
        $hand->addCard(new Card('Hearts', 1));
        $hand->addCard(new Card('Spades', 5));

        $this->assertEquals(19, $hand->getScore());
    }

    public function testGetScoreWithAceOver21(): void
    {
        $hand = new CardHand();
        $hand->addCard(new Card('Hearts', 1));
        $hand->addCard(new Card('Spades', 10));
        $hand->addCard(new Card('Clubs', 10));

        $this->assertEquals(21, $hand->getScore());
    }
}
