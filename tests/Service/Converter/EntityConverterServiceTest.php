<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 24/05/18
 * Time: 23:08.
 */

namespace Happy\Tests\Service\Converter;

use Doctrine\ORM\EntityManagerInterface;
use Happy\Entity\User;
use Happy\Repository\UserRepository;
use Happy\Service\Converter\EntityConverterService;
use Happy\Service\NormalizerService;
use Happy\Service\SerializerService;
use Happy\Tests\AbstractTestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class EntityConverterServiceTest.
 */
class EntityConverterServiceTest extends AbstractTestCase
{
    public function testFindEntity()
    {
        $serializer = $this->getMockBuilder(SerializerService::class)->disableOriginalConstructor()->getMock();
        $normalizer = $this->getMockBuilder(NormalizerService::class)->disableOriginalConstructor()->getMock();
        $repository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $repository->expects($this->once())->method('find')->willReturn(null);
        $manager = $this->getMockBuilder(EntityManagerInterface::class)->disableOriginalConstructor()->getMock();
        $manager->expects($this->once())->method('getRepository')->willReturn($repository);
        $entityConverterService = new EntityConverterService($manager, $serializer, $normalizer);
        $this->expectException(HttpException::class);
        $entityConverterService->findEntity(User::class, 'id');
    }

    public function testReturnFindEntity()
    {
        $serializer = $this->getMockBuilder(SerializerService::class)->disableOriginalConstructor()->getMock();
        $normalizer = $this->getMockBuilder(NormalizerService::class)->disableOriginalConstructor()->getMock();
        $repository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $user = new User();
        $repository->expects($this->once())->method('find')->willReturn($user);
        $manager = $this->getMockBuilder(EntityManagerInterface::class)->disableOriginalConstructor()->getMock();
        $manager->expects($this->once())->method('getRepository')->willReturn($repository);

        $entityConverterService = new EntityConverterService($manager, $serializer, $normalizer);
        $this->assertTrue($entityConverterService->findEntity(User::class, 'id') instanceof User);
    }
}
