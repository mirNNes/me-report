<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController extends AbstractController
{
    #[Route('/lucky', name: 'lucky')]
    public function index(): Response
    {
        $number = random_int(1, 100);
        $images = [
            'img/unicorn.png',
            'img/rainbow.png',
            'img/confeti.png',
            'img/clover.png',
        ];
        $randomImage = $images[array_rand($images)];

        return $this->render('lucky/index.html.twig', [
            'number' => $number,
            'image' => $randomImage,
        ]);
    }
}
