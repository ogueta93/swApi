<?php
// tests/Service/SwApiTest.php
namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SwApiTest extends WebTestCase
{
    public function testSimple()
    {
        $client = static::createClient();
        $client->request('POST', '/people');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertInternalType('string', $client->getResponse()->getContent());

        $decodedData = \json_decode($client->getResponse()->getContent(), true);
        $count = $decodedData['count'] ?? 0;
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testWithPageNumberValue()
    {
        $value = 5;

        $client = static::createClient();
        $client->request('POST', '/people', ['page' => $value]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertInternalType('string', $client->getResponse()->getContent());

        $decodedData = \json_decode($client->getResponse()->getContent(), true);
        $count = $decodedData['count'] ?? 0;
        $this->assertGreaterThanOrEqual(1, $count);
    }
}
