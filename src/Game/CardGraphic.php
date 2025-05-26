<?php


namespace App\Game;

/**
 * A card that can be represented graphically as a string with suit symbol.
 * Inherits suit and value from Card.
 */
class CardGraphic extends Card
{
    /**
     * Unicode symbols for suits.
     */
    private const SUIT_SYMBOLS = [
        'hearts' => '♥',
        'spades' => '♠',
        'diamonds' => '♦',
        'clubs' => '♣'
    ];

    /**
     * Returns the card as a string in format like [Q♦].
     *
     * @return string
     */
    public function __toString(): string
    {
        $symbol = self::SUIT_SYMBOLS[strtolower($this->getSuit())] ?? $this->getSuit();
        return "[{$this->getValue()}$symbol]";
    }
}
