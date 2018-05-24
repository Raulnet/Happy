<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 21/05/18
 * Time: 21:39.
 */

namespace Happy\Service\Converter;

use Doctrine\ORM\EntityManagerInterface;
use Happy\Service\NormalizerService;
use Happy\Service\SerializerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class EntityConverterService.
 */
class EntityConverterService
{
    const ENTITY_KEY_ID = 'id';

    /** @var EntityManagerInterface */
    private $manager;

    /** @var SerializerService */
    private $serializerService;
    /** @var NormalizerService */
    protected $normalizer;

    /**
     * EntityConverterService constructor.
     *
     * @param EntityManagerInterface $manager
     * @param SerializerService      $serializerService
     * @param NormalizerService      $normalizerService
     */
    public function __construct(
        EntityManagerInterface $manager,
        SerializerService $serializerService,
        NormalizerService $normalizerService
    ) {
        $this->manager = $manager;
        $this->serializerService = $serializerService;
        $this->normalizer = $normalizerService;
    }

    /**
     * @param string $className
     * @param array  $data
     *
     * @return object
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function buildEntity(string $className, Request $request): object
    {
        $hydrator = $this->normalizer->getHydrator($className);

        return $hydrator->handleRequest(new $className(), $request);
    }

    /**
     * @param string $className
     * @param string $id
     *
     * @return object
     */
    public function findEntity(string $className, string $id): object
    {
        $entity = $this->manager->getRepository($className)->find($id);
        if (empty($entity)) {
            throw new HttpException(JsonResponse::HTTP_NOT_FOUND, 'http.exception.entity.not.found');
        }

        return $entity;
    }
}
