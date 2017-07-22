<?php

namespace Tests\InMockBundle\Prototype;

use InMockBundle\Prototype\Parameter;
use InMockBundle\Prototype\Endpoint;
use JsonSchema\Validator;

/**
 * Class EndpointTest
 * @package Tests\InMockBundle\Prototype
 */
class EndpointTest extends AbstractPrototypeTestCase
{
    public function testGettersAndSetters()
    {
        $parameter = new Parameter;
        $queryParameter1 = new Parameter;
        $queryParameter2 = new Parameter;

        $endpoint = new Endpoint;
        $endpoint->setMethod('get')
            ->setPath('/test')
            ->setParameters([$parameter])
            ->setQueryParameters([$queryParameter1, $queryParameter2]);

        $this->assertEquals('get', $endpoint->getMethod());
        $this->assertEquals('/test', $endpoint->getPath());
        $this->assertEquals(1, count($endpoint->getParameters()));
        $this->assertEquals($parameter, $endpoint->getParameters()[0]);
        $this->assertEquals(2, count($endpoint->getQueryParameters()));
        $this->assertEquals($queryParameter1, $endpoint->getQueryParameters()[0]);
        $this->assertEquals($queryParameter2, $endpoint->getQueryParameters()[1]);
    }

    public function testValidate()
    {
        $json = file_get_contents(__DIR__ . '/../../Resources/prototypes/endpoint.json');

        $jsonSchemaValidator = new Validator;
        $jsonSchemaValidator->validate(
            $json,
            __DIR__ . '/../../../src/InMockBundle/Resources/schemas/endpoint.schema.json'
        );

        $this->assertTrue($jsonSchemaValidator->isValid());
    }

    public function testDeserialize()
    {
        $json = file_get_contents(__DIR__ . '/../../Resources/prototypes/endpoint.json');

        /** @var Endpoint $endpoint */
        $endpoint = $this->serializer->deserialize($json, Endpoint::class, 'json');

        $this->assertEquals('/cats/{id}', $endpoint->getPath());
        $this->assertEquals('get', $endpoint->getMethod());
        $this->assertEquals(1, count($endpoint->getParameters()));
        $this->assertInstanceOf(Parameter::class, $endpoint->getParameters()[0]);
        $this->assertEquals(2, count($endpoint->getQueryParameters()));
        $this->assertInstanceOf(Parameter::class, $endpoint->getQueryParameters()[0]);
        $this->assertInstanceOf(Parameter::class, $endpoint->getQueryParameters()[1]);
    }
}
