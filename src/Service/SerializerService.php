<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 18/05/18
 * Time: 23:11.
 */

namespace Happy\Service;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Serializer;

/**
 * Class SerializerService.
 */
class SerializerService extends AbstractService
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * SerializerService constructor.
     */
    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * @return Serializer
     */
    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }
}
