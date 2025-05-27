<?php

namespace App\Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\CardGraphic;

class CardGraphicTest extends TestCase
{
    public function testToString(): void
    {
        $card = new CardGraphic('hearts', 12);
        $this->assertEquals('[Q♥]', (string)$card);

        $card = new CardGraphic('clubs', 1);
        $this->assertEquals('[A♣]', (string)$card);

        $card = new CardGraphic('unknown', 5);
        $this->assertEquals('[5unknown]', (string)$card);
    }
}
