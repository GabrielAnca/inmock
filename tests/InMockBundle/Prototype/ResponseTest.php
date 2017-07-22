<?php

namespace Tests\InMockBundle\Prototype;

use InMockBundle\Prototype\Response;
use JsonSchema\Validator;

/**
 * Class ResponseTest
 * @package Tests\InMockBundle\Prototype
 */
class ResponseTest extends AbstractPrototypeTestCase
{
    public function testGettersAndSetters()
    {
        $response = new Response;
        $response->setStatusCode(200)
            ->setTemplate('test.html.twig');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('test.html.twig', $response->getTemplate());
    }

    public function testValidate()
    {
        $json = file_get_contents(__DIR__ . '/../../Resources/prototypes/response.json');

        $jsonSchemaValidator = new Validator();
        $jsonSchemaValidator->validate(
            $json,
            __DIR__ . '/../../../src/InMockBundle/Resources/schemas/response.schema.json'
        );

        $this->assertTrue($jsonSchemaValidator->isValid());
    }

    public function testDeserialize()
    {
        $json = file_get_contents(__DIR__ . '/../../Resources/prototypes/response.json');

        /** @var Response $response */
        $response = $this->serializer->deserialize($json, Response::class, 'json');

        $this->assertEquals('get_cat.json.twig', $response->getTemplate());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
