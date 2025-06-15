<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'ProductController');
    }

    public function testShowAllProduct(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product/show');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testShowProductByIdNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product/show/999999');

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);
        $this->assertJson($content);
        $this->assertEquals('{}', $content);
    }
}
