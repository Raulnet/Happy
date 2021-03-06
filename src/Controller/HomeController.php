<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 12/05/18
 * Time: 00:15.
 */

namespace Happy\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;

/**
 * Class HomeController.
 */

/**
 * Class HomeController.
 *
 * @Route("/api")
 */
class HomeController extends Controller
{
    /**
     * @Route("/", name="_happy_home", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns list of api available"
     * )
     * @SWG\Tag(name="home")
     *
     * @return JsonResponse
     */
    public function home(): JsonResponse
    {
        $data = [
            'hello',
            'I\'m Happy!',
        ];

        return new JsonResponse($data);
    }
}
