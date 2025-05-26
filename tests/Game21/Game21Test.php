<?php

namespace App\Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\Game21\Game21;

class Game21Test extends TestCase
{
    public function testGameStartsWithTwoCards(): void
    {
        $game = new Game21();
        $game->startGame();

        $state = $game->getGameState();
        $this->assertCount(2, $state['playerHand']);
        $this->assertIsInt($state['playerScore']);
        $this->assertFalse($state['gameOver']);
    }

    public function testPlayerDrawAddsCard(): void
    {
        $game = new Game21();
        $game->startGame();
        $before = count($game->getGameState()['playerHand']);

        $game->playerDraw();
        $after = count($game->getGameState()['playerHand']);

        $this->assertSame($before + 1, $after);
    }

    public function testPlayerDrawAndCheckTriggersBankOnBust(): void
    {
        $game = new Game21();
        $game->startGame();

        for ($i = 0; $i < 10; $i++) {
            $game->playerDrawAndCheck();
            if ($game->getGameState()['gameOver']) {
                break;
            }
        }

        $this->assertTrue($game->getGameState()['gameOver']);
        $this->assertNotNull($game->getGameState()['bankHand']);
    }

    public function testBankPlaysToAtLeast17(): void
    {
        $game = new Game21();
        $game->startGame();
        $game->bankTurn();

        $this->assertTrue($game->getGameState()['gameOver']);
        $this->assertGreaterThanOrEqual(17, $game->getBankScore());
    }

    public function testDetermineWinner(): void
    {
        $game = new Game21();
        $game->startGame();
        $game->bankTurn();

        $state = $game->getGameState();
        $this->assertContains($state['winner'], ['Spelare', 'Bank']);
    }
}
