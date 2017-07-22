<?php

namespace Tests\InMockBundle\Prototype;

use InMockBundle\Prototype\Route;
use InMockBundle\Prototype\RouteCollection;
use JsonSchema\Validator;

/**
 * Class RouteCollectionTest
 * @package Tests\InMockBundle\Prototype
 */
class RouteCollectionTest extends AbstractPrototypeTestCase
{
    public function testGettersAndSetters()
    {
        $route1 = new Route;
        $route2 = new Route;

        $routeCollection = new RouteCollection;
        $routeCollection->setRoutes([$route1, $route2]);

        $this->assertEquals(2, count($routeCollection->getRoutes()));
        $this->assertEquals($route1, $routeCollection->getRoutes()[0]);
        $this->assertEquals($route2, $routeCollection->getRoutes()[1]);
    }

    public function testValidate()
    {
        $json = file_get_contents(__DIR__ . '/../../Resources/prototypes/route_collection.json');

        $jsonSchemaValidator = new Validator();
        $jsonSchemaValidator->validate(
            $json,
            __DIR__ . '/../../../src/InMockBundle/Resources/schemas/route_collection.schema.json'
        );

        $this->assertTrue($jsonSchemaValidator->isValid());
    }

    public function testDeserialize()
    {
        $json = file_get_contents(__DIR__ . '/../../Resources/prototypes/route_collection.json');

        /** @var RouteCollection $routeCollection */
        $routeCollection = $this->serializer->deserialize($json, RouteCollection::class, 'json');

        $this->assertEquals(3, count($routeCollection->getRoutes()));
        $this->assertInstanceOf(Route::class, $routeCollection->getRoutes()[0]);
        $this->assertInstanceOf(Route::class, $routeCollection->getRoutes()[1]);
        $this->assertInstanceOf(Route::class, $routeCollection->getRoutes()[2]);
    }
}
