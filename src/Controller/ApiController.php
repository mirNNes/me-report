<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
}

