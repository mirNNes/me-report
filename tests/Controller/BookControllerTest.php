<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public function testIndexPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/library');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('html');
    }

    public function testListPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/library/books');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('html');
    }

    public function testNewPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/library/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testShowPageNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/library/999999');

        $this->assertResponseStatusCodeSame(404);
    }
}
