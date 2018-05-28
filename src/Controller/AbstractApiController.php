<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 18/05/18
 * Time: 23:09.
 */

namespace Happy\Controller;

use Happy\Service\NormalizerService;
use Happy\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AbstractApiController.
 */
abstract class AbstractApiController extends AbstractController
{
    /** @var \JMS\Serializer\Serializer */
    private $serializer;
    /** @var NormalizerService */
    protected $normalizer;

    /**
     * AbstractApiController constructor.
     *
     * @param SerializerService $serializerService
     * @param NormalizerService $normalizerService
     */
    public function __construct(SerializerService $serializerService, NormalizerService $normalizerService)
    {
        $this->serializer = $serializerService->getSerializer();
        $this->normalizer = $normalizerService;
    }

    /**
     * @param mixed $content
     * @param int   $code
     *
     * @return JsonResponse
     */
    public function apiJsonResponse($content, int $code = JsonResponse::HTTP_OK): JsonResponse
    {
        $content = $this->serializer->serialize($content, SerializerService::SERIALIZE_FORMAT_JSON);

        $response = new JsonResponse();
        $response->setStatusCode($code);
        $response->setContent($content);

        return $response;
    }
}
