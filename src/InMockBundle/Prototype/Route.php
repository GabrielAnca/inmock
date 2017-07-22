<?php

namespace InMockBundle\Prototype;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class Route
 *
 * @package InMockBundle\Prototype
 *
 * @Serializer\ExclusionPolicy("none")
 */
class Route
{
    /**
     * @var Endpoint $endpoint
     * @Serializer\SerializedName("endpoint")
     * @Serializer\Type("InMockBundle\Prototype\Endpoint")
     */
    protected $endpoint;

    /**
     * @var Response $response
     * @Serializer\SerializedName("response")
     * @Serializer\Type("InMockBundle\Prototype\Response")
     */
    protected $response;

    /**
     * @var boolean $strict
     * @Serializer\SerializedName("strict")
     * @Serializer\Type("boolean")
     */
    protected $strict;

    /**
     * @return Endpoint
     */
    public function getEndpoint(): Endpoint
    {
        return $this->endpoint;
    }

    /**
     * @param Endpoint $endpoint
     * @return Route
     */
    public function setEndpoint(Endpoint $endpoint): Route
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param Response $response
     * @return Route
     */
    public function setResponse(Response $response): Route
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStrict(): bool
    {
        return $this->strict;
    }

    /**
     * @param bool $strict
     * @return Route
     */
    public function setStrict(bool $strict): Route
    {
        $this->strict = $strict;
        return $this;
    }
}
