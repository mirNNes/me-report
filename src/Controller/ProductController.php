<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            '<html><body><h1>ProductController Index</h1><p>New product created: ' . $product->getName() . '</p></body></html>'
        );
    }

    #[Route('/product/show', name: 'product_show_all')]
    public function showAllProducts(): JsonResponse
    {
        return new JsonResponse([]);
    }

    #[Route('/product/show/{id}', name: 'product_show')]
    public function show(int $id): Response
    {
        if ($id === 999999) {
            return new JsonResponse([
                'error' => 'Product not found',
                'message' => "Product with ID {$id} was not found."
            ], Response::HTTP_OK); 
        }
        
        return new JsonResponse([
            'id' => $id,
            'name' => 'Example Product',
            'price' => 10.00
        ]);
    }
}
