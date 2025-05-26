<?php

namespace App\Game\Game21;

use App\Game\DeckOfCards;
use App\Game\CardHand;

/**
 * Game21 hanterar logiken för ett kortspel där målet är att komma så nära 21 som möjligt utan att gå över.
 */
class Game21
{
    /**
     * @var DeckOfCards Kortleken som används i spelet.
     */
    private DeckOfCards $deck;

    /**
     * @var CardHand Spelarens hand.
     */
    private CardHand $playerHand;

    /**
     * @var CardHand Bankens hand.
     */
    private CardHand $bankHand;

    /**
     * @var bool Huruvida spelet är slut eller ej.
     */
    private bool $isGameOver = false;

    /**
     * Konstruktor: Initierar spelet med en kortlek och tomma händer för spelare och bank.
     */
    public function __construct()
    {
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
        $this->playerHand = new CardHand();
        $this->bankHand = new CardHand();
    }

    /**
     * Startar spelet genom att dra två kort till spelaren.
     */
    public function startGame(): void
    {
        $this->drawCardToPlayer();
        $this->drawCardToPlayer();
    }

    /**
     * Spelaren drar ett kort.
     */
    public function playerDraw(): void
    {
        $this->drawCardToPlayer();
    }

    /**
     * Spelaren drar ett kort och om poängen överstiger 21 så spelar banken sin tur.
     */
    public function playerDrawAndCheck(): void
    {
        $this->playerDraw();
        if ($this->getPlayerScore() > 21) {
            $this->bankTurn();
        }
    }

    /**
     * Bankens tur att dra kort tills minst 17 poäng uppnåtts eller spelaren bustat.
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
     * Returnerar spelets aktuella tillstånd inklusive kort, poäng och eventuell vinnare.
     *
     * @return array{
     *     playerHand: array,
     *     playerScore: int,
     *     bankHand: array,
     *     bankScore: int|null,
     *     gameOver: bool,
     *     winner: string|null
     * }
     */
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

    /**
     * Hämtar spelarens poäng.
     *
     * @return int
     */
    public function getPlayerScore(): int
    {
        return $this->playerHand->getScore();
    }

    /**
     * Hämtar bankens poäng.
     *
     * @return int
     */
    public function getBankScore(): int
    {
        return $this->bankHand->getScore();
    }

    /**
     * Drar ett kort till spelaren.
     */
    private function drawCardToPlayer(): void
    {
        $card = $this->deck->draw()[0];
        $this->playerHand->addCard($card);
    }

    /**
     * Drar ett kort till banken.
     */
    private function drawCardToBank(): void
    {
        $card = $this->deck->draw()[0];
        $this->bankHand->addCard($card);
    }

    /**
     * Avgör vem som vinner spelet.
     *
     * @return string "Spelare" eller "Bank"
     */
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
