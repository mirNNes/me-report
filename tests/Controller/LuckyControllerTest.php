<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LuckyControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/lucky');

        $this->assertResponseIsSuccessful();

        $number = $crawler->filter('h1 span')->text();
        $this->assertIsNumeric($number, 'Lucky number should be numeric');
        $this->assertGreaterThanOrEqual(1, (int) $number, 'Lucky number should be at least 1');
        $this->assertLessThanOrEqual(100, (int) $number, 'Lucky number should be at most 100');

        $content = $client->getResponse()->getContent();
        $this->assertMatchesRegularExpression(
            '/\/assets\/img\/(unicorn|rainbow|confeti|clover)-[a-z0-9]+\.(png|jpg|jpeg|gif)/i',
            $content,
            'Expected a themed image file (with hash) to be included in the page'
        );
    }
}
