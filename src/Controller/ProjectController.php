<?php

namespace App\Controller;

use App\BlackJack\BlackJack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    #[Route('/', name: 'proj_landing')]
    public function landing(): Response
    {
        return $this->render('project/index.html.twig');
    }

    #[Route('/proj/blackjack', name: 'proj_blackjack_play')]
public function blackjack(SessionInterface $session): Response
{
    $game = $session->get('blackjack');

    if (!$game instanceof BlackJack) {
        return $this->redirectToRoute('proj_blackjack_start');
    }

    return $this->render('project/blackjack.html.twig', [
        'gameState' => $game->getGameState(),
        'roundResult' => $game->getRoundResult(),
    ]);
}


    #[Route('/proj/blackjack/start', name: 'proj_blackjack_start', methods: ['GET', 'POST'])]
public function blackjackStart(Request $request, SessionInterface $session): Response
{
    $numHands = (int) $request->request->get('num_hands', 1);

    if ($numHands < 1 || $numHands > 3) {
        $this->addFlash('error', 'Du måste välja mellan 1 och 3 händer.');
        return $this->redirectToRoute('proj_landing');
    }

    // Skapa en array med dummy-bets för att indikera antalet händer (t.ex. [100, 100, 100])
    $bets = array_fill(0, $numHands, 100);

    $game = new BlackJack("Spelare", 1000);
    $game->startGame($bets); // vi använder bara bets för att skapa rätt antal händer
    $session->set('blackjack', $game);

    return $this->redirectToRoute('proj_blackjack_play');
}



    #[Route('/proj/blackjack/hit/{hand}', name: 'proj_blackjack_hit', methods: ['POST'])]
    public function blackjackHit(SessionInterface $session, int $hand): Response
    {
        $game = $session->get('blackjack');
        if ($game instanceof BlackJack && $game->getGameState()['roundActive']) {
            $game->playerHit($hand);
        }
        return $this->redirectToRoute('proj_blackjack_play');
    }

    #[Route('/proj/blackjack/stand/{hand}', name: 'proj_blackjack_stand', methods: ['POST'])]
    public function blackjackStand(SessionInterface $session, int $hand): Response
    {
        $game = $session->get('blackjack');
        if ($game instanceof BlackJack && $game->getGameState()['roundActive']) {
            $game->playerStand($hand);
        }
        return $this->redirectToRoute('proj_blackjack_play');
    }
}
