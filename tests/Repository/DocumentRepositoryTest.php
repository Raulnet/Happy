<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 14/05/18
 * Time: 23:52
 */

namespace Happy\Tests\Repository;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Happy\Entity\Documentation;
use Happy\Tests\AbstractTestCase;

/**
 * Class DocumentRepositoryTest
 *
 * @package Happy\Tests\Repository
 */
class DocumentRepositoryTest extends AbstractTestCase
{
    /** @var EntityManager */
    private $manager;

    public function setUp()
    {
        $kernel = self::bootKernel();
        $this->manager = $kernel->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    public function testDocumentRepository()
    {
        $entityRepository = $this->manager->getRepository(Documentation::class);
        $this->assertTrue($entityRepository instanceof EntityRepository);
    }

}