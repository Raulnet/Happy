<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 16/05/18
 * Time: 22:12.
 */

namespace Happy\Controller;

use Happy\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;

/**
 * Class ProjectController.
 *
 * @Route("/api")
 */
class ProjectController extends Controller
{
    /**
     * @param string $id
     * @Route("/project/{id}",
     *     name="_happy_get_project",
     *     methods={"GET"},
     *     requirements={
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @SWG\Response(
     *     response=200,
     *     description="Return project by id"
     * )
     * @SWG\Tag(name="project")
     *
     * @return JsonResponse
     */
    public function getProject(string $id): JsonResponse {
        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @Route("/project", name="_happy_post_project", methods={"POST"})
     * @SWG\Response(
     *     response=201,
     *     description="create Project by method Post"
     * )
     * @SWG\Tag(name="project")
     *
     * @return JsonResponse
     */
    public function postProject(Request $request): JsonResponse {
        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @Route("/project/{id}",
     *     name="_happy_edit_project",
     *     methods={"PATCH", "PUT"},
     *     requirements={
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @SWG\Response(
     *     response=202,
     *     description="Edit Project by methods Patch/Put"
     * )
     * @SWG\Tag(name="project")
     *
     * @return JsonResponse
     */
    public function editProject(Request $request, string $id): JsonResponse {
        return new JsonResponse(null, JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * @param string $id
     *
     * @Route("/project/{id}",
     *     name="_happy_remove_project",
     *     methods={"DELETE"},
     *     requirements={
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @SWG\Response(
     *     response=202,
     *     description="Remove (soft delete) Project by method DELETE"
     * )
     * @SWG\Tag(name="project")
     *
     * @return JsonResponse
     */
    public function removeProject(string $id): JsonResponse {
        return new JsonResponse(null, JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * @param string $id
     *
     * @Route("/project/{id}/restore",
     *     name="_happy_restore_project",
     *     methods={"PATCH"},
     *     requirements={
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @SWG\Response(
     *     response=204,
     *     description="Remove (soft delete) Project by method PATCH"
     * )
     * @SWG\Tag(name="project")
     *
     * @return JsonResponse
     */
    public function restoreProject(string $id): JsonResponse {
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}