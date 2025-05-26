<?php

namespace App\Game;

/**
 * Represents a full deck of 52 playing cards.
 */
class DeckOfCards
{
    /**
     * @var Card[] The array of Card objects in the deck.
     */
    private array $deck = [];

    /**
     * Constructor that initializes the deck with 52 standard playing cards.
     */
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

    /**
     * Shuffle the cards in the deck.
     *
     * @return void
     */
    public function shuffle(): void
    {
        shuffle($this->deck);
    }

    /**
     * Draw a number of cards from the top of the deck.
     *
     * @param int $number Number of cards to draw.
     *
     * @return Card[] An array of drawn Card objects.
     */
    public function draw(int $number = 1): array
    {
        return array_splice($this->deck, 0, $number);
    }

    /**
     * Get the current deck of cards.
     *
     * @return Card[] The current array of cards.
     */
    public function getDeck(): array
    {
        return $this->deck;
    }

    /**
     * Get the number of remaining cards in the deck.
     *
     * @return int Number of cards left.
     */
    public function count(): int
    {
        return count($this->deck);
    }
}
