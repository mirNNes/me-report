<?php

namespace App\BlackJack;

use App\Game\DeckOfCards;
use App\Game\CardHand;
use InvalidArgumentException;

/**
 * Klass för att hantera ett BlackJack-spel med 1 till 3 spelare.
 */
class BlackJack
{
    /**
     * Kortleken som används i spelet.
     *
     * @var DeckOfCards
     */
    private DeckOfCards $deck;

    /**
     * Spelarens händer.
     *
     * @var CardHand[]
     */
    private array $playerHands = [];

    /**
     * Dealerns hand.
     *
     * @var CardHand
     */
    private CardHand $dealerHand;

    /**
     * Indikerar om rundan är aktiv.
     *
     * @var bool
     */
    private bool $isRoundActive = false;

    /**
     * Resultat för varje spelhand efter en avslutad runda.
     * 'Win', 'Lose' eller 'Draw' per spelhand.
     *
     * @var string[]
     */
    private array $roundResult = [];

    /**
     * Indikerar om rundan är avslutad och resultaten är beräknade.
     *
     * @var bool
     */
    private bool $roundResolved = false;

    /**
     * Skapar ett nytt BlackJack-spel med en blandad kortlek.
     */
    public function __construct()
    {
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
        $this->dealerHand = new CardHand();
    }

    /**
     * Startar en ny runda av spelet.
     *
     * @param int $numberOfHands Antal spelarhänder (1 till 3).
     * @throws InvalidArgumentException Om antalet händer är ogiltigt.
     */
    public function startGame(int $numberOfHands = 1): void
    {
        if ($numberOfHands < 1 || $numberOfHands > 3) {
            throw new InvalidArgumentException("Antal händer måste vara mellan 1 och 3.");
        }

        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
        $this->playerHands = [];
        $this->dealerHand = new CardHand();
        $this->roundResult = [];
        $this->roundResolved = false;
        $this->isRoundActive = true;

        for ($i = 0; $i < $numberOfHands; $i++) {
            $hand = new CardHand();
            $hand->addCards($this->deck->draw(2));
            $this->playerHands[] = $hand;
        }

        $this->dealerHand->addCards($this->deck->draw(2));
    }

    /**
     * Spelaren drar ett kort ("Hit") till vald hand.
     *
     * @param int $index Index för spelarens hand.
     */
    public function playerHit(int $index): void
    {
        if (!$this->isRoundActive || !isset($this->playerHands[$index])) {
            return;
        }

        $cards = $this->deck->draw(1);
        if (!empty($cards)) {
            $this->playerHands[$index]->addCard($cards[0]);
        }

        if ($this->playerHands[$index]->isBust()) {
            $this->playerHands[$index]->setStanding(true);
        }

        if ($this->allPlayersDone()) {
            $this->dealerTurn();
            $this->resolveRound();
        }
    }

    /**
     * Spelaren väljer att stanna ("Stand") på vald hand.
     *
     * @param int $index Index för spelarens hand.
     */
    public function playerStand(int $index): void
    {
        if (!$this->isRoundActive || !isset($this->playerHands[$index])) {
            return;
        }

        $this->playerHands[$index]->setStanding(true);

        if ($this->allPlayersDone()) {
            $this->dealerTurn();
            $this->resolveRound();
        }
    }

    /**
     * Dealer drar kort tills den når minst 17 poäng.
     */
    private function dealerTurn(): void
    {
        while ($this->dealerHand->getScore() < 17) {
            $cards = $this->deck->draw(1);
            if (!empty($cards)) {
                $this->dealerHand->addCard($cards[0]);
            }
        }
    }

    /**
     * Kollar om alla spelares händer är färdiga.
     *
     * @return bool True om alla spelare är klara (bust eller stått).
     */
    private function allPlayersDone(): bool
    {
        foreach ($this->playerHands as $hand) {
            if (!$hand->isDone()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Avgör resultatet för varje spelhand jämfört med dealern.
     */
    private function resolveRound(): void
    {
        if ($this->roundResolved) {
            return;
        }

        $dealerScore = $this->dealerHand->getScore();
        $dealerBust = $this->dealerHand->isBust();

        foreach ($this->playerHands as $i => $hand) {
            $score = $hand->getScore();
            $bust = $hand->isBust();

            if ($bust) {
                $this->roundResult[$i] = 'Lose';
            } elseif ($dealerBust) {
                $this->roundResult[$i] = 'Win';
            } elseif ($score > $dealerScore) {
                $this->roundResult[$i] = 'Win';
            } elseif ($score === $dealerScore) {
                $this->roundResult[$i] = 'Draw';
            } else {
                $this->roundResult[$i] = 'Lose';
            }
        }

        $this->roundResolved = true;
        $this->isRoundActive = false;
    }

    /**
     * Hämtar alla spelarens händer.
     *
     * @return CardHand[]
     */
    public function getPlayerHands(): array
    {
        return $this->playerHands;
    }

    /**
     * Hämtar dealerns hand.
     *
     * @return CardHand
     */
    public function getDealerHand(): CardHand
    {
        return $this->dealerHand;
    }

    /**
     * Hämtar resultatet för den senaste rundan.
     *
     * @return string[] Resultat för varje hand ('Win', 'Lose', 'Draw').
     */
    public function getRoundResult(): array
    {
        return $this->roundResult;
    }

    /**
     * Hämtar hela spelstatusen i form av en array.
     *
     * @return array{
     *     playerHands: CardHand[],
     *     dealerHand: CardHand,
     *     roundActive: bool,
     *     roundResult: string[],
     *     roundResolved: bool
     * }
     */
    public function getGameState(): array
    {
        return [
            'playerHands' => $this->getPlayerHands(),
            'dealerHand' => $this->getDealerHand(),
            'roundActive' => $this->isRoundActive,
            'roundResult' => $this->getRoundResult(),
            'roundResolved' => $this->roundResolved,
        ];
    }
}
