<?php

namespace Tests\InMockBundle\Factory;

use InMockBundle\Entity\Parameter;
use InMockBundle\Factory\RequestFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class RequestFactoryTest
 * @package Tests\InMockBundle\Factory
 */
class RequestFactoryTest extends TestCase
{
    public function testBuild()
    {
        $request = RequestFactory::build('get', '/{id}', ['filter' => 'open']);
        $queryParameters = $request->getQueryParameters();

        $this->assertEquals('get', $request->getMethod());
        $this->assertEquals('/{id}', $request->getPath());

        $this->assertCount(1, $queryParameters);
        $this->assertInstanceOf(Parameter::class, $queryParameters[0]);
        $this->assertEquals('filter', $queryParameters[0]->getName());
        $this->assertEquals('open', $queryParameters[0]->getValue());
    }
}
