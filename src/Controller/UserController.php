<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 17/05/18
 * Time: 20:50
 */

namespace Happy\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;

/**
 * Class UserController
 *
 * @package Happy\Controller
 * @Route("/api")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/user",
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
    public function getUsers(): JsonResponse {

        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }

    /**
     * @param string $id
     * @Route("/user/{id}",
     *     name="_happy_get_user",
     *     methods={"GET"},
     *     requirements={
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @SWG\Response(
     *     response=200,
     *     description="Return user by id"
     * )
     * @SWG\Tag(name="user")
     *
     * @return JsonResponse
     */
    public function getUserById($id): JsonResponse {

        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }

    /**
     * @param Request $request
     *
     * @Route("/user", name="_happy_post_user", methods={"POST"})
     * @SWG\Response(
     *     response=201,
     *     description="create user by method Post"
     * )
     * @SWG\Tag(name="user")
     *
     * @return JsonResponse
     */
    public function postUser(Request $request): JsonResponse {

        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    /**
     * @param string  $id
     *
     * @Route("/user/{id}",
     *     name="_happy_edit_user",
     *     methods={"PATCH", "PUT"},
     *     requirements={
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @SWG\Response(
     *     response=200,
     *     description="Edit User by methods PATCH/PUT"
     * )
     * @SWG\Tag(name="user")
     *
     * @return JsonResponse
     */
    public function editUser($id): JsonResponse {

        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }

    /**
     * @param string  $id
     *
     * @Route("/user/{id}",
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
    public function removeUser($id): JsonResponse {

        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }

}