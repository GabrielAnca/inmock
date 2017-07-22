<?php

namespace Tests\InMockBundle\Prototype;

use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AbstractPrototypeTestCase
 * @package Tests\InMockBundle\Prototype
 */
abstract class AbstractPrototypeTestCase extends WebTestCase
{
    /** @var Serializer $serializer */
    protected $serializer;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->serializer = $kernel->getContainer()->get('jms_serializer');
    }
}
