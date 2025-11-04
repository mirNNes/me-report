<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LuckyControllerTest extends WebTestCase
{
    private function getLuckyNumberContent(): array
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/lucky');

        $this->assertResponseIsSuccessful();

        $number = $crawler->filter('h1 span')->text();
        $content = $client->getResponse()->getContent();

        return [(int) $number, $content];
    }

    public function testIndex(): void
    {
        [$number, $content] = $this->getLuckyNumberContent();

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
        $numbers = [];

        for ($i = 0; $i < 5; $i++) {
            [$number] = $this->getLuckyNumberContent();
            $numbers[] = $number;
        }

        $uniqueNumbers = array_unique($numbers);
        $this->assertGreaterThan(1, count($uniqueNumbers));
    }

    public function testImageFileExtensions(): void
    {
        [, $content] = $this->getLuckyNumberContent();
        $this->assertMatchesRegularExpression('/\.(png|jpg|jpeg|gif)/i', $content);
    }
}
