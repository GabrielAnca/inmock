<?php

namespace InMockBundle\Generator;

use InMockBundle\Exception\InvalidEndpointException;
use InMockBundle\Exception\ParameterNotFoundException;
use InMockBundle\Prototype\Endpoint;
use JMS\Serializer\SerializerBuilder;

/**
 * Class RegexGenerator
 *
 * @package InMockBundle\Generator
 */
class RegexGenerator
{
    /**
     * @param Endpoint $endpoint
     *
     * @return string
     *
     * @throws InvalidEndpointException
     */
    public static function getRegex(Endpoint $endpoint): string
    {
        preg_match_all('/\{([A-Za-z]{1}[A-Za-z0-9-_]*)\}/', $endpoint->getPath(), $parameters);

        // Using ` because it is not allowed in urls
        $regex = sprintf('`^%s$`', preg_quote($endpoint->getPath()));
        if (count($parameters[1]) > 0) {
            try {
                foreach ($parameters[1] as $parameter) {
                    $parameterRegex = self::getParameterRegex($parameter, $endpoint);

                    // preg_quote quoted all the braces, so we need to look for \{parameter\}
                    $regex = preg_replace(sprintf('/\\\{%s*\\\}/', $parameter), $parameterRegex, $regex, 1);
                }
            } catch (ParameterNotFoundException $e) {
                throw new InvalidEndpointException(sprintf('Endpoint is invalid: %s', $e->getMessage()), 0, $e);
            }
        }

        return $regex;
    }

    /**
     * @param string   $parameter
     * @param Endpoint $endpoint
     *
     * @return string
     *
     * @throws ParameterNotFoundException
     */
    public static function getParameterRegex(string $parameter, Endpoint $endpoint): string
    {
        foreach ($endpoint->getParameters() as $parameterInformation) {
            if ($parameterInformation->getName() == $parameter) {
                if (!empty($parameterInformation->getPattern())) {
                    return $parameterInformation->getPattern();
                } else {
                    return self::getTypeRegex($parameterInformation->getType());
                }
            }
        }

        throw new ParameterNotFoundException(sprintf('Parameter \'%s\' not found', $parameter));
    }

    /**
     * @param string $type
     * @return string
     */
    public static function getTypeRegex(string $type): string
    {
        switch ($type) {
            case 'int':
                return '[0-9]+';
            case 'boolean':
                return 'true|false';
            case 'string':
            default:
                // default == anything
                return '.+';
        }
    }
}
