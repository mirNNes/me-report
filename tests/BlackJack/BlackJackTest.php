<?php

namespace App\Tests\BlackJack;

use PHPUnit\Framework\TestCase;
use App\BlackJack\BlackJack;
use App\Game\Card;

class BlackJackTest extends TestCase
{
    public function testGameStartsWithOnePlayerHand(): void
    {
        $game = new BlackJack();
        $game->startGame(1);

        $state = $game->getGameState();

        $this->assertTrue($state['roundActive']);
        $this->assertCount(1, $state['playerHands']);
    }

    public function testPlayerBustsAfterHit(): void
    {
        $game = new BlackJack();
        $game->startGame(1);

        $playerHands = $this->getPrivateProperty($game, 'playerHands');
        $playerHand = $playerHands[0];

        $this->setPrivateProperty($playerHand, 'cards', []);

        $playerHand->addCard(new Card('hearts', 10));
        $playerHand->addCard(new Card('spades', 10));
        $playerHand->addCard(new Card('clubs', 5)); // Total = 25

        $this->assertGreaterThan(21, $playerHand->getScore());
    }

    public function testRoundResultIncludesWin(): void
    {
        $game = new BlackJack();
        $game->startGame(1);

        $playerHands = $this->getPrivateProperty($game, 'playerHands');
        $playerHand = $playerHands[0];

        $dealerHand = $this->getPrivateProperty($game, 'dealerHand');

        $this->setPrivateProperty($playerHand, 'cards', []);
        $this->setPrivateProperty($dealerHand, 'cards', []);

        $playerHand->addCard(new Card('hearts', 14)); // Ess (A)
        $playerHand->addCard(new Card('spades', 13)); // Kung (K)

        $dealerHand->addCard(new Card('clubs', 10));
        $dealerHand->addCard(new Card('diamonds', 8)); // Totalt 18

        $game->playerStand(0);

        $result = $game->getRoundResult();
        $this->assertEquals('Vinst', $result[0]);
    }

    public function testInvalidStartGameThrowsException(): void
    {
        $this->expectException(\TypeError::class);

        $game = new BlackJack();
        $game->startGame(null); // null till int → kastar TypeError
    }

    // === Hjälpmetoder ===

    private function getPrivateProperty(object $object, string $propertyName): mixed
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    private function setPrivateProperty(object $object, string $propertyName, mixed $value): void
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}
