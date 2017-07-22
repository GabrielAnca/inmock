<?php

namespace InMockBundle\Prototype;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class Endpoint
 *
 * @package InMockBundle\Prototype
 *
 * @Serializer\ExclusionPolicy("none")
 */
class Endpoint
{
    /**
     * @var string $method
     * @Serializer\SerializedName("method")
     * @Serializer\Type("string")
     */
    protected $method;

    /**
     * @var string $path
     * @Serializer\SerializedName("path")
     * @Serializer\Type("string")
     */
    protected $path;

    /**
     * @var Parameter[] $parameters
     * @Serializer\SerializedName("parameters")
     * @Serializer\Type("array<InMockBundle\Prototype\Parameter>")
     */
    protected $parameters;

    /**
     * @var Parameter[] $queryParameters
     * @Serializer\SerializedName("queryParameters")
     * @Serializer\Type("array<InMockBundle\Prototype\Parameter>")
     */
    protected $queryParameters;

    /**
     * Endpoint constructor.
     */
    public function __construct()
    {
        $this->parameters = [];
        $this->queryParameters = [];
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return Endpoint
     */
    public function setMethod(string $method): Endpoint
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Endpoint
     */
    public function setPath(string $path): Endpoint
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return Parameter[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param Parameter[] $parameters
     * @return Endpoint
     */
    public function setParameters(array $parameters): Endpoint
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return Parameter[]
     */
    public function getQueryParameters(): array
    {
        return $this->queryParameters;
    }

    /**
     * @param Parameter[] $queryParameters
     * @return Endpoint
     */
    public function setQueryParameters(array $queryParameters): Endpoint
    {
        $this->queryParameters = $queryParameters;
        return $this;
    }
}
