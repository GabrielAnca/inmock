<?php

namespace Tests\InMockBundle\Prototype;

use InMockBundle\Prototype\Endpoint;
use InMockBundle\Prototype\Response;
use InMockBundle\Prototype\Route;
use JsonSchema\Validator;

/**
 * Class RouteTest
 * @package Tests\InMockBundle\Prototype
 */
class RouteTest extends AbstractPrototypeTestCase
{
    public function testGettersAndSetters()
    {
        $endpoint = new Endpoint;
        $response = new Response;

        $route = new Route;
        $route->setEndpoint($endpoint)
            ->setResponse($response)
            ->setStrict(true);

        $this->assertEquals($endpoint, $route->getEndpoint());
        $this->assertEquals($response, $route->getResponse());
        $this->assertEquals(true, $route->isStrict());
    }

    public function testValidate()
    {
        $json = file_get_contents(__DIR__ . '/../../Resources/prototypes/route.json');

        $jsonSchemaValidator = new Validator();
        $jsonSchemaValidator->validate(
            $json,
            __DIR__ . '/../../../src/InMockBundle/Resources/schemas/route.schema.json'
        );

        $this->assertTrue($jsonSchemaValidator->isValid());
    }

    public function testDeserialize()
    {
        $json = file_get_contents(__DIR__ . '/../../Resources/prototypes/route.json');

        /** @var Route $route */
        $route = $this->serializer->deserialize($json, Route::class, 'json');

        $this->assertInstanceOf(Endpoint::class, $route->getEndpoint());
        $this->assertEquals(true, $route->isStrict());
        $this->assertInstanceOf(Response::class, $route->getResponse());
    }
}
