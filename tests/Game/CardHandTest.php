<?php

namespace App\Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\CardHand;
use App\Game\Card;

class CardHandTest extends TestCase
{
    private function createCardMock(string $name, int $numericValue): Card
    {
        $card = $this->createMock(Card::class);
        $card->method('getValue')->willReturn($name);
        $card->method('getNumericValue')->willReturn($numericValue);
        return $card;
    }

    public function testAddAndGetCards(): void
    {
        $hand = new CardHand();
        $card1 = $this->createCardMock('Two', 2);
        $card2 = $this->createCardMock('Ten', 10);
        $hand->addCard($card1);
        $hand->addCard($card2);
        $cards = $hand->getCards();
        $this->assertCount(2, $cards);
        $this->assertSame($card1, $cards[0]);
        $this->assertSame($card2, $cards[1]);
    }

    public function testScoreCalculations(): void
    {
        $hand1 = new CardHand();
        $hand1->addCard($this->createCardMock('Ten', 10));
        $hand1->addCard($this->createCardMock('Seven', 7));
        $this->assertEquals(17, $hand1->getScore());

        $hand2 = new CardHand();
        $hand2->addCard($this->createCardMock('Ace', 1));
        $hand2->addCard($this->createCardMock('Five', 5));
        $this->assertEquals(16, $hand2->getScore());

        $hand3 = new CardHand();
        $hand3->addCard($this->createCardMock('Ace', 1));
        $hand3->addCard($this->createCardMock('Ten', 10));
        $hand3->addCard($this->createCardMock('King', 10));
        $this->assertEquals(21, $hand3->getScore());

        $hand4 = new CardHand();
        $hand4->addCard($this->createCardMock('Ace', 1));
        $hand4->addCard($this->createCardMock('Ace', 1));
        $hand4->addCard($this->createCardMock('Nine', 9));
        $this->assertEquals(21, $hand4->getScore());
    }

    public function testCanSplit(): void
    {
        $hand1 = new CardHand();
        $hand1->addCard($this->createCardMock('Eight', 8));
        $hand1->addCard($this->createCardMock('Eight', 8));
        $this->assertTrue($hand1->canSplit());

        $hand2 = new CardHand();
        $hand2->addCard($this->createCardMock('Eight', 8));
        $hand2->addCard($this->createCardMock('Nine', 9));
        $this->assertFalse($hand2->canSplit());
    }

    public function testBustAndDone(): void
    {
        $hand1 = new CardHand();
        $hand1->addCard($this->createCardMock('King', 10));
        $hand1->addCard($this->createCardMock('Queen', 10));
        $hand1->addCard($this->createCardMock('Two', 2));
        $this->assertTrue($hand1->isBust());
        $this->assertTrue($hand1->isDone());

        $hand2 = new CardHand();
        $hand2->addCard($this->createCardMock('Ten', 10));
        $hand2->addCard($this->createCardMock('Seven', 7));
        $this->assertFalse($hand2->isBust());
        $this->assertFalse($hand2->isDone());

        $hand3 = new CardHand();
        $hand3->setStanding(true);
        $this->assertTrue($hand3->isDone());
    }
}
