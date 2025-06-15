<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testQuote()
    {
        $client = static::createClient();
        $client->request('GET', '/api/quote');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('quote', $data);
        $this->assertArrayHasKey('date', $data);
        $this->assertArrayHasKey('timestamp', $data);

        $this->assertIsString($data['quote']);
        $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2}/', $data['date']);
    }

    public function testGetAllBooks()
    {
        $client = static::createClient();
        $client->request('GET', '/api/library/books');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($data);
        if (count($data) > 0) {
            $this->assertArrayHasKey('id', $data[0]);
            $this->assertArrayHasKey('title', $data[0]);
            $this->assertArrayHasKey('isbn', $data[0]);
            $this->assertArrayHasKey('author', $data[0]);
            $this->assertArrayHasKey('image', $data[0]);
        }
    }

    public function testGetBookByIsbnNotFound()
    {
        $client = static::createClient();
        $client->request('GET', '/api/library/book/nonexistent-isbn');

        $this->assertResponseIsSuccessful();
        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue($data === null || $data === []);

    }
}
