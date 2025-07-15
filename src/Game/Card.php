<?php

namespace App\Game;

/**
 * Representerar ett spelkort med färg och värde.
 */
class Card
{
    /**
     * @var string Kortets färg (t.ex. "hearts", "spades").
     */
    private string $suit;

    /**
     * @var int Kortets värde (1-13, där 1 = Ess, 11 = Knekt, 12 = Dam, 13 = Kung).
     */
    private int $value;

    /**
     * Konstruktor.
     *
     * @param string $suit  Kortets färg.
     * @param int    $value Kortets värde.
     */
    public function __construct(string $suit, int $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    /**
     * Hämtar kortets färg.
     *
     * @return string
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * Hämtar kortets värde.
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Returnerar kortets namn, t.ex. "A", "10", "Q".
     *
     * @return string
     */
    public function getName(): string
    {
        return match ($this->value) {
            1 => 'A',
            11 => 'J',
            12 => 'Q',
            13 => 'K',
            default => (string) $this->value,
        };
    }

    /**
     * Returnerar kortets symbol för färg.
     *
     * @return string
     */
    public function getSuitSymbol(): string
    {
        return match ($this->suit) {
            'hearts'   => '♥',
            'diamonds' => '♦',
            'clubs'    => '♣',
            'spades'   => '♠',
            default    => '?',
        };
    }

    /**
     * Returnerar en strängrepresentation av kortet, t.ex. "[K♠]".
     *
     * @return string
     */
    public function __toString(): string
    {
        return "[{$this->getName()}{$this->getSuitSymbol()}]";
    }

    /**
     * Returnerar kortet som en sträng, t.ex. "[K♠]".
     * Kan användas där man inte vill förlita sig på __toString().
     *
     * @return string
     */
    public function getAsString(): string
    {
        return $this->__toString();
    }

    /**
     * Returnerar kortets numeriska värde (1–13).
     * Används för beräkningar i spel som Blackjack.
     *
     * @return int
     */
    public function getNumericValue(): int
    {
        return $this->value;
    }
}
