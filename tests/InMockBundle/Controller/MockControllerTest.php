<?php

namespace Tests\InMockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class MockControllerTest
 * @package Tests\InMockBundle\Controller
 */
class MockControllerTest extends WebTestCase
{
    public function testServe()
    {
        $client = static::createClient();

        $client->request('GET', '/cats/123?color=orange');

        $expectedJson = file_get_contents(__DIR__ . '/../../Resources/templates/get_cat.json.twig');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($expectedJson, $client->getResponse()->getContent());
    }

    public function testServeWithNoContentResponse()
    {
        $client = static::createClient();

        $client->request('DELETE', '/cats/123');

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
        $this->assertEquals('', $client->getResponse()->getContent());
    }

    public function testServeWithNotFoundException()
    {
        $client = static::createClient();

        $client->request('GET', '/cats/123');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testServeWithNonExistingTemplate()
    {
        $client = static::createClient();

        $client->request('POST', '/cats/123');

        $this->assertEquals(500, $client->getResponse()->getStatusCode());
    }
}
