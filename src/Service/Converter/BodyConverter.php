<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 21/05/18
 * Time: 20:47.
 */

namespace Happy\Service\Converter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class BodyConverter.
 */
class BodyConverter implements ParamConverterInterface
{
    /** @var EntityConverterService */
    private $entityConverterService;

    /**
     * BodyConverter constructor.
     *
     * @param EntityConverterService $entityConverterService
     */
    public function __construct(EntityConverterService $entityConverterService)
    {
        $this->entityConverterService = $entityConverterService;
    }

    /**
     * @param Request        $request
     * @param ParamConverter $configuration
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $name   = $configuration->getName();
        $object = null;

        if (in_array($request->getMethod(), ['GET', 'DELETE', 'PATCH', 'PUT'])) {
            $id     = $request->get('id');
            $object = $this->entityConverterService->findEntity($configuration->getClass(), $id);
        }

        if ('POST' === $request->getMethod()) {
            if (empty($request->getContent())) {
                throw new HttpException(JsonResponse::HTTP_CONFLICT, 'http.exception.data.content.empty');
            }
            $object = $this->entityConverterService->buildEntity($configuration->getClass(), $request);
        }

        if (empty($object)) {
            throw new HttpException(JsonResponse::HTTP_CONFLICT, 'http.exception.' . $name . '.not.converted');
        }
        $request->attributes->set($name, $object);
    }

    /**
     * @param ParamConverter $configuration
     *
     * @return bool
     */
    public function supports(ParamConverter $configuration)
    {
        return (bool)$configuration->getClass();
    }
}