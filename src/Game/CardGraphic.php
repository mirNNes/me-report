<?php

namespace App\Card;

class CardGraphic extends Card
{
    private const SUIT_SYMBOLS = ['hearts' => '♥', 'spades' => '♠', 'diamonds' => '♦', 'clubs' => '♣'];

    public function __toString(): string
    {
        $symbol = self::SUIT_SYMBOLS[$this->getSuit()] ?? $this->getSuit();
        return "[{$this->getValue()}$symbol]";
    }
}
