<?php

namespace App\Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\Game21\Game21;
use App\Game\Card;
use App\Game\CardHand;
use App\Game\DeckOfCards; // Lade till denna import
use ReflectionClass;

class Game21Test extends TestCase
{
    public function testGameStartsWithTwoCards(): void
    {
        $game = new Game21();
        $game->startGame();

        $state = $game->getGameState();
        $this->assertCount(2, $state['playerHand']);
        $this->assertFalse($state['gameOver']);
    }

    public function testPlayerDrawAndCheckTriggersBankOnBust(): void
    {
        $game = new Game21();
        $game->startGame();

        $playerHand = $this->getPrivateProperty($game, 'playerHand');
        $this->setPrivateProperty($playerHand, 'cards', [new Card('clubs', 10), new Card('hearts', 9)]);

        $game->playerDrawAndCheck();
        
        $state = $game->getGameState();
        $this->assertTrue($state['gameOver']);
        $this->assertGreaterThan(21, $state['playerScore']);
        $this->assertGreaterThanOrEqual(2, count($state['bankHand']));
    }

    public function testDetermineWinnerWhenGameIsOver(): void
    {
        $game = new Game21();
        $game->startGame();
        $game->bankTurn();

        $winner = $game->getGameState()['winner'];
        $this->assertContains($winner, ['Player', 'Bank']);
    }

    public function testInitialGameState(): void
    {
        $game = new Game21();
        $state = $game->getGameState();
        $this->assertCount(0, $state['playerHand']);
        $this->assertFalse($state['gameOver']);
        $this->assertNull($state['bankScore']);
        $this->assertNull($state['winner']);
        $this->assertCount(0, $state['bankHand']);
        $this->assertEquals(0, $game->getPlayerScore());
        $this->assertEquals(0, $game->getBankScore());
    }

    public function testPlayerDrawAndCheckContinuesTurnIfNotBust(): void
    {
        $game = new Game21();
        $game->startGame();
        
        $playerHand = $this->getPrivateProperty($game, 'playerHand');
        $this->setPrivateProperty($playerHand, 'cards', [new Card('hearts', 2), new Card('spades', 3)]);
        
        $game->playerDrawAndCheck(); 
        
        $state = $game->getGameState();
        $this->assertFalse($state['gameOver']);
        $this->assertCount(3, $state['playerHand']);
    }

    // Åtgärdar (Fel 2): Testar stoppvillkoret med score 21 för att säkerställa att banken slutar.
    public function testBankTurnStopsAtSeventeenOrMore(): void
    {
        $game = new Game21();
        
        // Måste starta spelet för att initialisera händerna korrekt.
        $game->startGame(); 
        
        $bankHand = $this->getPrivateProperty($game, 'bankHand');

        // Sätter bankens hand till 21 poäng (Ace=14, Ten=10)
        $this->setPrivateProperty($bankHand, 'cards', [new Card('clubs', 14), new Card('hearts', 10)]); 
        
        $game->bankTurn();
        
        // Banken ska ha 2 kort, eftersom poängen (21) är > 17, stoppas while-loopen.
        $this->assertCount(2, $bankHand->getCards(), "Banken drog kort trots att poängen var 21.");
        $this->assertTrue($game->getGameState()['gameOver']);
        $this->assertEquals(21, $game->getGameState()['bankScore']);
    }
    
    public function testDetermineWinnerPlayerBusts(): void
    {
        $game = new Game21();
        $playerHand = $this->getPrivateProperty($game, 'playerHand');
        $bankHand = $this->getPrivateProperty($game, 'bankHand');
        
        $this->setPrivateProperty($playerHand, 'cards', [new Card('clubs', 10), new Card('hearts', 10), new Card('spades', 2)]);
        $this->setPrivateProperty($bankHand, 'cards', [new Card('diamonds', 2)]);
        $this->setPrivateProperty($game, 'isGameOver', true);

        $this->assertEquals("Bank", $game->determineWinner());
    }
    
    public function testDetermineWinnerBankBusts(): void
    {
        $game = new Game21();
        $playerHand = $this->getPrivateProperty($game, 'playerHand');
        $bankHand = $this->getPrivateProperty($game, 'bankHand');
        
        $this->setPrivateProperty($playerHand, 'cards', [new Card('clubs', 10), new Card('hearts', 10)]);
        $this->setPrivateProperty($bankHand, 'cards', [new Card('diamonds', 10), new Card('spades', 10), new Card('clubs', 2)]);
        $this->setPrivateProperty($game, 'isGameOver', true);

        $this->assertEquals("Player", $game->determineWinner());
    }

    public function testDetermineWinnerPlayerWinsByHigherScore(): void
    {
        $game = new Game21();
        $playerHand = $this->getPrivateProperty($game, 'playerHand');
        $bankHand = $this->getPrivateProperty($game, 'bankHand');
        
        $this->setPrivateProperty($playerHand, 'cards', [new Card('clubs', 10), new Card('hearts', 10)]);
        $this->setPrivateProperty($bankHand, 'cards', [new Card('diamonds', 10), new Card('spades', 9)]);
        $this->setPrivateProperty($game, 'isGameOver', true);

        $this->assertEquals("Player", $game->determineWinner());
    }

    public function testDetermineWinnerBankWinsOnDraw(): void
    {
        $game = new Game21();
        $playerHand = $this->getPrivateProperty($game, 'playerHand');
        $bankHand = $this->getPrivateProperty($game, 'bankHand');
        
        $this->setPrivateProperty($playerHand, 'cards', [new Card('clubs', 10), new Card('hearts', 10)]);
        $this->setPrivateProperty($bankHand, 'cards', [new Card('diamonds', 10), new Card('spades', 10)]);
        $this->setPrivateProperty($game, 'isGameOver', true);

        $this->assertEquals("Bank", $game->determineWinner());
    }

    public function testDetermineWinnerBankWinsByHigherScoreThanPlayer(): void
    {
        $game = new Game21();
        $playerHand = $this->getPrivateProperty($game, 'playerHand');
        $bankHand = $this->getPrivateProperty($game, 'bankHand');
        
        $this->setPrivateProperty($playerHand, 'cards', [new Card('clubs', 10), new Card('hearts', 8)]);
        $this->setPrivateProperty($bankHand, 'cards', [new Card('diamonds', 10), new Card('spades', 10)]);
        $this->setPrivateProperty($game, 'isGameOver', true);

        $this->assertEquals("Bank", $game->determineWinner());
    }

    // Åtgärdar (Fel 1): Återgår till reflection för att hantera tom kortlek (E: ReflectionException).
    public function testDrawCardToPlayerHandlesEmptyDeck(): void
    {
        $game = new Game21();
        // Spelet måste startas för att kortleken ska initialiseras
        $game->startGame(); 

        // Hämta det privata DeckOfCards-objektet från Game21
        $deck = $this->getPrivateProperty($game, 'deck'); 
        
        // Sätt DeckOfCards::$cards till en tom array för att simulera en tom lek.
        $this->setPrivateProperty($deck, 'cards', []);
        
        $game->playerDraw();
        
        $state = $game->getGameState();
        // Spelaren ska fortfarande bara ha de 2 kort som drogs i startGame()
        $this->assertCount(2, $state['playerHand']);
        $this->assertTrue($state['gameOver'], "Spelet ska vara över när kortleken är tom.");
        
        // Testar om dragning efter att leken är tom misslyckas och inte lägger till fler kort
        $game->playerDraw();
        $this->assertCount(2, $game->getGameState()['playerHand']);
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
