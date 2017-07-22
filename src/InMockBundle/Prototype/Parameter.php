<?php

namespace InMockBundle\Prototype;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class Parameter
 *
 * @package InMockBundle\Prototype
 *
 * @Serializer\ExclusionPolicy("none")
 */
class Parameter
{
    /**
     * @var string $name
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     */
    protected $name;

    /**
     * @var string $type
     * @Serializer\SerializedName("type")
     * @Serializer\Type("string")
     */
    protected $type;

    /**
     * @var string $pattern
     * @Serializer\SerializedName("pattern")
     * @Serializer\Type("string")
     */
    protected $pattern;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Parameter
     */
    public function setName(string $name): Parameter
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Parameter
     */
    public function setType(string $type): Parameter
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     * @return Parameter
     */
    public function setPattern($pattern): Parameter
    {
        $this->pattern = $pattern;
        return $this;
    }
}
