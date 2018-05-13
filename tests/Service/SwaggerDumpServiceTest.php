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
use Nelmio\ApiDocBundle\ApiDocGenerator;

/**
 * Class SwaggerDumpServiceTest.
 */
class SwaggerDumpServiceTest extends AbstractTestCase
{
    /** @var ApiDocGenerator */
    private $generatorLocator;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->generatorLocator = $kernel->getContainer()->get('nelmio_api_doc.generator');
    }

    public function testGetSwaggerDoc() {
        $swaggerDumpService = new SwaggerDumpService($this->generatorLocator);
        $doc = $swaggerDumpService->getSwaggerDoc();

        $this->assertTrue(is_string($doc));

        $this->assertTrue(is_array(json_decode($doc, true)));
    }
}
