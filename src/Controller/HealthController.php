<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 16/05/18
 * Time: 21:28.
 */

namespace Happy\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;

/**
 * Class HealthConttroller.
 *
 * @Route("/api")
 */
class HealthController extends Controller
{
    /**
     * @Route("/health", name="_happy_health", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns Status Application"
     * )
     * @SWG\Tag(name="health")
     *
     * @return JsonResponse
     */
    public function health(): JsonResponse {
        return new JsonResponse('OK', JsonResponse::HTTP_OK);
    }
}