<?php

namespace App\Controller;

use App\Game\Game21\Game21;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game/intro', name: 'game_intro')]
    public function intro(SessionInterface $session): Response
    {
        $session->remove('game21');
        return $this->render('game/intro.html.twig');
    }

    #[Route('/game/start', name: 'game_start')]
    public function start(SessionInterface $session): Response
    {
        $game = new Game21();
        $game->startGame();
        $session->set('game21', $game);
        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/play', name: 'game_play')]
    public function play(SessionInterface $session): Response
    {
        /** @var Game21|null $game */
        $game = $session->get('game21');
        if (!$game) {
            return $this->redirectToRoute('game_start');
        }

        return $this->render('game/play.html.twig', $game->getGameState());
    }

    #[Route('/game/draw', name: 'game_draw', methods: ['POST'])]
    public function draw(SessionInterface $session): Response
    {
        /** @var Game21|null $game */
        $game = $session->get('game21');
        if ($game) {
            $game->playerDrawAndCheck();
            $session->set('game21', $game);
        }

        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/stay', name: 'game_stay', methods: ['POST'])]
    public function stay(SessionInterface $session): Response
    {
        /** @var Game21|null $game */
        $game = $session->get('game21');
        if ($game) {
            $game->bankTurn();
            $session->set('game21', $game);
        }

        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/documentation', name: 'game_documentation')]
    public function documentation(): Response
    {
        return $this->render('game/documentation.html.twig');
    }
}
