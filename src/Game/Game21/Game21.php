<?php

namespace App\Game\Game21;

use App\Game\DeckOfCards;
use App\Game\CardHand;

/**
 * Game21 handles the logic for a card game where the goal is to get as close to 21 as possible without going over.
 */
class Game21
{
    /**
     * @var DeckOfCards The deck used in the game.
     */
    private DeckOfCards $deck;

    /**
     * @var CardHand The player's hand.
     */
    private CardHand $playerHand;

    /**
     * @var CardHand The bank's hand.
     */
    private CardHand $bankHand;

    /**
     * @var bool Indicates whether the game is over.
     */
    private bool $isGameOver = false;

    /**
     * Constructor: Initializes the game with a shuffled deck and empty hands for player and bank.
     */
    public function __construct()
    {
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
        $this->playerHand = new CardHand();
        $this->bankHand = new CardHand();
    }

    /**
     * Starts the game by drawing two cards for the player.
     */
    public function startGame(): void
    {
        $this->drawCardToPlayer();
        $this->drawCardToPlayer();
    }

    /**
     * The player draws a card.
     */
    public function playerDraw(): void
    {
        $this->drawCardToPlayer();
    }

    /**
     * The player draws a card and if the score exceeds 21, the bank takes its turn.
     */
    public function playerDrawAndCheck(): void
    {
        $this->playerDraw();
        if ($this->getPlayerScore() > 21) {
            $this->bankTurn();
        }
    }

    /**
     * The bank's turn to draw cards until at least 17 points are reached or the player busts.
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
     * Returns the current state of the game, including cards, scores, and the winner if the game is over.
     *
     * @return array{
     *     playerHand: list<\App\Game\Card>,
     *     playerScore: int,
     *     bankHand: list<\App\Game\Card>,
     *     bankScore: int|null,
     *     gameOver: bool,
     *     winner: string|null
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
     * Gets the player's score.
     *
     * @return int
     */
    public function getPlayerScore(): int
    {
        return $this->playerHand->getScore();
    }

    /**
     * Gets the bank's score.
     *
     * @return int
     */
    public function getBankScore(): int
    {
        return $this->bankHand->getScore();
    }

    /**
     * Draws a card to the player.
     */
    private function drawCardToPlayer(): void
    {
        $card = $this->deck->draw()[0];
        $this->playerHand->addCard($card);
    }

    /**
     * Draws a card to the bank.
     */
    private function drawCardToBank(): void
    {
        $card = $this->deck->draw()[0];
        $this->bankHand->addCard($card);
    }

    /**
     * Determines the winner of the game.
     *
     * @return string "Player" or "Bank"
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
