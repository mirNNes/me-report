<?php

namespace App\Game\Game21;

use App\Game\DeckOfCards;
use App\Game\CardHand;
use App\Game\Card;

/**
 * Game21 hanterar logiken för ett kortspel där målet är att komma så nära 21 som möjligt utan att gå över.
 */
class Game21
{
    private DeckOfCards $deck;
    private CardHand $playerHand;
    private CardHand $bankHand;
    private bool $isGameOver = false;

    /**
     * Skapar ett nytt spel och blandar kortleken.
     */
    public function __construct()
    {
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
        $this->playerHand = new CardHand();
        $this->bankHand = new CardHand();
    }

    /**
     * Startar spelet genom att ge spelaren två kort.
     *
     * @return void
     */
    public function startGame(): void
    {
        $this->drawCardToPlayer();
        $this->drawCardToPlayer();
    }

    /**
     * Låter spelaren dra ett kort.
     *
     * @return void
     */
    public function playerDraw(): void
    {
        $this->drawCardToPlayer();
    }

    /**
     * Låter spelaren dra ett kort och kollar om spelet bör gå över till banken.
     *
     * @return void
     */
    public function playerDrawAndCheck(): void
    {
        $this->playerDraw();

        if ($this->getPlayerScore() > 21) {
            $this->bankTurn();
        }
    }

    /**
     * Banken drar kort tills den når minst 17 poäng.
     *
     * @return void
     */
    public function bankTurn(): void
    {
        $this->drawCardToBank();
        $this->drawCardToBank();

        while ($this->getBankScore() < 17) {
            $this->drawCardToBank();
        }

        $this->isGameOver = true;
    }

    /**
     * Returnerar aktuell spelstatus.
     *
     * @return array{
     *   playerHand: list<Card>,
     *   playerScore: int,
     *   bankHand: list<Card>,
     *   bankScore: int|null,
     *   gameOver: bool,
     *   winner: string|null
     * }
     */
    public function getGameState(): array
    {
        return [
            'playerHand' => array_values($this->playerHand->getCards()),
            'playerScore' => $this->getPlayerScore(),
            'bankHand' => $this->isGameOver ? array_values($this->bankHand->getCards()) : [],
            'bankScore' => $this->isGameOver ? $this->getBankScore() : null,
            'gameOver' => $this->isGameOver,
            'winner' => $this->isGameOver ? $this->determineWinner() : null,
        ];
    }

    /**
     * Hämtar spelarens nuvarande poäng.
     *
     * @return int
     */
    public function getPlayerScore(): int
    {
        return $this->playerHand->getScore();
    }

    /**
     * Hämtar bankens nuvarande poäng.
     *
     * @return int
     */
    public function getBankScore(): int
    {
        return $this->bankHand->getScore();
    }

    /**
     * Drar ett kort till spelaren.
     *
     * @return void
     */
    private function drawCardToPlayer(): void
    {
        $card = $this->deck->drawOne();

        if ($card === null) {
            $this->isGameOver = true;
            return;
        }

        $this->playerHand->addCard($card);
    }

    /**
     * Drar ett kort till banken.
     *
     * @return void
     */
    private function drawCardToBank(): void
    {
        $card = $this->deck->drawOne();

        if ($card === null) {
            $this->isGameOver = true;
            return;
        }

        $this->bankHand->addCard($card);
    }

    /**
     * Bestämmer vinnaren utifrån poäng.
     *
     * @return string "Player" eller "Bank"
     */
    public function determineWinner(): string
    {
        $player = $this->getPlayerScore();
        $bank = $this->getBankScore();

        if ($player > 21) {
            return "Bank";
        }

        if ($bank > 21 || $player > $bank) {
            return "Player";
        }

        return "Bank";
    }
}
