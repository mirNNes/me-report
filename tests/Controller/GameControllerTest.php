<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameControllerTest extends WebTestCase
{
    public function testIntroClearsSession(): void
    {
        $client = static::createClient();

        // Starta spelet för att lägga något i sessionen
        $client->request('GET', '/game/start');

        // Besök intro som ska rensa sessionen
        $client->request('GET', '/game/intro');

        $this->assertResponseIsSuccessful();

        // Kontrollera att sidan innehåller text som finns i intro-sidan
        $this->assertStringContainsString('Kortspel: 21', $client->getResponse()->getContent());
    }

    public function testStartInitializesGameAndRedirects(): void
    {
        $client = static::createClient();

        $client->request('GET', '/game/start');

        // Kontrollera att vi omdirigeras till /game/play efter start
        $this->assertResponseRedirects('/game/play');
    }

    public function testPlayRedirectsIfNoGame(): void
    {
        $client = static::createClient();

        // Rensa session genom att besöka intro
        $client->request('GET', '/game/intro');

        // Försök spela utan ett aktivt spel i session
        $client->request('GET', '/game/play');

        // Ska omdirigeras till start av spelet
        $this->assertResponseRedirects('/game/start');
    }

    public function testPlayRendersGameState(): void
    {
        $client = static::createClient();

        // Starta spelet
        $client->request('GET', '/game/start');
        // Besök spelsidan
        $client->request('GET', '/game/play');

        $this->assertResponseIsSuccessful();

        // Kontrollera att sidan innehåller en text som visar spelstatus
        $this->assertStringContainsString('Spelarens hand', $client->getResponse()->getContent());
    }

    public function testDrawUpdatesGameAndRedirects(): void
    {
        $client = static::createClient();

        $client->request('GET', '/game/start');
        $client->request('POST', '/game/draw');

        // Efter POST till draw ska vi omdirigeras till play
        $this->assertResponseRedirects('/game/play');
    }

    public function testStayUpdatesGameAndRedirects(): void
    {
        $client = static::createClient();

        $client->request('GET', '/game/start');
        $client->request('POST', '/game/stay');

        // Efter POST till stay ska vi omdirigeras till play
        $this->assertResponseRedirects('/game/play');
    }

    public function testDocumentationPageLoads(): void
    {
        $client = static::createClient();

        $client->request('GET', '/game/documentation');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Dokumentation', $client->getResponse()->getContent());
    }
}
