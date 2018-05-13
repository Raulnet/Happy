<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 12/05/18
 * Time: 00:15.
 */

namespace Happy\Controller;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToMany;
use Happy\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;

/**
 * Class HomeController.
 *
 * @Route("/api")
 */
class HomeController extends Controller
{
    /**
     * @Route("/", name="happy_home", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns list of api available"
     * )
     * @SWG\Tag(name="home")
     *
     * @return JsonResponse
     */
    public function home()
    {
        $data = [
            'hello',
            'I\'m Happy!',
        ];

        return new JsonResponse($data);
    }
}
