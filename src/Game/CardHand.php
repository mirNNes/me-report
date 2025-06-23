<?php

namespace App\Game;

class CardHand
{
    /**
     * @var Card[] Kort i handen
     */
    private array $cards = [];

    /**
     * @var int Satsningen för handen
     */
    private int $bet = 0;

    /**
     * @var bool Om spelaren står på denna hand
     */
    private bool $standing = false;

    /**
     * Lägg till ett kort till handen.
     *
     * @param Card $card
     * @return void
     */
    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    /**
     * Hämta alla kort i handen.
     *
     * @return Card[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Sätt satsningen för handen.
     *
     * @param int $bet
     * @return void
     */
    public function setBet(int $bet): void
    {
        $this->bet = $bet;
    }

    /**
     * Hämta satsningen för handen.
     *
     * @return int
     */
    public function getBet(): int
    {
        return $this->bet;
    }

    /**
     * Beräkna poäng för handen med korrekt ess-hantering.
     *
     * @return int
     */
    public function getScore(): int
    {
        $score = 0;
        $aces = 0;

        foreach ($this->cards as $card) {
            $value = $card->getNumericValue();

            if ($value >= 10) {
                // Klädda kort (J, Q, K) värderas som 10
                $score += 10;
            } elseif ($value === 1) {
                // Ess räknas separat
                $aces++;
                $score += 11; // Räkna ess som 11 till en början
            } else {
                $score += $value;
            }
        }

        // Om poängen går över 21 och det finns ess, räkna om ess som 1 istället för 11
        while ($score > 21 && $aces > 0) {
            $score -= 10; // dra bort 10 för ett ess (11->1)
            $aces--;
        }

        return $score;
    }

    /**
     * Kolla om handen är "bust" (poäng över 21).
     *
     * @return bool
     */
    public function isBust(): bool
    {
        return $this->getScore() > 21;
    }

    /**
     * Kolla om spelaren står på denna hand.
     *
     * @return bool
     */
    public function isStanding(): bool
    {
        return $this->standing;
    }

    /**
     * Sätt om spelaren står på handen.
     *
     * @param bool $standing
     * @return void
     */
    public function setStanding(bool $standing): void
    {
        $this->standing = $standing;
    }

    /**
     * Kolla om handen kan splittas (två kort med samma värde).
     *
     * @return bool
     */
    public function canSplit(): bool
    {
        if (count($this->cards) !== 2) {
            return false;
        }

        return $this->cards[0]->getNumericValue() === $this->cards[1]->getNumericValue();
    }
}
