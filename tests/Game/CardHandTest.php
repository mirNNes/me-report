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
        $card->method('getName')->willReturn($name);
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

    public function testGetScoreWithoutAces(): void
    {
        $hand = new CardHand();
        $hand->addCard($this->createCardMock('Ten', 10));
        $hand->addCard($this->createCardMock('Seven', 7));

        $this->assertEquals(17, $hand->getScore());
    }

    public function testGetScoreWithAceAs11(): void
    {
        $hand = new CardHand();
        $hand->addCard($this->createCardMock('Ace', 1)); // Ace
        $hand->addCard($this->createCardMock('Five', 5));

        // Ace should count as 11 + 5 = 16
        $this->assertEquals(16, $hand->getScore());
    }

    public function testGetScoreWithAceAs1(): void
    {
        $hand = new CardHand();
        $hand->addCard($this->createCardMock('Ace', 1));
        $hand->addCard($this->createCardMock('Ten', 10));
        $hand->addCard($this->createCardMock('King', 10));

        // Ace should count as 1 to avoid bust: 1 + 10 + 10 = 21
        $this->assertEquals(21, $hand->getScore());
    }

    public function testMultipleAcesHandledCorrectly(): void
    {
        $hand = new CardHand();
        $hand->addCard($this->createCardMock('Ace', 1));
        $hand->addCard($this->createCardMock('Ace', 1));
        $hand->addCard($this->createCardMock('Nine', 9));

        // Optimal count: 11 (first ace) + 1 (second ace) + 9 = 21
        $this->assertEquals(21, $hand->getScore());
    }

    public function testCanSplitReturnsTrueWhenCardsHaveSameValue(): void
    {
        $hand = new CardHand();
        $hand->addCard($this->createCardMock('Eight', 8));
        $hand->addCard($this->createCardMock('Eight', 8));

        $this->assertTrue($hand->canSplit());
    }

    public function testCanSplitReturnsFalseWhenCardsHaveDifferentValue(): void
    {
        $hand = new CardHand();
        $hand->addCard($this->createCardMock('Eight', 8));
        $hand->addCard($this->createCardMock('Nine', 9));

        $this->assertFalse($hand->canSplit());
    }

    public function testIsBustReturnsTrueWhenScoreOver21(): void
    {
        $hand = new CardHand();
        $hand->addCard($this->createCardMock('King', 10));
        $hand->addCard($this->createCardMock('Queen', 10));
        $hand->addCard($this->createCardMock('Two', 2));

        $this->assertTrue($hand->isBust());
    }

    public function testIsBustReturnsFalseWhenScore21OrLess(): void
    {
        $hand = new CardHand();
        $hand->addCard($this->createCardMock('Ten', 10));
        $hand->addCard($this->createCardMock('Seven', 7));

        $this->assertFalse($hand->isBust());
    }

    public function testIsDoneReturnsTrueWhenStanding(): void
    {
        $hand = new CardHand();
        $hand->setStanding(true);

        $this->assertTrue($hand->isDone());
    }

    public function testIsDoneReturnsTrueWhenBust(): void
    {
        $hand = new CardHand();
        $hand->addCard($this->createCardMock('King', 10));
        $hand->addCard($this->createCardMock('Queen', 10));
        $hand->addCard($this->createCardMock('Two', 2));

        $this->assertTrue($hand->isDone());
    }

    public function testIsDoneReturnsFalseWhenNotBustOrStanding(): void
    {
        $hand = new CardHand();
        $hand->addCard($this->createCardMock('Ten', 10));
        $hand->addCard($this->createCardMock('Seven', 7));

        $this->assertFalse($hand->isDone());
    }
}
