<?php

namespace InMockBundle\Service;

use InMockBundle\Entity\Request;
use InMockBundle\Exception\InvalidRoutesException;
use InMockBundle\Generator\RegexGenerator;
use InMockBundle\Prototype\Endpoint;
use InMockBundle\Prototype\Route;
use InMockBundle\Prototype\RouteCollection;
use JMS\Serializer\Serializer;
use JsonSchema\Validator;

/**
 * Class RouterService
 *
 * @package InMockBundle\Service
 */
class RouterService
{
    const NO_MATCH = 0;
    const MATCH = 1;
    const STRICT_MATCH = 2;

    /**
     * @var Serializer $serializer
     */
    protected $serializer;

    /**
     * @var Validator $jsonSchemaValidator
     */
    protected $jsonSchemaValidator;

    /**
     * @var Route[] $routes
     */
    protected $routes;

    /**
     * RouteResolverService constructor.
     *
     * @param Serializer $serializer
     * @param Validator  $jsonSchemaValidator
     */
    public function __construct(Serializer $serializer, Validator $jsonSchemaValidator)
    {
        $this->serializer = $serializer;
        $this->jsonSchemaValidator = $jsonSchemaValidator;
        $this->routes = [];
    }

    /**
     * @param string $routesPath
     * @param string $format
     *
     * @throws InvalidRoutesException
     */
    public function addRoutesFromFile(string $routesPath, string $format)
    {
        $routeCollection = $this->getRouteCollectionFromFile($routesPath, $format);

        $this->routes = array_merge($this->routes, $routeCollection->getRoutes());
    }

    /**
     * @param string $path
     * @param string $format
     *
     * @return RouteCollection
     * @throws InvalidRoutesException
     */
    protected function getRouteCollectionFromFile(string $path, string $format): RouteCollection
    {
        $rawRoutes = file_get_contents($path);
        $routes = json_decode($rawRoutes);

        if ($routes === null) {
            throw new InvalidRoutesException(sprintf('Routes file \'%s\' is not a valid json file', $path));
        }

        $this->jsonSchemaValidator->validate(
            $routes,
            (object) ['$ref' => 'file://' . dirname(__FILE__) . '/../Resources/schemas/route_collection.schema.json']
        );

        if (!$this->jsonSchemaValidator->isValid()) {
            throw new InvalidRoutesException(
                sprintf(
                    'Routes file \'%s\' is not valid: %s',
                    $path,
                    json_encode($this->jsonSchemaValidator->getErrors())
                )
            );
        }

        return $this->serializer->deserialize($rawRoutes, RouteCollection::class, $format);
    }

    /**
     * @param Request $request
     *
     * @return Route|null
     */
    public function getRouteForRequest(Request $request): ?Route
    {
        if (empty($this->routes)) {
            return null;
        }

        $matchingRoute = null;
        foreach ($this->routes as $route) {
            $match = $this->compareRequestToEndpoint($request, $route->getEndpoint());

            if ($match == self::STRICT_MATCH) {
                $matchingRoute = $route;
                break;
            } elseif (!$route->isStrict() && $match == self::MATCH) {
                $matchingRoute = $route;
                break;
            }
        }

        return $matchingRoute;
    }

    /**
     * Compares a request with an endpoint. It will generate a score based on the request method, path and query
     * parameters. The final score will be:
     *  - self::NO_MATCH: either the request method, endpoint or some of the query parameters don't match or are missing
     *  - self::MATCH: method, endpoint and all query parameters defined in the request match, but there are extra
     *    parameters in the request that were not expected.
     *  - self::STRICT_MATCH: method, endpoint and all query parameters defined in the request, and there is no
     *    unexpected query parameter in the request.
     *
     * @param Request  $request
     * @param Endpoint $endpoint
     *
     * @return int
     */
    protected function compareRequestToEndpoint(Request $request, Endpoint $endpoint): int
    {
        if (strtolower($request->getMethod()) != strtolower($endpoint->getMethod())) {
            return self::NO_MATCH;
        }

        if (!$this->isValidPath($request->getPath(), $endpoint)) {
            return self::NO_MATCH;
        }

        if (count($request->getQueryParameters()) == 0 && count($endpoint->getQueryParameters()) == 0) {
            return self::STRICT_MATCH;
        }

        $matched = 0;
        foreach ($endpoint->getQueryParameters() as $endpointQueryParameter) {
            foreach ($request->getQueryParameters() as $requestQueryParameter) {
                $parameterNameMatches = $endpointQueryParameter->getName() == $requestQueryParameter->getName();
                $validParameterType = $this->validateParameterType(
                    $requestQueryParameter->getValue(),
                    $endpointQueryParameter->getType()
                );

                if ($parameterNameMatches && $validParameterType) {
                    $matched += 1;
                }
            }
        }

        $requiredQueryParameters = count($endpoint->getQueryParameters());
        $presentQueryParameters = count($request->getQueryParameters());

        if ($matched == $requiredQueryParameters && $requiredQueryParameters == $presentQueryParameters) {
            return self::STRICT_MATCH;
        } elseif ($matched == $requiredQueryParameters) {
            return self::MATCH;
        } else {
            return self::NO_MATCH;
        }
    }

    /**
     * @param string   $path
     * @param Endpoint $endpoint
     *
     * @return bool
     */
    protected function isValidPath(string $path, Endpoint $endpoint): bool
    {
        $pattern = RegexGenerator::getRegex($endpoint);
        return preg_match($pattern, $path) === 1;
    }

    /**
     * @param string $value
     * @param string $type
     * @return bool
     */
    protected function validateParameterType(string $value, string $type): bool
    {
        $regex = RegexGenerator::getTypeRegex($type);
        return preg_match(sprintf('/^%s$/', $regex), $value) === 1;
    }
}
