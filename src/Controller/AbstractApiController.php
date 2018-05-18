<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 18/05/18
 * Time: 23:09.
 */

namespace Happy\Controller;

use Happy\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AbstractApiController.
 */
abstract class AbstractApiController extends AbstractController
{
    const FORMAT_JSON = 'json';

    /**
     * @var \JMS\Serializer\Serializer
     */
    private $serializer;

    /**
     * AbstractApiController constructor.
     *
     * @param SerializerService $serializerService
     */
    public function __construct(SerializerService $serializerService)
    {
        $this->serializer = $serializerService->getSerializer();
    }

    /**
     * @param mixed $content
     * @param int   $code
     *
     * @return JsonResponse
     */
    public function apiJsonResponse($content, int $code = JsonResponse::HTTP_OK): JsonResponse
    {
        $data = $this->serializer->serialize($content, self::FORMAT_JSON);
        $response = new JsonResponse();
        $response->setStatusCode($code);
        $response->setContent($data);

        return $response;
    }
}