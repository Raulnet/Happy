<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 15/05/18
 * Time: 00:06.
 */

namespace Happy\Tests\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Happy\Tests\AbstractTestCase;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Class GlobalRepositoryTest.
 */
class GlobalRepositoryTest extends AbstractTestCase
{
    /** @var EntityManager */
    private $manager;

    public function setUp()
    {
        $kernel = self::bootKernel();
        $this->manager = $kernel->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    public function testEntityRepository() {
        $entitiesMap = $this->dumpEntityProperties();
        foreach ($entitiesMap as $className => $annotations) {
            $this->annotationsTest($className, $annotations);
        }

        $this->assertTrue(true);
    }

    /**
     * @param string $className
     * @param array  $annotations
     */
    private function annotationsTest(string $className, array $annotations): void
    {
        foreach ($annotations as $annotation) {
            if($annotation instanceof Entity) {
                $entityRepository = $this->manager->getRepository($className);
                $this->assertTrue($entityRepository instanceof EntityRepository);
            }
        }
    }

    private function dumpEntityProperties(): array
    {
        $annotationReader = new AnnotationReader();
        $resultScan = preg_grep('/^([^.])/', scandir(__DIR__.'/../../src/Entity'));
        $entitiesMap = [];
        foreach ($resultScan as $result) {
            if (preg_match('/^.*php$/i', $result)) {
                $entityName = 'Happy\Entity\\'.str_replace('.php', '', $result);
                $entity = new \ReflectionClass($entityName);
                $entitiesMap[$entityName] = $annotationReader->getClassAnnotations($entity);
            }
        }

        return $entitiesMap;
    }
}