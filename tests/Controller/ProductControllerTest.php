<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProductRepository;

class ProductControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/product');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'ProductController');
    }

    public function testShowAllProduct()
    {
        $client = static::createClient();
        $client->request('GET', '/product/show');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testShowProductByIdNotFound()
    {
        $client = static::createClient();
        $client->request('GET', '/product/show/999999');
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
        $this->assertEquals('{}', $client->getResponse()->getContent());
    }
}
