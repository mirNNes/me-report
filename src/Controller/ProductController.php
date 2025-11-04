<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ProductManager;

class ProductController extends AbstractController
{
    private $productManager;

    public function __construct(ProductManager $productManager)
    {
        $this->productManager = $productManager;
    }

    public function index(): Response
    {
        $product = $this->productManager->createNewProduct();

        return new Response('New product created: ' . $product->getName());
    }
}
