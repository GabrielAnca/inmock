<?php

namespace InMockBundle\Prototype;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class Response
 *
 * @package InMockBundle\Prototype
 *
 * @Serializer\ExclusionPolicy("none")
 */
class Response
{
    /**
     * @var string $template
     * @Serializer\SerializedName("template")
     * @Serializer\Type("string")
     */
    protected $template;

    /**
     * @var int $statusCode
     * @Serializer\SerializedName("statusCode")
     * @Serializer\Type("int")
     */
    protected $statusCode;

    /**
     * @return string
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * @param string $template
     * @return Response
     */
    public function setTemplate(string $template): Response
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return Response
     */
    public function setStatusCode(int $statusCode): Response
    {
        $this->statusCode = $statusCode;
        return $this;
    }
}
