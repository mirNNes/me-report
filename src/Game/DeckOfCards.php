<?php

namespace App\Game;

class DeckOfCards
{
    private array $deck = [];

    public function __construct()
    {
        $suits = ['hearts', 'diamonds', 'clubs', 'spades'];
        $values = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 1];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->deck[] = new Card($suit, $value);
            }
        }
    }

    public function shuffle(): void
    {
        shuffle($this->deck);
    }

    public function draw(int $number = 1): array
    {
        $cardsDrawn = array_splice($this->deck, 0, $number);

        return $cardsDrawn;
    }

    public function getDeck(): array
    {
        return $this->deck;
    }

    public function count(): int
    {
        return count($this->deck);
    }
}
