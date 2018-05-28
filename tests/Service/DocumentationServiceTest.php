<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 28/05/18
 * Time: 01:16
 */

namespace Happy\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use Happy\Entity\Project;
use Happy\Service\DocumentationService;
use Happy\Tests\AbstractTestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;


/**
 * Class DocumentationTest
 *
 * @package Happy\Tests\Service
 */
class DocumentationServiceTest extends AbstractTestCase
{
    public function testPushDocumentationRawException(): void
    {
        $manager              = $this->getMockBuilder(EntityManagerInterface::class)
                                     ->disableOriginalConstructor()
                                     ->getMock();
        $documentationService = new DocumentationService($manager);
        $project              = new Project();
        $dockSwagger          = json_encode(['info' => ['version' => '']]);
        $this->expectException(HttpException::class);
        $documentationService->pushDocumentationRaw($project, $dockSwagger);
    }
}