<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 18/05/18
 * Time: 23:57
 */

namespace Happy\Tests\Service;

use Happy\Service\SerializerService;
use Happy\Tests\AbstractTestCase;

/**
 * Class AbstractServiceTest
 *
 * @package Happy\Tests\Service
 */
class AbstractServiceTest extends AbstractTestCase
{
    const MESSAGE_ERROR = 'mock message error';

    public function testLastError() {
        // use Default Service;
        $service = new SerializerService();
        $this->assertNull($service->lastError());
        $this->assertNull($service->setLastError(self::MESSAGE_ERROR));
        $this->assertTrue($service->lastError() === self::MESSAGE_ERROR);
        $this->assertNull($service->setLastError(null));
        $this->assertNull($service->lastError());
    }
}