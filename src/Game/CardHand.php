<?php

namespace App\Game;

use App\Game\Card;

/**
 * Represents a hand of playing cards.
 */
class CardHand
{
    /**
     * @var Card[] The cards in the hand.
     */
    private array $cards = [];

    /**
     * Add a card to the hand.
     *
     * @param Card $card
     */
    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    /**
     * Get all cards in the hand.
     *
     * @return Card[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Calculate the score of the hand.
     * Aces count as 14, but adjusted to 1 if score exceeds 21.
     *
     * @return int
     */
    public function getScore(): int
    {
        $score = 0;
        $aces = 0;

        foreach ($this->cards as $card) {
            $value = $card->getNumericValue();

            if ($value === 1) {
                $score += 14;
                $aces++;
                continue;
            }

            if ($value >= 11) {
                $score += 10;
                continue;
            }

            $score += $value;
        }

        while ($score > 21 && $aces > 0) {
            $score -= 13;
            $aces--;
        }

        return $score;
    }
}
