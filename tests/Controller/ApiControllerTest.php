<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testQuote(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/quote');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $json = $client->getResponse()->getContent();
        $this->assertIsString($json);

        $data = json_decode($json, true);
        $this->assertIsArray($data);

        $this->assertArrayHasKey('quote', $data);
        $this->assertArrayHasKey('date', $data);
        $this->assertArrayHasKey('timestamp', $data);

        $this->assertIsString($data['quote']);
        $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2}/', $data['date']);
        $this->assertIsString($data['timestamp']);
        $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $data['timestamp']);
    }

    public function testGetAllBooks(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/library/books');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $json = $client->getResponse()->getContent();
        $this->assertIsString($json);

        $data = json_decode($json, true);
        $this->assertIsArray($data);

        if (count($data) > 0) {
            $this->assertArrayHasKey('id', $data[0]);
            $this->assertArrayHasKey('title', $data[0]);
            $this->assertArrayHasKey('isbn', $data[0]);
            $this->assertArrayHasKey('author', $data[0]);
            $this->assertArrayHasKey('image', $data[0]);
        }
    }

    public function testGetBookByIsbnNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/library/book/nonexistent-isbn');

        $this->assertResponseIsSuccessful();

        $json = $client->getResponse()->getContent();
        $this->assertIsString($json);

        $data = json_decode($json, true);

        $this->assertTrue(is_null($data) || (is_array($data) && empty($data)));
    }

    public function testInvalidEndpoint(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/invalid-endpoint');

        $this->assertResponseStatusCodeSame(404);
    }
}
