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
     *
     * @var array<string, string>
     */
    private const SUIT_SYMBOLS = [
        'hearts' => '♥',
        'spades' => '♠',
        'diamonds' => '♦',
        'clubs' => '♣'
    ];

    /**
     * Symbolic representation of face card values.
     *
     * @var array<int, string>
     */
    private const VALUE_SYMBOLS = [
        1 => 'A',
        11 => 'J',
        12 => 'Q',
        13 => 'K'
    ];

    /**
     * Returns the card as a string in format like [Q♦], [10♠], [A♣], etc.
     *
     * @return string
     */
    public function __toString(): string
    {
        $value = self::VALUE_SYMBOLS[$this->getValue()] ?? $this->getValue();
        $suit = self::SUIT_SYMBOLS[strtolower($this->getSuit())] ?? $this->getSuit();

        return "[{$value}{$suit}]";
    }
}
