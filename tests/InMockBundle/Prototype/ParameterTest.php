<?php

namespace Tests\InMockBundle\Prototype;

use InMockBundle\Prototype\Parameter;
use JsonSchema\Validator;

/**
 * Class ParameterTest
 * @package Tests\InMockBundle\Prototype
 */
class ParameterTest extends AbstractPrototypeTestCase
{
    public function testGettersAndSetters()
    {
        $parameter = new Parameter;
        $parameter->setName('test')
            ->setPattern('/[A-Z]/')
            ->setType('int');

        $this->assertEquals('test', $parameter->getName());
        $this->assertEquals('/[A-Z]/', $parameter->getPattern());
        $this->assertEquals('int', $parameter->getType());
    }

    public function testValidate()
    {
        $json = file_get_contents(__DIR__ . '/../../Resources/prototypes/parameter.json');

        $jsonSchemaValidator = new Validator();
        $jsonSchemaValidator->validate(
            $json,
            __DIR__ . '/../../../src/InMockBundle/Resources/schemas/parameter.schema.json'
        );

        $this->assertTrue($jsonSchemaValidator->isValid());
    }

    public function testDeserialize()
    {
        $json = file_get_contents(__DIR__ . '/../../Resources/prototypes/parameter.json');

        /** @var Parameter $parameter */
        $parameter = $this->serializer->deserialize($json, Parameter::class, 'json');

        $this->assertEquals('id', $parameter->getName());
        $this->assertEquals('int', $parameter->getType());
        $this->assertEquals('[0-9]+', $parameter->getPattern());
    }
}
