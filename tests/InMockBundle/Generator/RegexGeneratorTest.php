<?php

namespace Tests\InMockBundle\Generator;

use InMockBundle\Exception\InvalidEndpointException;
use InMockBundle\Generator\RegexGenerator;
use InMockBundle\Prototype\Endpoint;
use InMockBundle\Prototype\Parameter;
use PHPUnit\Framework\TestCase;

/**
 * Class RegexGeneratorTest
 * @package Tests\InMockBundle\Generator
 */
class RegexGeneratorTest extends TestCase
{
    public function testGetRegex()
    {
        $parameter = new Parameter;
        $parameter->setName('id')
            ->setType('int');

        $endpoint = new Endpoint;
        $endpoint->setMethod('get')
            ->setPath('/test/{id}')
            ->setParameters([$parameter]);

        $pattern = RegexGenerator::getRegex($endpoint);

        $this->assertEquals('`^/test/[0-9]+$`', $pattern);
    }

    public function testGetRegexWithInvalidEndpoint()
    {
        $this->expectException(InvalidEndpointException::class);

        $endpoint = new Endpoint;
        $endpoint->setMethod('get')
            ->setPath('/test/{id}');

        RegexGenerator::getRegex($endpoint);
    }

    public function testGetParameterRegexWithType()
    {
        $parameter = new Parameter;
        $parameter->setName('id')
            ->setType('int');

        $endpoint = new Endpoint;
        $endpoint->setMethod('get')
            ->setPath('/test/{id}')
            ->setParameters([$parameter]);

        $pattern = RegexGenerator::getParameterRegex('id', $endpoint);

        $this->assertEquals('[0-9]+', $pattern);
    }

    public function testGetParameterRegexWithPattern()
    {
        $originalPattern = '[a-z]{1}[A-Z]+';

        $parameter = new Parameter;
        $parameter->setName('id')
            ->setPattern($originalPattern);

        $endpoint = new Endpoint;
        $endpoint->setMethod('get')
            ->setPath('/test/{id}')
            ->setParameters([$parameter]);

        $pattern = RegexGenerator::getParameterRegex('id', $endpoint);

        $this->assertEquals($originalPattern, $pattern);
    }

    public function testGetTypeRegexWithInt()
    {
        $pattern = RegexGenerator::getTypeRegex('int');
        $this->assertEquals('[0-9]+', $pattern);
    }

    public function testGetTypeRegexWithBoolean()
    {
        $pattern = RegexGenerator::getTypeRegex('boolean');
        $this->assertEquals('true|false', $pattern);
    }

    public function testGetTypeRegexWithString()
    {
        $pattern = RegexGenerator::getTypeRegex('string');
        $this->assertEquals('.+', $pattern);
    }

    public function testGetTypeRegexWithInvalidType()
    {
        $pattern = RegexGenerator::getTypeRegex('invalidtype');
        $this->assertEquals('.+', $pattern);
    }
}
