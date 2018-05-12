<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 12/05/18
 * Time: 12:08.
 */

namespace Happy\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AbstractTestCase.
 */
abstract class AbstractTestCase extends WebTestCase
{
    /**
     * Call protected/private method of a class.
     * instantiated object that we will run method on.
     *
     * @param        &$object
     * @param string $methodName method name to call
     * @param array  $parameters array of parameters to pass into method
     *
     * @return mixed
     *
     * @throws \ReflectionException
     */
    protected function invokeMethod(
        &$object,
        string $methodName,
        array $parameters = []
    ) {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}