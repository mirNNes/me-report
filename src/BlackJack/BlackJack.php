<?php

namespace App\BlackJack;

use App\Game\DeckOfCards;
use App\Game\CardHand;

class BlackJack
{
    private string $playerName;
    private DeckOfCards $deck;
    private array $playerHands = [];
    private CardHand $dealerHand;
    private int $bank;
    private bool $isRoundActive = false;
    private bool $roundResolved = false;
    private array $roundResult = []; // Ny egenskap för resultat

    public function __construct(string $playerName = "Spelare", int $startingBank = 1000)
    {
        $this->playerName = $playerName;
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
        $this->dealerHand = new CardHand();
        $this->bank = $startingBank;
    }

    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    public function startGame(array $bets): void
    {
        if (count($bets) < 1 || count($bets) > 3) {
            throw new \InvalidArgumentException("Antal händer måste vara mellan 1 och 3.");
        }

        foreach ($bets as $bet) {
            if ($bet > $this->bank) {
                throw new \Exception("Inte tillräckligt med pengar.");
            }
        }

        $this->isRoundActive = true;
        $this->roundResolved = false;
        $this->playerHands = [];
        $this->dealerHand = new CardHand();
        $this->roundResult = []; // Nollställ resultat vid ny runda

        foreach ($bets as $bet) {
            $this->bank -= $bet;
            $hand = new CardHand();
            $hand->setBet($bet);
            $hand->addCard($this->deck->draw()[0]);
            $hand->addCard($this->deck->draw()[0]);
            $this->playerHands[] = $hand;
        }

        $this->dealerHand->addCard($this->deck->draw()[0]);
        $this->dealerHand->addCard($this->deck->draw()[0]);
    }

    public function playerHit(int $index = 0): void
    {
        if (!$this->isRoundActive || !isset($this->playerHands[$index])) {
            throw new \LogicException("Fel i spelflödet.");
        }

        $this->playerHands[$index]->addCard($this->deck->draw()[0]);

        if ($this->playerHands[$index]->isBust()) {
            $this->playerStand($index);
        }
    }

    public function playerStand(int $index = 0): void
    {
        if (!isset($this->playerHands[$index])) {
            throw new \LogicException("Ogiltig hand.");
        }

        $this->playerHands[$index]->setStanding(true);

        if ($this->allPlayersDone()) {
            $this->dealerTurn();
            $this->isRoundActive = false;
            $this->roundResolved = true;
            $this->resolveRound();
        }
    }

    public function playerSplit(int $index): void
    {
        if (!$this->isRoundActive || !isset($this->playerHands[$index])) {
            throw new \LogicException("Ogiltig hand för split.");
        }

        $hand = $this->playerHands[$index];

        if (!$hand->canSplit()) {
            throw new \LogicException("Denna hand kan inte splittas.");
        }

        $bet = $hand->getBet();

        if ($this->bank < $bet) {
            throw new \Exception("Inte tillräckligt med pengar för att splitta.");
        }

        $this->bank -= $bet;

        $cards = $hand->getCards();

        $hand1 = new CardHand();
        $hand1->setBet($bet);
        $hand1->addCard($cards[0]);
        $hand1->addCard($this->deck->draw()[0]);

        $hand2 = new CardHand();
        $hand2->setBet($bet);
        $hand2->addCard($cards[1]);
        $hand2->addCard($this->deck->draw()[0]);

        array_splice($this->playerHands, $index, 1, [$hand1, $hand2]);
    }

    private function dealerTurn(): void
    {
        while ($this->dealerHand->getScore() < 17) {
            $this->dealerHand->addCard($this->deck->draw()[0]);
        }
    }

    private function resolveRound(): void
    {
        $dealerScore = $this->dealerHand->getScore();
        $this->roundResult = [];

        foreach ($this->playerHands as $index => $hand) {
            $score = $hand->getScore();
            $bet = $hand->getBet();

            if ($score > 21) {
                $this->roundResult[$index] = 'Förlust';
                continue;
            }

            $blackjack = ($score === 21 && count($hand->getCards()) === 2);

            if ($dealerScore > 21 || $score > $dealerScore) {
                $multiplier = $blackjack ? 2.5 : 2;
                $this->bank += (int)($bet * $multiplier);
                $this->roundResult[$index] = 'Vinst';
            } elseif ($score === $dealerScore) {
                $this->bank += $bet;
                $this->roundResult[$index] = 'Oavgjort';
            } else {
                $this->roundResult[$index] = 'Förlust';
            }
        }
    }

    private function allPlayersDone(): bool
    {
        foreach ($this->playerHands as $hand) {
            if (!$hand->isStanding() && !$hand->isBust()) {
                return false;
            }
        }
        return true;
    }

    public function getGameState(): array
    {
        return [
            'playerName' => $this->playerName,
            'playerHands' => $this->getFormattedPlayerHands(),
            'dealerHand' => $this->roundResolved ? $this->dealerHand->getCards() : [$this->dealerHand->getCards()[0]],
            'dealerScore' => $this->dealerHand->getScore(),
            'bank' => $this->bank,
            'roundActive' => $this->isRoundActive,
            'roundResolved' => $this->roundResolved,
        ];
    }

    private function getFormattedPlayerHands(): array
    {
        $result = [];
        foreach ($this->playerHands as $hand) {
            $result[] = [
                'cards' => $hand->getCards(),
                'score' => $hand->getScore(),
                'standing' => $hand->isStanding(),
                'bust' => $hand->isBust(),
                'bet' => $hand->getBet(),
                'canSplit' => $hand->canSplit(),
            ];
        }
        return $result;
    }

    // Ny getter för rundans resultat
    public function getRoundResult(): array
    {
        return $this->roundResult;
    }
}
