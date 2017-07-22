<?php

namespace InMockBundle\Entity;

/**
 * Class Request
 *
 * @package InMockBundle\Entity
 */
class Request
{
    /**
     * @var string $method
     */
    protected $method;

    /**
     * @var string $path
     */
    protected $path;

    /**
     * @var Parameter[] $queryParameters
     */
    protected $queryParameters;

    /**
     * Request constructor.
     */
    public function __construct()
    {
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
     * @return Request
     */
    public function setMethod(string $method): Request
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
     * @return Request
     */
    public function setPath(string $path): Request
    {
        $this->path = $path;
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
     * @return Request
     */
    public function setQueryParameters(array $queryParameters): Request
    {
        $this->queryParameters = $queryParameters;
        return $this;
    }
}
