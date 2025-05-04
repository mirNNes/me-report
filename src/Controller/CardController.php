<?php

namespace App\Controller;

use App\Game\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    #[Route('/card', name: 'card_card')]
    public function card(): Response
    {
        return $this->render('card/card.html.twig');
    }

    #[Route('/card/deck', name: 'card_deck')]
    public function deck(SessionInterface $session): Response
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        } else {
            $deck = $session->get('deck');
        }

        return $this->render('card/deck.html.twig', [
            'deck' => $deck->getDeck(),
        ]);
    }

    #[Route('/card/deck/shuffle', name: 'card_deck_shuffle')]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = $session->get('deck', new DeckOfCards());

        $deck->shuffle();

        $session->set('deck', $deck);

        return $this->render('card/deck.html.twig', [
            'deck' => $deck->getDeck(),
        ]);
    }

    #[Route('/card/deck/draw/{number}', name: 'card_deck_draw')]
    public function draw(int $number = 1, SessionInterface $session): Response
    {
        $deck = $session->get('deck', new DeckOfCards());

        $drawnCards = $deck->draw($number);

        $session->set('deck', $deck);

        return $this->render('card/draw.html.twig', [
            'drawnCards' => $drawnCards,
            'remaining' => $deck->count(),
        ]);
    }

    #[Route('/session', name: 'session')]
    public function session(SessionInterface $session): Response
    {
        $sessionData = $session->all();

        return $this->render('card/session.html.twig', [
            'sessionData' => $sessionData
        ]);
    }

    #[Route('/session/delete', name: 'session_delete')]
    public function deleteSession(SessionInterface $session): Response
    {
        $session->clear();

        $this->addFlash('success', 'Sessionen har raderats.');

        return $this->redirectToRoute('session');
    }


}
