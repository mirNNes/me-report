<?php

namespace App\Tests\BlackJack;

use PHPUnit\Framework\TestCase;
use App\BlackJack\BlackJack;
use App\Game\Card;
use ReflectionClass;

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

        $playerHand = $this->getPrivateProperty($game, 'playerHands')[0];
        $this->setPrivateProperty($playerHand, 'cards', []);

        $playerHand->addCard(new Card('hearts', 10));
        $playerHand->addCard(new Card('spades', 10));
        $playerHand->addCard(new Card('clubs', 5));

        $this->assertGreaterThan(21, $playerHand->getScore());
    }

    public function testRoundResultIncludesWin(): void
    {
        $game = new BlackJack();
        $game->startGame(1);

        $playerHand = $this->getPrivateProperty($game, 'playerHands')[0];
        $dealerHand = $this->getPrivateProperty($game, 'dealerHand');

        $this->setPrivateProperty($playerHand, 'cards', []);
        $this->setPrivateProperty($dealerHand, 'cards', []);

        $playerHand->addCard(new Card('hearts', 14));
        $playerHand->addCard(new Card('spades', 13));

        $dealerHand->addCard(new Card('clubs', 10));
        $dealerHand->addCard(new Card('diamonds', 8));

        $game->playerStand(0);

        $this->assertEquals('Win', $game->getRoundResult()[0]);
    }

    public function testInvalidStartGameThrowsException(): void
    {
        $this->expectException(\TypeError::class);
        $game = new BlackJack();
        $game->startGame(null);
    }

    private function getPrivateProperty(object $object, string $propertyName): mixed
    {
        $reflection = new ReflectionClass($object::class);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    private function setPrivateProperty(object $object, string $propertyName, mixed $value): void
    {
        $reflection = new ReflectionClass($object::class);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}
