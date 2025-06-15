<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CardControllerTest extends WebTestCase
{
    public function testCardPage()
    {
        $client = static::createClient();
        $client->request('GET', '/card');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('html');
    }

    public function testDeckPage()
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('html');
    }

    public function testShuffleDeck()
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/shuffle');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('html');
    }

    public function testDrawCards()
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/draw/2');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('html');
    }

    public function testSessionPage()
    {
        $client = static::createClient();
        $client->request('GET', '/session');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('html');
    }

    public function testDeleteSession()
    {
        $client = static::createClient();
        $client->request('GET', '/session/delete');

        $this->assertResponseRedirects('/session');
    }
}
