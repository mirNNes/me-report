<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LuckyControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/lucky');

        $this->assertResponseIsSuccessful();

        // Kontrollera att lucky number är numerisk och inom intervall
        $number = $crawler->filter('h1 span')->text();
        $this->assertIsNumeric($number, 'Lucky number should be numeric');
        $this->assertGreaterThanOrEqual(1, (int) $number, 'Lucky number should be at least 1');
        $this->assertLessThanOrEqual(100, (int) $number, 'Lucky number should be at most 100');

        // Kontrollera att rätt bildfil inkluderas
        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);
        $this->assertMatchesRegularExpression(
            '/\/assets\/img\/(unicorn|rainbow|confeti|clover)-[a-z0-9]+\.(png|jpg|jpeg|gif)/i',
            $content,
            'Expected a themed image file (with hash) to be included in the page'
        );
    }

    public function testMultipleRequestsReturnDifferentNumbers(): void
    {
        $client = static::createClient();
        $numbers = [];

        // Gör 5 förfrågningar och spara talen
        for ($i = 0; $i < 5; $i++) {
            $crawler = $client->request('GET', '/lucky');
            $this->assertResponseIsSuccessful();

            $number = $crawler->filter('h1 span')->text();
            $this->assertIsNumeric($number);
            $numbers[] = (int) $number;
        }

        // Kontrollera att det finns minst två olika tal bland svaren
        $uniqueNumbers = array_unique($numbers);
        $this->assertGreaterThan(1, count($uniqueNumbers), 'Expected variation in lucky numbers across requests');
    }

    public function testImageFileExtensions(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/lucky');

        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);
        $this->assertMatchesRegularExpression('/\.(png|jpg|jpeg|gif)/i', $content);
    }
}
