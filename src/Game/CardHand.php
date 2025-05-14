<?php

namespace App\Game;

class CardHand
{
    private array $cards = [];

    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function getScore(): int
    {
        $score = 0;
        $aces = 0;

        foreach ($this->cards as $card) {
            $value = $card->getValue();

            if ($value === 1) {
                $score += 14;
                $aces++;
            } elseif ($value >= 11) {
                $score += 10;
            } else {
                $score += $value;
            }
        }

        while ($score > 21 && $aces > 0) {
            $score -= 13;
            $aces--;
        }

        return $score;
    }

}
