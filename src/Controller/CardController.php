<?php

namespace App\Controller;

use App\Game\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    // Landningssida för kortspelet
    #[Route('/card', name: 'card_card')]
    public function card(): Response
    {
        return $this->render('card/card.html.twig');
    }

    // Visa kortleken
    #[Route('/card/deck', name: 'card_deck')]
    public function deck(): Response
    {
        $deck = new DeckOfCards();

        return $this->render('card/deck.html.twig', [
            'deck' => $deck->getDeck(),
        ]);
    }

    // Blanda kortleken
    #[Route('/card/deck/shuffle', name: 'card_deck_shuffle')]
    public function shuffle(): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();

        return $this->render('card/deck.html.twig', [
            'deck' => $deck->getDeck(),
        ]);
    }

    // Dra ett eller flera kort
    #[Route('/card/deck/draw/{number}', name: 'card_deck_draw')]
    public function draw(int $number = 1): Response
    {
        $deck = new DeckOfCards();
        $drawnCards = $deck->draw($number);

        return $this->render('card/draw.html.twig', [
            'drawnCards' => $drawnCards,
            'remaining' => $deck->count(),
        ]);
    }
}
