<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameControllerTest extends WebTestCase
{
    public function testIntroClearsSession(): void
    {
        $client = static::createClient();

        $client->request('GET', '/game/start');
        $client->request('GET', '/game/intro');

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);
        $this->assertStringContainsString('Kortspel: 21', $content);
    }

    public function testStartInitializesGameAndRedirects(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/start');

        $this->assertResponseRedirects('/game/play');
    }

    public function testPlayRedirectsIfNoGame(): void
    {
        $client = static::createClient();

        $client->request('GET', '/game/intro');
        $client->request('GET', '/game/play');

        $this->assertResponseRedirects('/game/start');
    }

    public function testPlayRendersGameState(): void
    {
        $client = static::createClient();

        $client->request('GET', '/game/start');
        $client->request('GET', '/game/play');

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);
        $this->assertStringContainsString('Spelarens hand', $content);
    }

    public function testDrawUpdatesGameAndRedirects(): void
    {
        $client = static::createClient();

        $client->request('GET', '/game/start');
        $client->request('POST', '/game/draw');

        $this->assertResponseRedirects('/game/play');
    }

    public function testStayUpdatesGameAndRedirects(): void
    {
        $client = static::createClient();

        $client->request('GET', '/game/start');
        $client->request('POST', '/game/stay');

        $this->assertResponseRedirects('/game/play');
    }

    public function testDocumentationPageLoads(): void
    {
        $client = static::createClient();

        $client->request('GET', '/game/documentation');

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);
        $this->assertStringContainsString('Dokumentation', $content);
    }
}
