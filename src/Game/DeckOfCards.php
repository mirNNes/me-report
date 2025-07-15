<?php

namespace App\Game;

/**
 * Representerar en standardkortlek med 52 kort.
 */
class DeckOfCards
{
    /**
     * @var Card[] Kortleken.
     */
    private array $deck = [];

    /**
     * Skapar en ny kortlek med 52 kort.
     */
    public function __construct()
    {
        $suits = ['hearts', 'diamonds', 'clubs', 'spades'];
        $values = range(1, 13);

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->deck[] = new Card($suit, $value);
            }
        }
    }

    /**
     * Blandar kortleken.
     */
    public function shuffle(): void
    {
        shuffle($this->deck);
    }

    /**
     * Drar ett eller flera kort från toppen av kortleken.
     *
     * @param int $count Antal kort att dra.
     * @return Card[] Returnerar en array med kort (kan vara tom om leken är slut).
     */
    public function draw(int $count = 1): array
    {
        $cards = [];

        for ($i = 0; $i < $count; $i++) {
            $card = array_shift($this->deck);
            if ($card === null) {
                break;
            }
            $cards[] = $card;
        }

        return $cards;
    }

    /**
     * Returnerar antal kort kvar i leken.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->deck);
    }

    /**
     * Returnerar hela kortleken.
     *
     * @return Card[] Array med alla kvarvarande kort i leken.
     */
    public function getDeck(): array
    {
        return $this->deck;
    }
}
