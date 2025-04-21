<?php

namespace App\Game;

class Card
{
    protected string $suit;
    protected int $value;

    public function __construct(string $suit, int $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

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

    public function getAsString(): string
    {
        return "[{$this->getValue()}{$this->getSuitSymbol()}]";
    }

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
