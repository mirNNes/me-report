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

        // Player Score: 27
        $playerHand->addCard(new Card('hearts', 14));
        $playerHand->addCard(new Card('spades', 13));

        // Dealer Score: 18
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
    
    public function testStartGameWithMaxHands(): void
    {
        $game = new BlackJack();
        $game->startGame(3);
        $state = $game->getGameState();
        $this->assertCount(3, $state['playerHands']);
    }
    
    public function testInvalidStartGameLowLimitThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $game = new BlackJack();
        $game->startGame(0);
    }
    
    public function testInvalidStartGameHighLimitThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $game = new BlackJack();
        $game->startGame(4);
    }
    
    public function testPlayerActionsDoNothingIfRoundIsInactiveOrIndexInvalid(): void
    {
        $game = new BlackJack();
        $game->playerStand(0);
        $game->playerHit(0);
        $this->assertEmpty($game->getRoundResult()); 

        $game->startGame(1);
        $stateBefore = $this->getPrivateProperty($game, 'playerHands')[0]->isDone();
        
        $game->playerStand(99);
        $game->playerHit(99);
        
        $this->assertEquals($stateBefore, $this->getPrivateProperty($game, 'playerHands')[0]->isDone()); 
    }

    // KORRIGERAT (Fel 1): Ökade spelarens poäng till 20. Dealern bustar med 25 poäng.
    public function testRoundResultPlayerWinsDueToDealerBust(): void
    {
        $game = new BlackJack();
        $game->startGame(1);

        $playerHand = $this->getPrivateProperty($game, 'playerHands')[0];
        $dealerHand = $this->getPrivateProperty($game, 'dealerHand');
        
        $this->setPrivateProperty($playerHand, 'cards', []);
        $playerHand->addCard(new Card('hearts', 10));
        $playerHand->addCard(new Card('spades', 10)); // Spelare: 20
        
        $this->setPrivateProperty($dealerHand, 'cards', []);
        $dealerHand->addCard(new Card('clubs', 10));
        $dealerHand->addCard(new Card('diamonds', 10));
        $dealerHand->addCard(new Card('clubs', 5)); // Dealer: 25 (BUST)
        
        $game->playerStand(0);
        
        $this->assertEquals('Win', $game->getRoundResult()[0], "Spelaren ska vinna när dealern bustar.");
    }

    public function testRoundResultDraw(): void
    {
        $game = new BlackJack();
        $game->startGame(1);

        $playerHand = $this->getPrivateProperty($game, 'playerHands')[0];
        $dealerHand = $this->getPrivateProperty($game, 'dealerHand');
        
        $this->setPrivateProperty($playerHand, 'cards', []);
        $playerHand->addCard(new Card('hearts', 10));
        $playerHand->addCard(new Card('spades', 8));
        
        $this->setPrivateProperty($dealerHand, 'cards', []);
        $dealerHand->addCard(new Card('clubs', 9));
        $dealerHand->addCard(new Card('diamonds', 9));

        $game->playerStand(0); 

        $this->assertEquals('Draw', $game->getRoundResult()[0], "Spelaren ska få 'Draw' vid lika poäng.");
    }

    public function testRoundResultPlayerLosesToDealer(): void
    {
        $game = new BlackJack();
        $game->startGame(1);

        $playerHand = $this->getPrivateProperty($game, 'playerHands')[0];
        $dealerHand = $this->getPrivateProperty($game, 'dealerHand');
        
        $this->setPrivateProperty($playerHand, 'cards', []);
        $playerHand->addCard(new Card('hearts', 10));
        $playerHand->addCard(new Card('spades', 5)); 
        
        $this->setPrivateProperty($dealerHand, 'cards', []);
        $dealerHand->addCard(new Card('clubs', 10));
        $dealerHand->addCard(new Card('diamonds', 10)); 

        $game->playerStand(0); 

        $this->assertEquals('Lose', $game->getRoundResult()[0], "Spelaren ska förlora om dealerns poäng är högre.");
    }

    public function testGettersReturnCorrectState(): void
    {
        $game = new BlackJack();
        $game->startGame(2);

        $this->assertCount(2, $game->getPlayerHands());
        $this->assertInstanceOf(\App\Game\CardHand::class, $game->getDealerHand());
        $this->assertTrue($game->getGameState()['roundActive']);
        $this->assertFalse($game->getGameState()['roundResolved']);
        $this->assertEmpty($game->getRoundResult());
    }

    public function testPlayerHitAddsCardAndRoundContinues(): void
    {
        $game = new BlackJack();
        $game->startGame(1);

        $playerHand = $this->getPrivateProperty($game, 'playerHands')[0];
        $initialCardCount = count($playerHand->getCards());
        
        $this->setPrivateProperty($playerHand, 'cards', []);
        $playerHand->addCard(new Card('hearts', 14)); 
        $playerHand->addCard(new Card('spades', 2));
        
        $game->playerHit(0); 

        $this->assertTrue($this->getPrivateProperty($game, 'isRoundActive'));
        $this->assertCount($initialCardCount + 1, $playerHand->getCards());
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
