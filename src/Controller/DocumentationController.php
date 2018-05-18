<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 17/05/18
 * Time: 21:28.
 */

namespace Happy\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * Class DocumentationController.
 *
 * @Route("/api")
 */
class DocumentationController extends AbstractController
{
    /**
     * @param string $projectId
     *
     * @Route("/project/{projectId}/documentation",
     *     name="_happy_get_documentations",
     *     methods={"GET"},
     *     requirements={
     *          "projectId"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @SWG\Response(
     *     response=200,
     *     description="Return all documentation"
     * )
     * @SWG\Tag(name="documentation")
     *
     * @return JsonResponse
     */
    public function getDocumentations(string $projectId)
    {
        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }

    /**
     * @param string $projectId
     * @param string $id
     *
     * @Route("/project/{projectId}/documentation/{id}",
     *     name="_happy_get_documentation",
     *     methods={"GET"},
     *     requirements={
     *          "projectId"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$",
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @SWG\Response(
     *     response=200,
     *     description="Return documentation by id"
     * )
     * @SWG\Tag(name="documentation")
     *
     * @return JsonResponse
     */
    public function getDocumentation(string $projectId, string $id)
    {
        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }

    /**
     * @param string  $projectId
     * @param Request $request
     *
     * @Route("/project/{projectId}/documentation",
     *     name="_happy_post_documentation",
     *     methods={"POST"},
     *     requirements={
     *          "projectId"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * )
     * @SWG\Response(
     *     response=201,
     *     description="create documentation by method Post"
     * )
     * @SWG\Tag(name="documentation")
     *
     * @return JsonResponse
     */
    public function postDocumentation(Request $request, string $projectId)
    {
        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    /**
     * @param string $projectId
     * @param string $id
     *
     * @Route("/project/{projectId}/documentation/{id}",
     *     name="_happy_edit_documentation",
     *     methods={"PATCH", "PUT"},
     *     requirements={
     *          "projectId"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$",
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @SWG\Response(
     *     response=200,
     *     description="Edit Documentation by methods PATCH/PUT"
     * )
     * @SWG\Tag(name="documentation")
     *
     * @return JsonResponse
     */
    public function editDocumentation(string $projectId, string $id)
    {
        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }

    /**
     * @param string $id
     *
     * @Route("/project/{projectId}/documentation/{id}",
     *     name="_happy_remove_documentation",
     *     methods={"DELETE"},
     *     requirements={
     *          "projectId"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$",
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @SWG\Response(
     *     response=200,
     *     description="Remove (soft delete) User by method DELETE"
     * )
     * @SWG\Tag(name="documentation")
     *
     * @return JsonResponse
     */
    public function removeDocumentation($id)
    {
        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }
}