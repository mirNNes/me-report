<?php

namespace App\Controller;

use App\Game\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function deck(SessionInterface $session): Response
    {
        // Kontrollera om kortleken finns i sessionen, om inte, skapa en ny
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck); // Spara kortleken i sessionen
        } else {
            $deck = $session->get('deck'); // Hämta kortleken från sessionen
        }

        return $this->render('card/deck.html.twig', [
            'deck' => $deck->getDeck(),
        ]);
    }

    // Blanda kortleken
    #[Route('/card/deck/shuffle', name: 'card_deck_shuffle')]
    public function shuffle(SessionInterface $session): Response
    {
        // Hämta kortleken från sessionen
        $deck = $session->get('deck', new DeckOfCards()); // Skapa ny om det inte finns en

        // Blanda kortleken
        $deck->shuffle();

        // Spara den blandade kortleken i sessionen
        $session->set('deck', $deck);

        return $this->render('card/deck.html.twig', [
            'deck' => $deck->getDeck(),
        ]);
    }

    // Dra ett eller flera kort
    // Dra ett eller flera kort
    #[Route('/card/deck/draw/{number}', name: 'card_deck_draw')]
    public function draw(int $number = 1, SessionInterface $session): Response
    {
        // Hämta kortleken från sessionen
        $deck = $session->get('deck', new DeckOfCards()); // Skapa ny om det inte finns en

        // Dra kort från kortleken
        $drawnCards = $deck->draw($number);

        // Spara den uppdaterade kortleken i sessionen
        $session->set('deck', $deck);

        return $this->render('card/draw.html.twig', [
            'drawnCards' => $drawnCards,
            'remaining' => $deck->count(),
        ]);
    }

    #[Route('/session', name: 'session')]
    public function session(SessionInterface $session): Response
    {
        // Hämta all sessionsdata
        $sessionData = $session->all();

        return $this->render('card/session.html.twig', [
            'sessionData' => $sessionData
        ]);
    }

    #[Route('/session/delete', name: 'session_delete')]
    public function deleteSession(SessionInterface $session): Response
    {
        $session->clear(); // Töm sessionen

        $this->addFlash('success', 'Sessionen har raderats.');

        return $this->redirectToRoute('session');
    }


}
