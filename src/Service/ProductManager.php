<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductManager
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function createNewProduct(): Product
    {
        $entityManager = $this->doctrine->getManager();

        $product = new Product();
        $product->setName('Keyboard_num_' . rand(1, 9));
        $product->setValue(rand(100, 999));

        $entityManager->persist($product);
        $entityManager->flush();

        return $product;
    }

    public function deleteProductById(int $id): void
    {
        $entityManager = $this->doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new NotFoundHttpException(sprintf(
                'No product found for id %d',
                $id
            ));
        }

        $entityManager->remove($product);
        $entityManager->flush();
    }

    public function updateProduct(int $id, int $value): Product
    {
        $entityManager = $this->doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new NotFoundHttpException(sprintf(
                'No product found for id %d',
                $id
            ));
        }

        $product->setValue($value);
        $entityManager->flush();

        return $product;
    }
}
