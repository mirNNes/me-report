<?php

namespace App\Game\Game21;

use App\Game\DeckOfCards;
use App\Game\CardHand;

class Game21
{
    private DeckOfCards $deck;
    private CardHand $playerHand;
    private CardHand $bankHand;
    private bool $isGameOver = false;

    public function __construct()
    {
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
        $this->playerHand = new CardHand();
        $this->bankHand = new CardHand();
    }

    public function startGame(): void
    {
        $this->drawCardToPlayer();
        $this->drawCardToPlayer();
    }

    public function playerDraw(): void
    {
        $this->drawCardToPlayer();
    }

    public function playerDrawAndCheck(): void
    {
        $this->playerDraw();
        if ($this->getPlayerScore() > 21) {
            $this->bankTurn();
        }
    }

    public function bankTurn(): void
    {
        $this->drawCardToBank();
        $this->drawCardToBank();

        while ($this->getBankScore() < 17) {
            $this->drawCardToBank();
        }

        $this->isGameOver = true;
    }

    public function getGameState(): array
    {
        return [
            'playerHand' => $this->playerHand->getCards(),
            'playerScore' => $this->getPlayerScore(),
            'bankHand' => $this->isGameOver ? $this->bankHand->getCards() : [],
            'bankScore' => $this->isGameOver ? $this->getBankScore() : null,
            'gameOver' => $this->isGameOver,
            'winner' => $this->isGameOver ? $this->determineWinner() : null,
        ];
    }

    public function getPlayerScore(): int
    {
        return $this->playerHand->getScore();
    }

    public function getBankScore(): int
    {
        return $this->bankHand->getScore();
    }

    private function drawCardToPlayer(): void
    {
        $card = $this->deck->draw()[0];
        $this->playerHand->addCard($card);
    }

    private function drawCardToBank(): void
    {
        $card = $this->deck->draw()[0];
        $this->bankHand->addCard($card);
    }

    public function determineWinner(): string
    {
        $player = $this->getPlayerScore();
        $bank = $this->getBankScore();

        if ($player > 21) {
            return "Bank";
        }

        if ($bank > 21 || $player > $bank) {
            return "Spelare";
        }

        return "Bank";
    }
}
