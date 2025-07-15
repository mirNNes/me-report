<?php

namespace App\Game;

/**
 * Representerar en hand med kort.
 */
class CardHand
{
    /**
     * @var Card[] Kort i handen.
     */
    private array $cards = [];

    /**
     * Lägger till ett kort i handen.
     *
     * @param Card $card Kortet som ska läggas till.
     */
    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    /**
     * Lägger till flera kort i handen.
     *
     * @param Card[] $cards Array med kort som ska läggas till.
     */
    public function addCards(array $cards): void
    {
        foreach ($cards as $card) {
            $this->addCard($card);
        }
    }

    /**
     * Returnerar alla kort i handen.
     *
     * @return Card[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Räknar ihop poäng i handen.
     * Ess räknas som 11, men kan räknas som 1 om totalpoängen blir över 21.
     * Knekt, Dam, Kung räknas som 10 poäng.
     *
     * @return int Total poäng.
     */
    public function getScore(): int
    {
        $score = 0;
        $aces = 0;

        foreach ($this->cards as $card) {
            $value = $card->getNumericValue();
            if ($value > 10) {
                $value = 10;
            }
            if ($value === 1) {
                $aces++;
                $value = 11;
            }
            $score += $value;
        }

        // Justera Ess från 11 till 1 om poängen överstiger 21
        while ($score > 21 && $aces > 0) {
            $score -= 10;
            $aces--;
        }

        return $score;
    }

    /**
     * Kollar om handen är bust (över 21 poäng).
     *
     * @return bool True om bust, annars false.
     */
    public function isBust(): bool
    {
        return $this->getScore() > 21;
    }

    /**
     * Kollar om handen kan splittas (två kort med samma valör).
     *
     * @return bool True om kan splittas, annars false.
     */
    public function canSplit(): bool
    {
        if (count($this->cards) !== 2) {
            return false;
        }
        return $this->cards[0]->getNumericValue() === $this->cards[1]->getNumericValue();
    }

    /**
     * Om spelaren valt att stå (inte ta fler kort).
     *
     * @var bool
     */
    private bool $standing = false;

    /**
     * Sätter om handen står (spelare står).
     *
     * @param bool $standing True om står, annars false.
     * @return void
     */
    public function setStanding(bool $standing): void
    {
        $this->standing = $standing;
    }

    /**
     * Kollar om handen står.
     *
     * @return bool True om står, annars false.
     */
    public function isStanding(): bool
    {
        return $this->standing;
    }

    /**
     * Kollar om handen är klar (antingen står eller bust).
     *
     * @return bool True om klar, annars false.
     */
    public function isDone(): bool
    {
        return $this->isStanding() || $this->isBust();
    }
}
