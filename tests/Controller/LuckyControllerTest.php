<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class LuckyControllerTest extends WebTestCase
{

    private function getLuckyNumberContent(KernelBrowser $client): array
    {
        $crawler = $client->request('GET', '/lucky');

        $this->assertResponseIsSuccessful();

        $number = $crawler->filter('h1 span')->text();
        $content = $client->getResponse()->getContent();

        return [(int) $number, $content];
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        
        [$number, $content] = $this->getLuckyNumberContent($client);

        $this->assertIsNumeric($number);
        $this->assertGreaterThanOrEqual(1, $number);
        $this->assertLessThanOrEqual(100, $number);
        $this->assertIsString($content);
        $this->assertMatchesRegularExpression(
            '/\/assets\/img\/(unicorn|rainbow|confeti|clover)-[a-z0-9]+\.(png|jpg|jpeg|gif)/i',
            $content
        );
    }

    public function testMultipleRequestsReturnDifferentNumbers(): void
    {
        $client = static::createClient();
        $numbers = [];

        for ($i = 0; $i < 5; $i++) {
            [$number] = $this->getLuckyNumberContent($client); 
            $numbers[] = $number;
        }

        $uniqueNumbers = array_unique($numbers);
        $this->assertGreaterThan(1, count($uniqueNumbers));
    }

    public function testImageFileExtensions(): void
    {
        $client = static::createClient();
        
        [, $content] = $this->getLuckyNumberContent($client);
        $this->assertMatchesRegularExpression('/\.(png|jpg|jpeg|gif)/i', $content);
    }
}
