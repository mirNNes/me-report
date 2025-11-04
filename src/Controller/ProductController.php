<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ProductManager;

class ProductController extends AbstractController
{
    private $productManager;

    public function __construct(ProductManager $productManager)
    {
        $this->productManager = $productManager;
    }

    #[Route('/product', name: 'product_index')]
    public function index(): Response
    {
        $product = $this->productManager->createNewProduct();

        return new Response(
            '<html><body><h1>Product Index</h1><p>New product created: ' . $product->getName() . '</p></body></html>'
        );
    }

    #[Route('/product/show/{id}', name: 'product_show')]
    public function show(int $id): Response
    {
        return new Response("Showing product ID: {$id}");
    }
}
