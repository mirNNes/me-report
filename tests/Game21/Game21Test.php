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
        $this->assertFalse($state['gameOver']);
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

        $state = $game->getGameState();
        $this->assertTrue($state['gameOver']);
        $this->assertGreaterThanOrEqual(2, count($state['bankHand']));
    }

    public function testDetermineWinner(): void
    {
        $game = new Game21();
        $game->startGame();
        $game->bankTurn();

        $winner = $game->getGameState()['winner'];
        $this->assertContains($winner, ['Player', 'Bank']);
    }
}
