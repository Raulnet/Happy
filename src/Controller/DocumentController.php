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
 * Class DocumentController.
 *
 * @Route("/api")
 */
class DocumentController extends AbstractController
{
    /**
     * @param string $projectId
     *
     * @Route("/project/{projectId}/document",
     *     name="_happy_get_documents",
     *     methods={"GET"},
     *     requirements={
     *          "projectId"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @SWG\Response(
     *     response=200,
     *     description="Return all document"
     * )
     * @SWG\Tag(name="document")
     *
     * @return JsonResponse
     */
    public function getDocuments(string $projectId)
    {
        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }

    /**
     * @param string $projectId
     * @param string $id
     *
     * @Route("/project/{projectId}/document/{id}",
     *     name="_happy_get_document",
     *     methods={"GET"},
     *     requirements={
     *          "projectId"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$",
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @SWG\Response(
     *     response=200,
     *     description="Return document by id"
     * )
     * @SWG\Tag(name="document")
     *
     * @return JsonResponse
     */
    public function getDocument(string $projectId, string $id)
    {
        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }

    /**
     * @param string  $projectId
     * @param Request $request
     *
     * @Route("/project/{projectId}/document",
     *     name="_happy_post_document",
     *     methods={"POST"},
     *     requirements={
     *          "projectId"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * )
     * @SWG\Response(
     *     response=201,
     *     description="create document by method Post"
     * )
     * @SWG\Tag(name="document")
     *
     * @return JsonResponse
     */
    public function postDocument(Request $request, string $projectId)
    {
        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    /**
     * @param string $projectId
     * @param string $id
     *
     * @Route("/project/{projectId}/document/{id}",
     *     name="_happy_edit_document",
     *     methods={"PATCH", "PUT"},
     *     requirements={
     *          "projectId"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$",
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @SWG\Response(
     *     response=200,
     *     description="Edit Document by methods PATCH/PUT"
     * )
     * @SWG\Tag(name="document")
     *
     * @return JsonResponse
     */
    public function editDocument(string $projectId, string $id)
    {
        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }

    /**
     * @param string $id
     *
     * @Route("/project/{projectId}/document/{id}",
     *     name="_happy_remove_document",
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
     * @SWG\Tag(name="document")
     *
     * @return JsonResponse
     */
    public function removeDocument($id)
    {
        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }
}