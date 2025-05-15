<?php

namespace App\Controller;

use App\Game\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'routes' => [
                ['name' => 'Dagens citat', 'path' => '/api/quote'],
                ['name' => 'Visa sorterad kortlek', 'path' => '/api/deck'],
                ['name' => 'Blanda kortlek', 'path' => '/api/deck/shuffle'],
                ['name' => 'Dra ett kort', 'path' => '/api/deck/draw'],
                ['name' => 'Dra flera kort', 'path' => '/api/deck/draw/3'],
            ],
        ]);
    }

    #[Route('/api/quote', name: 'api_quote')]
    public function quote(): JsonResponse
    {
        $quotes = [
            "It’s not rocket science, this HTML stuff... it’s harder.",
            "Why do programmers prefer dark mode? Because light attracts bugs.",
            "The code didn’t work. So I did what all the pros do – restarted the computer and grabbed a cup of coffee.",
            "Billy said he was gonna debug... he hit the computer with a wrench.",
            "When it says '404 – not found', I’m pretty sure it’s just my motivation that’s missing."
        ];

        $quote = $quotes[array_rand($quotes)];
        $now = new \DateTime();

        return $this->json([
            'quote' => $quote,
            'date' => $now->format('Y-m-d'),
            'timestamp' => $now->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('/api/deck', name: 'api_deck')]
    public function deck(): JsonResponse
    {
        $deck = new DeckOfCards();
        $cards = array_map(fn ($card) => $card->getAsString(), $deck->getDeck());

        return $this->json([
            'deck' => $cards,
        ]);
    }

    #[Route('/api/deck/shuffle', name: 'api_deck_shuffle', methods: ['POST'])]
    public function shuffle(SessionInterface $session): JsonResponse
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);

        $cards = array_map(fn ($card) => $card->getAsString(), $deck->getDeck());

        return $this->json([
            'deck' => $cards,
            'message' => 'Kortleken är blandad och sparad i sessionen.',
        ]);
    }

    #[Route('/api/deck/draw', name: 'api_deck_draw_one', methods: ['POST'])]
    public function drawOne(SessionInterface $session): JsonResponse
    {
        return $this->drawCards(1, $session);
    }

    #[Route('/api/deck/draw/{number}', name: 'api_deck_draw_many', methods: ['POST'])]
    public function draw(int $number, SessionInterface $session): JsonResponse
    {
        return $this->drawCards($number, $session);
    }

    private function drawCards(int $number, SessionInterface $session): JsonResponse
    {
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck', new DeckOfCards());
        $cardsDrawn = $deck->draw($number);
        $session->set('deck', $deck);

        $drawn = array_map(fn ($card) => $card->getAsString(), $cardsDrawn);

        return $this->json([
            'drawn_cards' => $drawn,
            'remaining_cards' => $deck->count(),
        ]);
    }

    #[Route('/api/game21', name: 'api_game21')]
public function game21Status(SessionInterface $session): JsonResponse
{
    $game = $session->get('game21');

    if (!$game) {
        return $this->json([
            'message' => 'Spelet har inte startat.'
        ]);
    }

    $state = $game->getGameState();

    $playerHand = array_map(fn($card) => $card->getAsString(), $state['playerHand']);
    $bankHand = array_map(fn($card) => $card->getAsString(), $state['bankHand']);

    return $this->json([
        'player_hand' => $playerHand,
        'player_score' => $state['playerScore'],
        'bank_hand' => $bankHand,
        'bank_score' => $state['bankScore'],
        'game_over' => $state['gameOver'],
        'winner' => $state['winner'],
    ]);
}


}
