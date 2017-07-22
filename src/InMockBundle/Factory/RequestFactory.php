<?php

namespace InMockBundle\Factory;

use InMockBundle\Entity\Parameter;
use InMockBundle\Entity\Request;

/**
 * Class RequestFactory
 *
 * @package InMockBundle\Factory
 */
class RequestFactory
{
    /**
     * @param string $method
     * @param string $path
     * @param array  $parameters
     *
     * @return Request
     */
    public static function build(string $method, string $path, array $parameters = []): Request
    {
        $queryParameters = [];
        foreach ($parameters as $name => $value) {
            $queryParameters[] = (new Parameter())
                ->setName($name)
                ->setValue($value);
        }

        $request = (new Request())
            ->setMethod($method)
            ->setPath($path)
            ->setQueryParameters($queryParameters);

        return $request;
    }
}
