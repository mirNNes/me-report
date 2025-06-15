<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CardControllerTest extends WebTestCase
{
    public function testCardPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('html');
    }

    public function testDeckPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('html');
    }

    public function testShuffleDeck(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/shuffle');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('html');
    }

    public function testDrawCards(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/draw/2');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('html');
    }

    public function testSessionPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/session');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('html');
    }

    public function testDeleteSession(): void
    {
        $client = static::createClient();
        $client->request('GET', '/session/delete');

        $this->assertResponseRedirects('/session');
    }
}
