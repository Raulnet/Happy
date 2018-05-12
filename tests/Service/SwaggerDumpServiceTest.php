<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 12/05/18
 * Time: 12:14.
 */

namespace Happy\Tests\Service;

use Happy\Service\SwaggerDumpService;
use Happy\Tests\AbstractTestCase;
use Symfony\Component\HttpKernel\HttpKernel;

/**
 * Class SwaggerDumpServiceTest.
 */
class SwaggerDumpServiceTest extends AbstractTestCase
{
    /** @var HttpKernel */
    private $httpKernel;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->httpKernel = $kernel->getContainer()->get('http_kernel');
    }

    public function testGetSwaggerDoc() {
        $swaggerDumpService = new SwaggerDumpService($this->httpKernel);
        $doc = $swaggerDumpService->getSwaggerDoc();

        $this->assertTrue(is_string($doc));

        $this->assertTrue(is_array(json_decode($doc, true)));
    }
}
