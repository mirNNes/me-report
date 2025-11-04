<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    private function requestPage(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);
    }

    public function testIndexPage(): void
    {
        $this->requestPage('/library');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('html');
    }

    public function testListPage(): void
    {
        $this->requestPage('/library/books');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('html');
    }

    public function testNewPage(): void
    {
        $this->requestPage('/library/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testShowPageNotFound(): void
    {
        $this->requestPage('/library/999999');
        $this->assertResponseStatusCodeSame(404);
    }
}
