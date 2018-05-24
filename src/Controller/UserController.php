<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 17/05/18
 * Time: 20:50.
 */

namespace Happy\Controller;

use Happy\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;

/**
 * Class UserController.
 *
 * @Route("/api")
 */
class UserController extends AbstractApiController
{
    /**
     * @Route("/users",
     *     name="_happy_get_users",
     *     methods={"GET"}
     *     )
     * @SWG\Response(
     *     response=200,
     *     description="Return all user"
     * )
     * @SWG\Tag(name="user")
     *
     * @return JsonResponse
     */
    public function getUsers(): JsonResponse
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->apiJsonResponse($users);
    }

    /**
     * @param User $user
     * @Route("/users/{id}",
     *     name="_happy_get_user",
     *     methods={"GET"},
     *     requirements={
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @ParamConverter("user", class="Happy\Entity\User")
     * @SWG\Response(
     *     response=200,
     *     description="Return user by id"
     * )
     * @SWG\Tag(name="user")
     *
     * @return JsonResponse
     */
    public function getUserById(User $user): JsonResponse
    {
        return $this->apiJsonResponse($user, JsonResponse::HTTP_OK);
    }

    /**
     * @param User $user
     *
     * @Route("/users", name="_happy_post_user", methods={"POST"})
     * @ParamConverter("user", converter="body.converter", class="Happy\Entity\User")
     * @SWG\Response(
     *     response=201,
     *     description="create user by method Post"
     * )
     * @SWG\Tag(name="user")
     *
     * @return JsonResponse
     */
    public function postUser(User $user): JsonResponse
    {
        $manager = $this->getDoctrine()->getManager();
        if ($manager->getRepository(User::class)->find($user->getId())) {
            throw new HttpException(JsonResponse::HTTP_CONFLICT, 'http.exception.user.already.created');
        }
        $manager->persist($user);
        $manager->flush();

        return $this->apiJsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @Route("/users/{id}",
     *     name="_happy_edit_user",
     *     methods={"PATCH", "PUT"},
     *     requirements={
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @ParamConverter("user", converter="body.converter", class="Happy\Entity\User")
     * @SWG\Response(
     *     response=200,
     *     description="Edit User by methods PATCH/PUT"
     * )
     * @SWG\Tag(name="user")
     *
     * @return JsonResponse
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function editUser(Request $request, User $user): JsonResponse
    {
        $hydrator = $this->normalizer->getHydrator(User::class);
        $hydrator->handleRequest($user, $request);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();

        return $this->apiJsonResponse(null, JsonResponse::HTTP_OK);
    }

    /**
     * @param User $user
     *
     * @Route("/users/{id}",
     *     name="_happy_remove_user",
     *     methods={"DELETE"},
     *     requirements={
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @SWG\Response(
     *     response=200,
     *     description="Remove (soft delete) User by method DELETE"
     * )
     * @SWG\Tag(name="user")
     *
     * @return JsonResponse
     */
    public function removeUser(User $user): JsonResponse
    {
        $user->setDateDeleted(new \DateTime('now'));
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }
}
