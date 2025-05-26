<?php

namespace App\Game;

/**
 * Represents a standard playing card with a suit and value.
 */
class Card
{
    /**
     * @var string The suit of the card (e.g., Hearts, Spades).
     */
    protected string $suit;

    /**
     * @var int The numeric value of the card (1–13).
     */
    protected int $value;

    /**
     * Constructor for Card.
     *
     * @param string $suit  The suit of the card.
     * @param int    $value The numeric value of the card (1 = Ace, 11 = Jack, 12 = Queen, 13 = King).
     */
    public function __construct(string $suit, int $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    /**
     * Get the suit of the card.
     *
     * @return string The suit of the card.
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * Get the numeric value of the card.
     *
     * @return int The raw numeric value (1–13).
     */
    public function getNumericValue(): int
    {
        return $this->value;
    }

    /**
     * Get the value of the card as a displayable string.
     *
     * @return string 'A', '2'...'10', 'J', 'Q', or 'K'.
     */
    public function getValue(): string
    {
        return match ($this->value) {
            1  => 'A',
            11 => 'J',
            12 => 'Q',
            13 => 'K',
            default => (string) $this->value
        };
    }

    /**
     * Get the card as a string with value and suit symbol, e.g., [A♥].
     *
     * @return string The formatted card string.
     */
    public function getAsString(): string
    {
        return "[{$this->getValue()}{$this->getSuitSymbol()}]";
    }

    /**
     * Get the Unicode symbol for the card's suit.
     *
     * @return string The symbol of the suit ('♥', '♦', '♣', '♠', or '?').
     */
    public function getSuitSymbol(): string
    {
        return match (strtolower($this->suit)) {
            'hearts'   => '♥',
            'diamonds' => '♦',
            'clubs'    => '♣',
            'spades'   => '♠',
            default    => '?'
        };
    }
}
