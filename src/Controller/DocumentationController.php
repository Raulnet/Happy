<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 17/05/18
 * Time: 21:28.
 */

namespace Happy\Controller;

use Happy\Entity\Documentation;
use Happy\Entity\Project;
use Happy\Service\DocumentationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * Class DocumentationController.
 *
 * @Route("/api")
 */
class DocumentationController extends AbstractApiController
{
    /**
     * @var DocumentationService
     */
    private $documentationService;

    /**
     * DocumentationController constructor.
     *
     * @param DocumentationService $documentationService
     */
    public function __construct(DocumentationService $documentationService)
    {
        $this->documentationService = $documentationService;
    }

    /**
     * @param Project $project
     *
     * @Route("/projects/{id}/documentations",
     *     name="_happy_get_documentations",
     *     methods={"GET"},
     *     requirements={
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
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
    public function getDocumentations(Project $project)
    {
        $documentations = $this->getDoctrine()->getRepository(Documentation::class)->findBy(['project' => $project]);

        return $this->apiJsonResponse($documentations, JsonResponse::HTTP_OK);
    }

    /**
     * @param Project       $project
     * @param Documentation $documentation
     *
     * @Route("/projects/{projectId}/documentations/{documentationId}",
     *     name="_happy_get_documentation",
     *     methods={"GET"},
     *     requirements={
     *          "projectId"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$",
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @ParamConverter("project", class="Happy\Entity\Project", options={"id"="projectId"})
     * @ParamConverter("documentation", class="Happy\Entity\Documentation", options={"id"="documentationId"})
     * @SWG\Response(
     *     response=200,
     *     description="Return documentation by id"
     * )
     * @SWG\Tag(name="documentation")
     *
     * @return JsonResponse
     */
    public function getDocumentation(Project $project, Documentation $documentation)
    {
        $this->support($project, $documentation);

        return $this->apiJsonResponse($documentation, JsonResponse::HTTP_OK);
    }

    /**
     * @param Project       $project
     * @param Documentation $documentation
     *
     * @Route("/projects/{id}/documentations",
     *     name="_happy_post_documentation",
     *     methods={"POST"},
     *     requirements={
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * )
     * @ParamConverter("documentation", converter="body.converter", class="Happy\Entity\Documentation")
     * @SWG\Response(
     *     response=201,
     *     description="create documentation by method Post"
     * )
     * @SWG\Tag(name="documentation")
     *
     * @return JsonResponse
     */
    public function postDocumentation(Project $project, Documentation $documentation)
    {
        $documentation->setProject($project);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($documentation);
        $manager->flush();

        return $this->apiJsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param Project $project
     *
     * @Route("/projects/{id}/documentations/raw",
     *     name="_happy_post_documentation_raw",
     *     methods={"POST"},
     *     requirements={
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
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
    public function postRawDocumentation(Request $request, Project $project)
    {
        $this->documentationService->pushDocumentationRaw($project, $request->getContent());

        return $this->apiJsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    /**
     * @param Project       $project
     * @param Documentation $documentation
     *
     * @Route("/projects/{projectId}/documentations/{documentationId}",
     *     name="_happy_edit_documentation",
     *     methods={"PATCH", "PUT"},
     *     requirements={
     *          "projectId"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$",
     *          "documentationId"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @ParamConverter("project", class="Happy\Entity\Project", options={"id"="projectId"})
     * @ParamConverter("documentation", class="Happy\Entity\Documentation", options={"id"="documentationId"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Edit Documentation by methods PATCH/PUT"
     * )
     * @SWG\Tag(name="documentation")
     *
     * @return JsonResponse
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function editDocumentation(Request $request, Project $project, Documentation $documentation)
    {
        $this->support($project, $documentation);
        $hydrator = $this->normalizer->getHydrator(Documentation::class);
        $hydrator->handleRequest($documentation, $request);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();

        return $this->apiJsonResponse($documentation, JsonResponse::HTTP_OK);
    }

    /**
     * @param Project       $project
     * @param Documentation $documentation
     *
     * @Route("/projects/{projectId}/documentations/{documentationId}",
     *     name="_happy_remove_documentation",
     *     methods={"DELETE"},
     *     requirements={
     *          "projectId"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$",
     *          "documentationId"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @ParamConverter("project", class="Happy\Entity\Project", options={"id"="projectId"})
     * @ParamConverter("documentation", class="Happy\Entity\Documentation", options={"id"="documentationId"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Remove (soft delete) User by method DELETE"
     * )
     * @SWG\Tag(name="documentation")
     *
     * @return JsonResponse
     */
    public function removeDocumentation(Project $project, Documentation $documentation)
    {
        $this->support($project, $documentation);
        $documentation->setDateDeleted(new \DateTime('now'));
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();

        return $this->apiJsonResponse($documentation, JsonResponse::HTTP_OK);
    }

    private function support(Project $project, Documentation $documentation)
    {
        if ($project->getId() !== $documentation->getProject()->getId()) {
            throw new HttpException(JsonResponse::HTTP_NOT_FOUND, 'http.exception.project.on.documentation.not.found');
        }
    }
}
