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
        $this->playerHand->addCard($this->deck->draw()[0]);
        $this->playerHand->addCard($this->deck->draw()[0]);
    }

    public function playerDraw(): void
    {
        $this->playerHand->addCard($this->deck->draw()[0]);
    }

    public function bankTurn(): void
    {
        $this->bankHand->addCard($this->deck->draw()[0]);
        $this->bankHand->addCard($this->deck->draw()[0]);

        while ($this->getBankScore() < 17) {
            $this->bankHand->addCard($this->deck->draw()[0]);
        }

        $this->isGameOver = true;
    }



    public function getPlayerHand(): CardHand
    {
        return $this->playerHand;
    }

    public function getBankHand(): CardHand
    {
        return $this->bankHand;
    }

    public function getPlayerScore(): int
    {
        return $this->playerHand->getScore();
    }

    public function getBankScore(): int
    {
        return $this->bankHand->getScore();
    }

    public function isGameOver(): bool
    {
        return $this->isGameOver;
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
