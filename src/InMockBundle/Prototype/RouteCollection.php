<?php

namespace InMockBundle\Prototype;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class RouteCollection
 *
 * @package InMockBundle\Prototype
 *
 * @Serializer\ExclusionPolicy("none")
 */
class RouteCollection
{
    /**
     * @var Route[] $routes
     * @Serializer\SerializedName("routes")
     * @Serializer\Type("array<InMockBundle\Prototype\Route>")
     */
    protected $routes;

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @param Route[] $routes
     * @return RouteCollection
     */
    public function setRoutes(array $routes): RouteCollection
    {
        $this->routes = $routes;
        return $this;
    }
}
