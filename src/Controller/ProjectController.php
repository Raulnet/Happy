<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 16/05/18
 * Time: 22:12.
 */

namespace Happy\Controller;

use Happy\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;

/**
 * Class ProjectController.
 *
 * @Route("/api")
 */
class ProjectController extends AbstractApiController
{
    /**
     * @param Project $project
     * @Route("/projects/{id}",
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
    public function getProject(Project $project): JsonResponse
    {
        return $this->apiJsonResponse($project, JsonResponse::HTTP_OK);
    }

    /**
     * @param Project $project
     *
     * @Route("/projects", name="_happy_post_project", methods={"POST"})
     * @ParamConverter("project", converter="body.converter", class="Happy\Entity\Project")
     * @SWG\Response(
     *     response=201,
     *     description="create Project by method Post"
     * )
     * @SWG\Tag(name="project")
     *
     * @return JsonResponse
     */
    public function postProject(Project $project): JsonResponse
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($project);
        $manager->flush();
        return $this->apiJsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param Project $project
     *
     * @Route("/projects/{id}",
     *     name="_happy_edit_project",
     *     methods={"PATCH", "PUT"},
     *     requirements={
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @ParamConverter("project", class="Happy\Entity\Project")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Edit Project by methods Patch/Put"
     * )
     * @SWG\Tag(name="project")
     *
     * @return JsonResponse
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function editProject(Request $request, Project $project): JsonResponse
    {
        $hydrator = $this->normalizer->getHydrator(Project::class);
        $hydrator->handleRequest($project, $request);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->apiJsonResponse(null, JsonResponse::HTTP_OK);
    }

    /**
     * @param string $id
     *
     * @Route("/projects/{id}",
     *     name="_happy_remove_project",
     *     methods={"DELETE"},
     *     requirements={
     *          "id"="^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$"
     *          }
     *     )
     * @ParamConverter("project", class="Happy\Entity\Project")
     * @SWG\Response(
     *     response=200,
     *     description="Remove (soft delete) Project by method DELETE"
     * )
     * @SWG\Tag(name="project")
     *
     * @return JsonResponse
     */
    public function removeProject(Project $project): JsonResponse
    {
        $project->setDateDeleted(new \DateTime('now'));
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }
}