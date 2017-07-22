<?php

namespace Tests\InMockBundle\Service;

use InMockBundle\Entity\Parameter;
use InMockBundle\Entity\Request;
use InMockBundle\Exception\InvalidRoutesException;
use InMockBundle\Service\RouterService;
use JMS\Serializer\Serializer;
use JsonSchema\Validator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RouterServiceTest
 * @package Tests\InMockBundle\Service
 */
class RouterServiceTest extends WebTestCase
{
    /** @var ContainerInterface $container */
    private $container;

    /** @var RouterService $router */
    private $router;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->container = $kernel->getContainer();

        $this->router = $this->container->get('inmock.service.router');
    }

    public function testGetRouteCollectionFromFileWithInvalidJson()
    {
        $this->expectException(InvalidRoutesException::class);

        $this->router->addRoutesFromFile(
            __DIR__ . '/../../Resources/prototypes/route_collection.invalid_json.json',
            'json'
        );
    }

    public function testGetRouteCollectionFromFileWithInvalidSchema()
    {
        $this->expectException(InvalidRoutesException::class);

        $this->router->addRoutesFromFile(
            __DIR__ . '/../../Resources/prototypes/route_collection.invalid_schema.json',
            'json'
        );
    }

    public function testGetRouteForRequest()
    {
        $parameter = new Parameter;
        $parameter->setName('color')
            ->setValue('orange');

        $request = new Request;
        $request->setMethod('get')
            ->setPath('/cats/123')
            ->setQueryParameters([$parameter]);

        $route = $this->router->getRouteForRequest($request);

        $this->assertEquals(200, $route->getResponse()->getStatusCode());
        $this->assertEquals('get_cat.json.twig', $route->getResponse()->getTemplate());
        $this->assertEquals('get', $route->getEndpoint()->getMethod());
        $this->assertEquals('/cats/{id}', $route->getEndpoint()->getPath());
    }

    public function testGetRouteForRequestWithMissingParameter()
    {
        $request = new Request;
        $request->setMethod('get')
            ->setPath('/cats/123');

        $route = $this->router->getRouteForRequest($request);

        $this->assertEquals(null, $route);
    }

    public function testGetRouteForRequestWithNotMatchingMethod()
    {
        $parameter = new Parameter;
        $parameter->setName('color')
            ->setValue('orange');

        $request = new Request;
        $request->setMethod('post')
            ->setPath('/cats/123')
            ->setQueryParameters([$parameter]);

        $route = $this->router->getRouteForRequest($request);

        $this->assertEquals(null, $route);
    }

    public function testGetRouteForRequestWithNotMatchingPath()
    {
        $parameter = new Parameter;
        $parameter->setName('color')
            ->setValue('orange');

        $request = new Request;
        $request->setMethod('get')
            ->setPath('/cat/123')
            ->setQueryParameters([$parameter]);

        $route = $this->router->getRouteForRequest($request);

        $this->assertEquals(null, $route);
    }

    public function testGetRouteForRequestWithOneExtraQueryParameter()
    {
        $parameter = new Parameter;
        $parameter->setName('color')
            ->setValue('orange');

        $request = new Request;
        $request->setMethod('delete')
            ->setPath('/cats/123')
            ->setQueryParameters([$parameter]);

        $route = $this->router->getRouteForRequest($request);

        $this->assertEquals(204, $route->getResponse()->getStatusCode());
        $this->assertEquals(null, $route->getResponse()->getTemplate());
        $this->assertEquals('delete', $route->getEndpoint()->getMethod());
        $this->assertEquals('/cats/{id}', $route->getEndpoint()->getPath());
    }

    public function testGetRouteForRequestWithExactlyZeroQueryParameters()
    {
        $request = new Request;
        $request->setMethod('delete')
            ->setPath('/cats/123');

        $route = $this->router->getRouteForRequest($request);

        $this->assertEquals(204, $route->getResponse()->getStatusCode());
        $this->assertEquals(null, $route->getResponse()->getTemplate());
        $this->assertEquals('delete', $route->getEndpoint()->getMethod());
        $this->assertEquals('/cats/{id}', $route->getEndpoint()->getPath());
    }

    public function testGetRouteForRequestWithoutRoutes()
    {
        // We need to instantiate the class in this situation because the services loader is always including a routes
        // file, this way we can avoid it.

        /** @var Serializer $serializer */
        $serializer = $this->container->get('jms_serializer');
        /** @var Validator $jsonSchemaValidator */
        $jsonSchemaValidator = $this->container->get('validator.json_schema');

        $router = new RouterService($serializer, $jsonSchemaValidator);

        $request = new Request;
        $request->setMethod('delete')
            ->setPath('/cats/123');

        $route = $router->getRouteForRequest($request);

        $this->assertEquals(null, $route);
    }
}
